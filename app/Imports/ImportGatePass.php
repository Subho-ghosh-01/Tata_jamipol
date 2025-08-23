<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use App\VendorEmployeeDetails;

HeadingRowFormatter::default('none');

class ImportGatePass implements ToModel,WithHeadingRow
{
	public function model(array $row)
    {
		// print_r($row);
		if($row['ID'] != "" && $row['New Gatepass No'] != "" && $row['New Expiry Date'] != ""){
			VendorEmployeeDetails::where('id',$row['ID'])->update([
				'employee'       => @$row['Employee Name'],
			    'gatepass'       => @$row['New Gatepass No'],
			    'age'            => @$row['Age'],
			    'designation'    => @$row['Designation'],
			    'expiry'         => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(@$row['New Expiry Date']),
			]); 

		}		
    }



}
