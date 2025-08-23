<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\UserLogin;
use Maatwebsite\Excel\Concerns\Exportable;
use Session;
use Maatwebsite\Excel\Concerns\WithMapping;


class ExportGatePass implements FromCollection,WithHeadings,WithMapping
{
	use Exportable;
    private $i = 1;

  
    public function collection()
    {
    	date_default_timezone_set("Asia/Calcutta"); 
        $date = date('Y-m-d');
        $gatepass =  UserLogin::leftjoin('userlogins_employee_details','userlogins_employee_details.userlogins_id','=','userlogins.id')
                        ->where('userlogins.user_type',2)
                        ->where('userlogins_employee_details.expiry',"<",$date)
                        ->select('userlogins.vendor_name_code','userlogins_employee_details.gatepass',
                    			'userlogins_employee_details.employee','userlogins_employee_details.age',
                    			'userlogins_employee_details.designation','userlogins_employee_details.expiry',
                    			'userlogins_employee_details.id')->get();

        return $gatepass;
    }

    public function map($gatepass): array
    {
        return [
            $this->i++,
            $gatepass->vendor_name_code,
            $gatepass->gatepass,
            $gatepass->employee,
            $gatepass->age,
            $gatepass->designation,
            date('d-m-Y',strtotime($gatepass->expiry)),
            $gatepass->id,

        ];
    }

 	public function headings(): array
	{
	    return [
			'Sl No.',
			'Vendor Code',
			'Gatepass No',
			'Employee Name',
			'Age',
			'Designation',
			'Expiry Date',
			'ID',
			'New Gatepass No',
			'New Expiry Date'
	    ];
	}

    
}
