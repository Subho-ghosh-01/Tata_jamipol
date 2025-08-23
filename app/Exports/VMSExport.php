<?php

namespace App\Exports;

use App\Models\VMS;
use App\UserLogin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VMSExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = VMS::query();

        if (!empty($this->filters['from_date']))
            $query->whereDate('created_at', '>=', $this->filters['from_date']);
        if (!empty($this->filters['to_date']))
            $query->whereDate('created_at', '<=', $this->filters['to_date']);
        if (!empty($this->filters['status']) && $this->filters['status'] != 'All')
            $query->where('status', $this->filters['status']);
        // Add expiry_type filtering if needed

        $data = $query->get();

        return $data->map(function ($row) {
            $createdUser = UserLogin::find($row->created_by);
            $subtype = $createdUser
                ? ($createdUser->user_type == 1 ? 'Employee' : ($createdUser->user_type == 2 ? 'Vendor' : 'N/A'))
                : 'N/A';
            $status = $row->status ?? 'N/A';

            return [
                'Pass No' => $row->full_sl,
                'Vehicle Type' => $row->vehicle_pass_for,
                'Registration No' => $row->vehicle_registration_no,
                'Owner Name' => $row->vehicle_owner_name,
                'Status' => $status,
                'User Type' => $subtype,
                'Created By' => $createdUser->name ?? 'N/A',
                'Created At' => $row->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return ['Pass No', 'Vehicle Type', 'Registration No', 'Owner Name', 'Status', 'User Type', 'Created By', 'Created At'];
    }
}
?>