<?php

namespace App\Imports;

use App\WorkOrder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ImportWorkOrder implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // print_r($row);
        // exit;

        return new WorkOrder([
            'vendor_code' => $row['vendor_code'],
            'order_code' => $row['order_code'],
            'order_validity' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['order_validity']),
        ]);
    }
}
