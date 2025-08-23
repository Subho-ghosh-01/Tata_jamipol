<?php

namespace App\Imports;

use App\Attendence_upload;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class Importattendence implements ToModel, WithHeadingRow
{
    public static $insertedCount = 0;
    public static $duplicateCount = 0;
    public static $duplicates = [];

    protected $division_id;

    public function __construct($division_id)
    {
        $this->division_id = $division_id;
    }

    private function excelTimeToTime($value)
    {
        return is_numeric($value)
            ? gmdate('H:i:s', round($value * 86400)) // convert Excel float to time
            : (date('H:i:s', strtotime($value)) ?: null);
    }

    public function model(array $row)
    {
        try {
            $pno = isset($row['p_no']) ? substr($row['p_no'], 0, 50) : null;
            if (!$pno) {
                Log::warning('Skipping row: PNo is missing.', $row);
                return null;
            }

            $date = isset($row['date']) && !empty($row['date'])
                ? (is_numeric($row['date']) ? Date::excelToDateTimeObject($row['date'])->format('Y-m-d') : date('Y-m-d', strtotime($row['date'])))
                : null;

            if (!$date) {
                Log::warning("Skipping row: Invalid or missing date for PNo: $pno");
                return null;
            }

            if (Attendence_upload::where('PNo', $pno)->whereDate('Date', $date)->where('division_id', $this->division_id)->exists()) {
                self::$duplicateCount++;
                self::$duplicates[] = ['PNo' => $pno, 'Date' => $date];
                return null;
            }

            $shift = $row['shift'] ?? null;
            $inTime = $this->excelTimeToTime($row['in_time'] ?? null);
            $outTime = $this->excelTimeToTime($row['out_time'] ?? null);

            $presant = "ABSENT";
            $extra_hours = null;

            $find_pno = DB::table('Clms_gatepass')->where('emp_pno', $pno)->select('shift')->orderBy('id', 'desc')->first();

            if ($find_pno && $find_pno->shift == $shift) {
                $presant = isset($row['present']) ? substr($row['present'], 0, 10) : null;

                $shift_end_times = [
                    'A-SH' => '14:00',
                    'B-SH' => '22:00',
                    'C-SH' => '06:00 +1',
                    'G-S2' => '18:00',
                    'G-S3' => '16:30',
                    'G-S4_' => '18:30',
                    'G-S5' => '17:30',
                ];

                if ($outTime && isset($shift_end_times[$shift])) {
                    $shift_end_str = $shift_end_times[$shift];
                    $shift_end = ($shift == 'C-SH')
                        ? strtotime("$date +1 day 06:00")
                        : strtotime("$date " . $shift_end_str);

                    $actual_out = strtotime("$date " . $outTime);

                    // Handle C-SH correctly (next-day shift end)
                    if ($shift == 'C-SH' && $actual_out < $shift_end) {
                        $actual_out = strtotime("$date +1 day " . $outTime);
                    }

                    if ($actual_out && $shift_end && $actual_out > $shift_end) {
                        $extra_seconds = $actual_out - $shift_end;
                        $extra_hours = round($extra_seconds / 3600, 2);
                    }
                }
            }

            $attendance = new Attendence_upload([
                'PNo' => $pno,
                'Name' => isset($row['name']) ? substr($row['name'], 0, 100) : null,
                'Designation' => isset($row['designation']) ? substr($row['designation'], 0, 100) : null,
                'CurrentHead' => isset($row['current_head']) ? substr($row['current_head'], 0, 100) : null,
                'Department' => isset($row['department']) ? substr($row['department'], 0, 100) : null,
                'Section' => isset($row['section']) ? substr($row['section'], 0, 100) : null,
                'Date' => $date,
                'InTime' => $inTime,
                'OutTime' => $outTime,
                'TotalInTime' => isset($row['total_in_time']) ? substr($row['total_in_time'], 0, 50) : null,
                'Present' => $presant,
                'Leave' => isset($row['leave']) ? substr($row['leave'], 0, 10) : null,
                'Holiday' => isset($row['holiday']) ? substr($row['holiday'], 0, 10) : null,
                'Shift' => isset($row['shift']) ? substr($row['shift'], 0, 100) : null,
                'ShiftStartTime' => $row['shift_start_time'] ?? null,
                'ShiftEndTime' => $row['shift_end_time'] ?? null,
                'LateHours' => isset($row['late_hours']) ? substr($row['late_hours'], 0, 50) : null,
                'EarlyHours' => isset($row['early_hours']) ? substr($row['early_hours'], 0, 50) : null,
                'Source' => isset($row['source']) ? substr($row['source'], 0, 50) : null,
                'Remark' => $row['remark'] ?? null,
                'Group' => $row['group'] ?? null,
                'division_id' => $this->division_id,
                'extra_hours' => $extra_hours,
            ]);

            $attendance->save();
            self::$insertedCount++;
        } catch (\Exception $e) {
            Log::error("Row insert failed: " . $e->getMessage(), $row);
        }

        return null;
    }
}
