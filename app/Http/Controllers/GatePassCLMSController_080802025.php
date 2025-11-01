<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\UserLogin;
use Session;
use App\Permit;
use App\Job;
use App\Division;
use Auth;
use App\Department;
use App\RenewPermit;
use Mail;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
class GatePassCLMSController extends Controller
{

    
public function index(){
	      $divisions = Division::all();
          $workorder = DB::table('work_order');
          if(Session::get('user_sub_typeSession') == '3'){
            $users       = UserLogin::where('user_type','1')->where('clm_role','Executing_agency')->where('active','Yes')->get();
          }else{
            $users       = UserLogin::where('user_type','1')->where('division_id',Session::get('user_DivID_Session'))->where('clm_role','Executing_agency')->where('active','Yes')->get();
          }
         
        return view('CLMSGatepass',compact('divisions','workorder','users'));
		
        //return view('RequestVGatepass');////
    }
    public function index1(){ 
         // $divisions = Division::all();
 //$gatepasss = DB::table('visitor_gate_pass')->orderBy('id','desc')->get();
       // return view('admin.gatepass_approvals.approve',compact('gatepasss'));
        $UserLogin = UserLogin::all();
        $workorder = DB::table('work_order');
        $gatepasss = DB::table('Clms_gatepass')->where('created_by',Session::get('user_idSession'))
                                            ->where('status','Pending_for_shift_incharge')
                                            ->orwhere('created_by',Session::get('user_idSession'))
                                            ->where('status','Pending_for_hr')
                                            ->orwhere('created_by',Session::get('user_idSession'))
                                            ->where('status','Pending_for_safety')
                                            ->orwhere('created_by',Session::get('user_idSession'))
                                            ->where('status','Pending_for_plant_head')
                                            ->orwhere('created_by',Session::get('user_idSession'))
                                            ->where('status','Pending_executing')
			                                ->orwhere('created_by',Session::get('user_idSession'))
                                            ->where('status','Pending_for_security')
			                                ->orwhere('created_by',Session::get('user_idSession'))
                                            ->where('status','Rejected')
                                            ->orderBy('id', 'DESC')->get();


        $gatepassss = DB::table('Clms_gatepass')->where('created_by',Session::get('user_idSession'))
                             ->where('status','Pending_for_security')
                             ->where('created_by',Session::get('user_idSession'))
                             ->where('status','Rejected')
                              ->orderBy('id', 'DESC')->get();

if(Session::get('clm_role') =='Shift_incharge'){

              $gatepasss = DB::table('Clms_gatepass')->where('status','Pending_for_shift_incharge')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();  

                   $gatepassss = DB::table('Clms_gatepass')
                              
                             ->where('shift_incharge_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();   

}if(Session::get('clm_role') =='Executing_agency'){

              $gatepasss = DB::table('Clms_gatepass')->where('status','Pending_executing')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->where('pending_excueting_by',Session::get('user_idSession'))
                              ->orderBy('id', 'DESC')->get();  

                   $gatepassss = DB::table('Clms_gatepass')
                              
                             ->where('pending_excueting_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();   

}



elseif(Session::get('clm_role') =='hr_dept'){

$gatepasss = DB::table('Clms_gatepass')->where('status','Pending_for_hr')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();    


$gatepassss = DB::table('Clms_gatepass')
                              ->where('hr_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();  


}elseif(Session::get('clm_role') =='Safety_dept'){

$gatepasss = DB::table('Clms_gatepass')->where('status','Pending_for_safety')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();    

if(Session::get('user_sub_typeSession') == 3){
	$gatepassss = DB::table('Clms_gatepass')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();  

}else{
	$gatepassss = DB::table('Clms_gatepass')
                              ->where('safety_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();  

}


}elseif(Session::get('clm_role') =='plant_head'){

$gatepasss = DB::table('Clms_gatepass')->where('status','Pending_for_plant_head')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();    


$gatepassss = DB::table('Clms_gatepass')
                              
                              ->where('plant_head_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();  


}
elseif(Session::get('clm_role') =='security' || Session::get('vms_role') =='Security'){

$gatepasss = DB::table('Clms_gatepass')->where('status','Pending_for_security')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();    


$gatepassss = DB::table('Clms_gatepass')->where('status','Pending_for_security')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();  


}elseif(Session::get('user_sub_typeSession') == 3){
     $gatepasss = DB::table('Clms_gatepass')->orderBy('id', 'DESC')->get();
                             
}


       return view('admin.gatepass_approvals.approve_clms',compact('gatepasss','UserLogin','workorder','gatepassss'));
        //return view('RequestVGatepass');
    }

 public function indext(){
         // $divisions = Division::all();
 //$gatepasss = DB::table('visitor_gate_pass')->orderBy('id','desc')->get();
       // return view('admin.gatepass_approvals.approve',compact('gatepasss'));
        $UserLogin = UserLogin::all();
        $workorder = DB::table('work_order');
        $gatepasss = DB::table('Clms_gatepass')->where('created_by',Session::get('user_idSession'))
                                            ->where('status','Pending_for_shift_incharge')
                                            ->where('created_by',Session::get('user_idSession'))
                                            ->orwhere('status','Pending_for_hr')
                                            ->where('created_by',Session::get('user_idSession'))
                                            ->orwhere('status','Pending_for_safety')
                                            ->where('created_by',Session::get('user_idSession'))
                                            ->orwhere('status','Pending_for_plant_head')
                                            ->orderBy('id', 'DESC')->get();


        $gatepassss = DB::table('Clms_gatepass')->where('created_by',Session::get('user_idSession'))
                             ->where('status','Pending_for_security')
                             ->where('created_by',Session::get('user_idSession'))
                             ->where('status','Rejected')
                              ->orderBy('id', 'DESC')->get();
if(Session::get('clm_role') =='Shift_incharge'){

              $gatepasss = DB::table('Clms_gatepass')->where('status','Pending_for_shift_incharge')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();  

                   $gatepassss = DB::table('Clms_gatepass')
                              
                             ->where('shift_incharge_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();   

}elseif(Session::get('clm_role') =='hr_dept'){

$gatepasss = DB::table('Clms_gatepass')->where('status','Pending_for_hr')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();    


$gatepassss = DB::table('Clms_gatepass')
                              ->where('hr_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();  


}elseif(Session::get('clm_role') =='Safety_dept'){

$gatepasss = DB::table('Clms_gatepass')->where('status','Pending_for_safety')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();    

if(Session::get('user_sub_typeSession') == 3){
	$gatepassss = DB::table('Clms_gatepass')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get(); 
}else{
	$gatepassss = DB::table('Clms_gatepass')
                              ->where('safety_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get(); 
}
 


}elseif(Session::get('clm_role') =='plant_head'){

$gatepasss = DB::table('Clms_gatepass')->where('status','Pending_for_plant_head')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();    


$gatepassss = DB::table('Clms_gatepass')
                              
                              ->where('plant_head_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();  


}
elseif(Session::get('clm_role') =='security' || Session::get('vms_role') =='Security'){

$gatepasss = DB::table('Clms_gatepass')->where('status','Pending_for_security')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();    


$gatepassss = DB::table('Clms_gatepass')->where('status','Pending_for_security')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();  


}elseif(Session::get('user_sub_typeSession') == 3){
     $gatepasss = DB::table('Clms_gatepass')->orderBy('id', 'DESC')->get();
	
	 
                             
}


       return view('admin.gatepass_approvals.approve_clms_t',compact('gatepasss','UserLogin','workorder','gatepassss'));
        //return view('RequestVGatepass');
    }





public function report(Request $request){
         
  if(Session::get('user_sub_typeSession') == 3){
            $divisions = Division::get();

        }
        else{
            $users       = UserLogin::where('id',Session::get('user_idSession'))->first();
            $divisions   = Division::where('id',@$users->division_id)->get();
          }
        $report = "";
        if ($request->input('divi_id')<>''  || $request->input('dept_id')<>'' || $request->input('fromdate')<>'' && $request->input('todate')<>'')
        {   
            $start  = Carbon::parse($request->input('fromdate'))->startOfDay()->toDateTimeString();
            $end    = Carbon::parse($request->input('todate'))->endOfDay()->toDateTimeString();
            $report = DB::table('Clms_gatepass')->where('division',$request->input('divi_id'))
                       
             ->whereBetween('created_datetime',[$start,$end])->get();
            //$report = DB::table('Clms_gatepass')->whereBetween('created_datetime',[$start,$end])->get();
        }
        return view('admin.gatepass_approvals.clms_report',compact('report','divisions'));
        
        //return view('RequestVGatepass');
    }

    public function GetReport(Request $request){
        // show all the 

        if(Session::get('user_sub_typeSession') == 3){
            $divisions = Division::get();
        }
        else{
            $users       = UserLogin::where('id',Session::get('user_idSession'))->first();
            $divisions   = Division::where('id',@$users->division_id)->get();
        }
            if($request->input('divi_id')<>'' && $request->input('dept_id') == 'ALL' && $request->input('fromdate')<>'' && $request->input('todate')<>'')
        {    
            $start  = Carbon::parse($request->input('fromdate'))->startOfDay()->toDateTimeString();
            $end    = Carbon::parse($request->input('todate'))->endOfDay()->toDateTimeString();
            $report = DB::table('Clms_gatepass')->where('division',$request->input('divi_id'))->whereBetween('created_datetime',[$start,$end])->get();
            return view('admin.gatepass_approvals.clms_report',compact('report','divisions'));
        }
        elseif($request->input('divi_id')<>'' && $request->input('dept_id') != 'ALL' && $request->input('fromdate')<>'' && $request->input('todate')<>'')
        {    
            $start  = Carbon::parse($request->input('fromdate'))->startOfDay()->toDateTimeString();
            $end    = Carbon::parse($request->input('todate'))->endOfDay()->toDateTimeString();
            $report = DB::table('Clms_gatepass')->where('division',$request->input('divi_id'))->where('department',$request->input('dept_id'))->whereBetween('created_datetime',[$start,$end])->get();
            return view('admin.gatepass_approvals.clms_report',compact('report','divisions'));
        }
    
        elseif($request->input('fromdate')<>'' && $request->input('todate')<>'')
        {    
            $start  = Carbon::parse($request->input('fromdate'))->startOfDay()->toDateTimeString();
            $end    = Carbon::parse($request->input('todate'))->endOfDay()->toDateTimeString();
            $report = DB::table('Clms_gatepass')->whereBetween('created_datetime',[$start,$end])->get();
            return view('admin.gatepass_approvals.clms_report',compact('report','divisions'));
        }
    }

public function edit($vgpid)
    {
        $id = \Crypt::decrypt($vgpid);
        //Unique Record
        //$gatepass = DB::table('visitor_gate_pass');
        $gatepass  =DB::table('Clms_gatepass')->where('id',$id)->get();
        $divisions   = Division::all();
        $department   = Department::all();
        
        return view('admin.gatepass_approvals.edit_clms',compact('id'));
    }

    public function edit_new($vgpid)
    {
        $id = \Crypt::decrypt($vgpid);
        //Unique Record
        //$gatepass = DB::table('visitor_gate_pass');
        $gatepass  =DB::table('Clms_gatepass')->where('id',$id)->get();
        $divisions   = Division::all();
        $department   = Department::all();
        
        return view('admin.gatepass_approvals.edit_clms_new',compact('id'));
    }
    public function edit_renew($vgpid)
    {
        $id = \Crypt::decrypt($vgpid);
        //Unique Record
        //$gatepass = DB::table('visitor_gate_pass');
        $gatepass  =DB::table('Clms_gatepass')->where('id',$id)->get();
        $divisions   = Division::all();
        $department   = Department::all();
        if(Session::get('user_sub_typeSession') == '3'){
            $users       = UserLogin::where('user_type','1')->where('clm_role','Executing_agency')->where('active','Yes')->get();
          }else{
            $users       = UserLogin::where('user_type','1')->where('division_id',Session::get('user_DivID_Session'))->where('clm_role','Executing_agency')->where('active','Yes')->get();
          }
        return view('admin.gatepass_approvals.renew_clms',compact('id','users'));
    }
public function printg($pgid)
    {
        $id = \Crypt::decrypt($pgid);
        //Unique Record
        //$gatepass = DB::table('visitor_gate_pass');
       $gatepass  =DB::table('Clms_gatepass')->where('id',$id)->get();
        $divisions   = Division::all();
        $department   = Department::all();
        
        return view('admin.gatepass_approvals.printg_clms1',compact('id','gatepass','divisions','department'));
    }
   /* public function autocompleteSearch(Request $request)
    {
          $query = $request->get('query');
//DB::table('work_order')->where('vendor_code',@$approver->vendor_code)->get();
          $filterResult = DB::table('work_order')->where('order_code','LIKE', '%'. $query. '%')->get();
          return response()->json($filterResult);
    } */


public function autocomplete(Request $request)
    {
      

        $data = DB::table('work_order')->select('order_code','id')
                   ->where('order_code', 'LIKE', '%'. $request->get('search'). '%')
                   ->where('division_id',Session::get('user_DivID_Session'))
                    ->get();
    
        return response()->json($data);

     
    
      //echo json_encode($return_arr);
       //  $da=json_decode($return_arr);
      // return response()->json($da[0]->order_code);
    // return response()->json($da[0]->order_code);
    }

    public function autoworkorder($id){
      // $toReturn = array();
        $order_validity = DB::table('work_order')->select('order_validity')
                    ->where('order_code',$id)
                    ->where('division_id',Session::get('user_DivID_Session'))
                    ->get();
    
        $d=json_decode($order_validity);
      // return $d[0]->order_validity;
     return response()->json($d[0]->order_validity);
        //return $d[0]->order_validity;
 //return response()->json($order_validity);
      // return $order_validity->order_validity;
        //   echo json_encode(array('order_validity'=>$order_validity['order_validity']));        
         //echo json_encode(array('data'=>$order_validity['order_validity']));
    }

public function update(Request $request)
    {
        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        $date =  date('Y-m-d H:i:s');
    

    if(Session::get('clm_role') =='Shift_incharge'){

if($request->approver_decision=='approve'){
        $status='Pending_for_plant_head';
    }else{
        $status='Rejected';
    }

      
        
   
  // $full_gp='JAM'.'/'.$transdate1.'/'.$serial_no_gp;



        $gatepassv  =  DB::table('Clms_gatepass')->where('id',$request->id)->update([
                   
                      'shift_incharge_decision'           => $request->approver_decision,
                    'shift_incharge_remarks'              => $request->approver_remarks,
                    'status'              => $status,
                    'shift_incharge_datetime'=>$date,
                   'shift_incharge_by' =>Session::get('user_idSession')
                   

                ]);

 $gatepass = DB::table('Clms_gatepass')->where('id',$request->id)->first();


    $approver = UserLogin::where('id',$gatepass->created_by)->first();
    
    $work = DB::table('work_order')->where('id',$gatepass->work_order_no)->first();
   

      $getUserUpdateDetails = UserLogin::where('clm_role','plant_head')->first();
                $user = array('name'        => $getUserUpdateDetails->name,
                            'email'         => $getUserUpdateDetails->email,
                            'vendor_code'   => $getUserUpdateDetails->vendor_code,
                            'subject'       => "Pending For Approval [Notification]",
                            'id'            => $request->id,
                            'vendor'        => $approver->name,
                            'workorder'     => $gatepass->work_order_no,
                            'full_sl'       => $gatepass->full_sl
                    );

        }elseif(Session::get('clm_role') =='Executing_agency'){


if($request->approver_decision=='approve'){
        $status='Pending_for_hr';
    }else{
        $status='Rejected';
    }

          $gatepassv  =  DB::table('Clms_gatepass')->where('id',$request->id)->update([
                      'pending_excuting_decision'           => $request->approver_decision,
                    'pending_excuting_remarks'              => $request->approver_remarks,
                    'status'              => $status,
                    'pending_eccuting_date'=>$date,
                   'pending_excueting_by' =>Session::get('user_idSession'),
                   
                ]);

          $gatepass = DB::table('Clms_gatepass')->where('id',$request->id)->first();


    $approver = UserLogin::where('id',$gatepass->created_by)->first();
    
    $work = DB::table('work_order')->where('id',$gatepass->work_order_no)->first();


$getUserUpdateDetails = UserLogin::where('clm_role','hr_dept')->first();
                $user = array('name'        => $getUserUpdateDetails->name,
                            'email'         => $getUserUpdateDetails->email,
                            'vendor_code'   => $getUserUpdateDetails->vendor_code,
                            'subject'       => "Pending For Approval [Notification]",
                            'id'            => $request->id,
                            'vendor'        => $approver->name,
                            'workorder'     => $gatepass->work_order_no,
                            'full_sl'       => $gatepass->full_sl
                    );
        }elseif(Session::get('clm_role') =='hr_dept'){


if($request->approver_decision=='approve'){
        $status='Pending_for_safety';
    }else{
        $status='Rejected';
    }

          $gatepassv  =  DB::table('Clms_gatepass')->where('id',$request->id)->update([
                      'hr_decision'           => $request->approver_decision,
                    'hr_remarks'              => $request->approver_remarks,
                    'status'              => $status,
                    'hr_datetime'=>$date,
                   'hr_by' =>Session::get('user_idSession'),
                   
                ]);

          $gatepass = DB::table('Clms_gatepass')->where('id',$request->id)->first();


    $approver = UserLogin::where('id',$gatepass->created_by)->first();
    
    $work = DB::table('work_order')->where('id',$gatepass->work_order_no)->first();


$getUserUpdateDetails = UserLogin::where('clm_role','Safety_dept')->first();
                $user = array('name'        => $getUserUpdateDetails->name,
                            'email'         => $getUserUpdateDetails->email,
                            'vendor_code'   => $getUserUpdateDetails->vendor_code,
                            'subject'       => "Pending For Approval [Notification]",
                            'id'            => $request->id,
                            'vendor'        => $approver->name,
                            'workorder'     => $gatepass->work_order_no,
                            'full_sl'       => $gatepass->full_sl
                    );
        }
        elseif(Session::get('clm_role') =='Safety_dept' && $request->training_date!=''){


if($request->approver_decision=='approve'){
        $status='Pending_for_safety';
    }else{
        $status='Rejected';
    }


          $gatepassv  =  DB::table('Clms_gatepass')->where('id',$request->id)->update([
                   
                      'safety_decision'           => $request->approver_decision,
                    'safety_training_date'              => $request->training_date,
                    'status'              => $status,
                    'safety_training_time'=>$request->training_time,
                   'safety_training_by' =>Session::get('user_idSession'),
				   'safety_datetime2'  => $date,
                   
                ]);
$gatepass = DB::table('Clms_gatepass')->where('id',$request->id)->first();


    $approver = UserLogin::where('id',$gatepass->created_by)->first();
    
    $work = DB::table('work_order')->where('id',$gatepass->work_order_no)->first();
   
  
$getUserUpdateDetails = UserLogin::where('clm_role','Safety_dept')->first();
                $user = array('name'        => $getUserUpdateDetails->name,
                            'email'         => $getUserUpdateDetails->email,
                            'vendor_code'   => $getUserUpdateDetails->vendor_code,
                            'subject'       => "Pending For Approval [Notification]",
                            'id'            => $request->id,
                            'vendor'        => $approver->name,
                            'workorder'     => $gatepass->work_order_no,
                            'full_sl'       => $gatepass->full_sl
                    );

        }
         elseif(Session::get('clm_role') =='Safety_dept'){


if($request->approver_decision=='approve'){
     //  $status='Pending_for_shift_incharge';
         $status='Pending_for_plant_head';
    }else{
        $status='Rejected';
    }

$transdate = date('Y-m-d');
       
        $month = date('m', strtotime($transdate));
        $year  = date('Y', strtotime($transdate));
  

      //  $users = DB::table('users')->get();
  
$transdate1 = date('mY');

 $divv = DB::table('Clms_gatepass')
 ->whereYear('safety_datetime', '=', $year)
                ->whereMonth('safety_datetime', '=', $month)
                ->orderBy('id', 'DESC')->first();

//echo $divv->safety_no;
//exit;
    //  {{ $divv->safety_no; }}

        if ($divv)
        {
            $v=$divv->safety_no;
            $v++;
            $serial_no_gp=$v;
        }
        else{
            $serial_no_gp="1"; 
        }

        $division = DB::table('userlogins')->where('id',Session::get('user_idSession'))
                         ->orderBy('id', 'DESC')->first();

        $DIv=$division->division_id;


         $divv1 = Division::where('id',$DIv)
                ->orderBy('id', 'DESC')->first();
        if ($divv1)
        {
            $v=$divv1->abbreviation;
           
            $abb=$v;
        }
   
   $full_gp='JAM'.'/'.$abb.'/'.$transdate1.'/'.$serial_no_gp;

          $gatepassv  =  DB::table('Clms_gatepass')->where('id',$request->id)->update([
                   
                      'safety_decision'           => $request->approver_decision,
                    'safety_remarks'              => $request->approver_remarks,
                    'status'              => $status,
                    'safety_datetime'=>$date,
                   'safety_by' =>Session::get('user_idSession'),
                   'safety_pass_no' => $full_gp,
			       'safety_no'      => $serial_no_gp
                   
                ]);
$gatepass = DB::table('Clms_gatepass')->where('id',$request->id)->first();


    $approver = UserLogin::where('id',$gatepass->created_by)->first();
    
    $work = DB::table('work_order')->where('id',$gatepass->work_order_no)->first();
   
  
$getUserUpdateDetails = UserLogin::where('clm_role','plant_head')->first();
                $user = array('name'        => $getUserUpdateDetails->name,
                            'email'         => $getUserUpdateDetails->email,
                            'vendor_code'   => $getUserUpdateDetails->vendor_code,
                            'subject'       => "Pending For Approval [Notification]",
                            'id'            => $request->id,
                            'vendor'        => $approver->name,
                            'workorder'     => $gatepass->work_order_no,
                            'full_sl'       => $gatepass->full_sl
                    );

        }elseif(Session::get('clm_role') =='plant_head'){


if($request->approver_decision=='approve'){
        $status='Pending_for_security';
    }else{
        $status='Rejected';
    }


          $gatepassv  =  DB::table('Clms_gatepass')->where('id',$request->id)->update([
                   
                      'plant_head_decision'           => $request->approver_decision,
                    'plant_head_remarks'              => $request->approver_remarks,
                    'status'              => $status,
                    'plant_head_datetime'=>$date,
                   'plant_head_by' =>Session::get('user_idSession'),
                   
                ]);
          $gatepass = DB::table('Clms_gatepass')->where('id',$request->id)->first();


    $approver = UserLogin::where('id',$gatepass->created_by)->first();
    
    $work = DB::table('work_order')->where('id',$gatepass->work_order_no)->first();

$getUserUpdateDetails = UserLogin::where('clm_role','security')->orwhere('vms_roll','Security')->first();
                $user = array('name'        => $getUserUpdateDetails->name,
                            'email'         => $getUserUpdateDetails->email,
                            'vendor_code'   => $getUserUpdateDetails->vendor_code,
                            'subject'       => "Pending For Approval [Notification]",
                            'id'            => $request->id,
                            'vendor'        => $approver->name,
                             'workorder'     => $gatepass->work_order_no,
                            'full_sl'       => $gatepass->full_sl
                    );
        }
        
              
        if($gatepassv){

                Mail::send('admin.gatepass_approvals.send_pwd',['data' => $user

            ],function($message) use ($user){
                        $message->to($user['email'])
                                ->subject($user['subject']);
                        $message->from('web@jamipol.com');
                    });
                    return redirect()->action([GatePassCLMSController::class, 'index1']);
        //return redirect()->route('approve_clms')->with('message', 'Update Successfully!');
            //return back()->with('message','  Update Successfully!');
        }else{
            return back()->with('message','Error While Approve');

        }
    }

	public function store(Request $request)
    {
		date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        //dd($request->all());
		$date =  date('Y-m-d H:i:s');
      $transdate = date('Y-m-d');
       $transdate1 = date('m-d');
        $month = date('m', strtotime($transdate));
        $year  = date('Y', strtotime($transdate));
  
$request->validate([
              'work_order_validity'     => 'required|after_or_equal:'. date('Y-m-d'),
              'dob' => 'required|date|before:'.now()->subYears(18)->toDateString(),
             // 'police_verification_date' => 'required|date|after:'.now()->subYears(3)->toDateString(),
              'medical_exam_date' => 'required|date|after:'.now()->subDays(180)->toDateString(),
			  'upload_photo' => 'required|file|mimes:jpeg,jpg,png',
        ],[
            'work_order_validity'.'after_or_equal' => 'Gate Pass Expired',
            'dob.before' => trans('Age should not be less than 18 years'),
           //  'police_verification_date.after' => trans('Expired Police Verification'),
             'medical_exam_date.after' => trans('Expired Medical Fitness Validity'),
        ]);
  //if($request->education == 'Matric' || $request->education == 'Diploma' || $request->education == 'Intermediate' || $request->education == 'Graduate'){
	//  $request->validate([
        //    'upload_result' => 'required|file|mimes:pdf'
       // ]);
  //}         
		   
if($request->valid_passport == 'Yes'){
	 $request->validate([
            'passport_copy' => 'required|file|mimes:jpeg,jpg,png'
        ]);
}elseif($request->valid_passport == 'No'){
	$request->validate([
            'police_verification_copy' => 'required|file|mimes:pdf'
        ]);
	
	
}
/*$validator = Validator::make($request->all(), [
                
                'birthdate' => 'required|date|before:'.now()->subYears(18)->toDateString(),
                
            ], [
                'birthdate.before' => trans('18 year validation'),                
            ]);*/

      //  $users = DB::table('users')->get();
//echo $request->upload_fitness;
//exit;

/*if($request->passport_no !='' &&  $request->police_verification_date !=''){
 $request->validate([
                'police_verification_date' => 'required',
                'police_verification_copy' => 'required',
                ],[
                'police_verification_date.'.'required'   => 'Police Verification Is Required',
                'police_verification_copy.'.'required'   => 'Police Verification Copy Is Required',
              ]);


}*/
//$request->passport_no,

         $division = DB::table('userlogins')->where('id',Session::get('user_idSession'))
                         ->orderBy('id', 'DESC')->first();

        $DIv=$division->division_id;


        $divv = DB::table('Clms_gatepass')->where('division',$DIv)
                ->whereYear('created_datetime', '=', $year)
                ->whereMonth('created_datetime', '=', $month)
                ->orderBy('id', 'DESC')->first();
        if ($divv)
        {
            $v=$divv->sl;
            $v++;
            $serial_no=$v;
        }
        else{
            $serial_no="1"; 
        }
        
         $divv = Division::where('id',$DIv)
                ->orderBy('id', 'DESC')->first();
        if ($divv)
        {
            $v=$divv->abbreviation;
           
            $abb=$v;
        }
   
   $full_sl='CLGP'.'/'.$abb.'/'.$transdate1.'/'.$serial_no;

 if($request->hasFile('upload_unique_id')){
	 
	 $request->validate([
        'upload_unique_id' => 'mimes:png,jpeg,jpg,pdf,doc,docx,ppt,pptx', // max file size in kilobytes (optional)
    ]);
            $location = 'documents/clm_pics/';
            $extension = '.'.$request->upload_unique_id->getClientOriginalExtension();
            $name = basename($request->upload_unique_id->getClientOriginalName(),$extension).time();
            //$name = $name.$extension;
			$uid = Session::get('user_idSession');
			$datetime = date('Ymd_H_i_s');
			$randn = rand(0000,9999);
			$name = $uid.'_'.$datetime.'_'.$randn.$extension;
            $path = $request->upload_unique_id->move($location,$name);
            $name = $name;
           // $old_name = Permit::where('id',$id)->get();
            //if(file_exists($old_name[0]->upload_unique_id)){
               // unlink($old_name[0]->upload_unique_id);
           // }
        }else{
            $name=$request->upload_unique_id;
        }
if($request->hasFile('upload_unique_id_back')){
	
	$request->validate([
        'upload_unique_id_back' => 'mimes:png,jpeg,jpg,pdf,doc,docx,ppt,pptx', // max file size in kilobytes (optional)
    ]);
            $location8 = 'documents/clm_pics/';
            $extension8 = '.'.$request->upload_unique_id_back->getClientOriginalExtension();
            $name8 = basename($request->upload_unique_id_back->getClientOriginalName(),$extension8).time();
            //$name8 = $name8.$extension8;
			$uid8 = Session::get('user_idSession');
			$datetime8 = date('Ymd_H_i_s');
			$randn8 = rand(0000,9999);
			$name8 = $uid8.'_'.$datetime8.'_'.$randn8.$extension8;
            $path8 = $request->upload_unique_id_back->move($location8,$name8);
            $name8 = $name8;
           // $old_name = Permit::where('id',$id)->get();
            //if(file_exists($old_name[0]->upload_unique_id)){
               // unlink($old_name[0]->upload_unique_id);
           // }
        }else{
            $name8=$request->upload_unique_id_back;
        }


if($request->hasFile('uan_copy')){
	$request->validate([
        'uan_copy' => 'mimes:png,jpeg,jpg,pdf,doc,docx,ppt,pptx', // max file size in kilobytes (optional)
    ]);
            $location1 = 'documents/clm_pics/';
            $extension1 = '.'.$request->uan_copy->getClientOriginalExtension();
            $name1 = basename($request->uan_copy->getClientOriginalName(),$extension1).time();
            //$name1 = $name1.$extension1;
			$uid1 = Session::get('user_idSession');
			$datetime1 = date('Ymd_H_i_s');
			$randn1 = rand(0000,9999);
			$name1 = $uid1.'_'.$datetime1.'_'.$randn1.$extension1;
            $path1 = $request->uan_copy->move($location1,$name1);
            $name1 = $name1;
           // $old_name = Permit::where('id',$id)->get();
            //if(file_exists($old_name[0]->upload_unique_id)){
               // unlink($old_name[0]->upload_unique_id);
           // }
        }else{
            $name1=$request->uan_copy;
        }
        if($request->hasFile('upload_ins')){
			$request->validate([
        'upload_ins' => 'mimes:png,jpeg,jpg,pdf,doc,docx,ppt,pptx', // max file size in kilobytes (optional)
    ]);
            $location2 = 'documents/clm_pics/';
            $extension2 = '.'.$request->upload_ins->getClientOriginalExtension();
            $name2 = basename($request->upload_ins->getClientOriginalName(),$extension2).time();
            //$name2 = $name2.$extension2;
			$uid2 = Session::get('user_idSession');
			$datetime2 = date('Ymd_H_i_s');
			$randn2 = rand(0000,9999);
			$name2 = $uid2.'_'.$datetime2.'_'.$randn2.$extension2;
			
            $path2 = $request->upload_ins->move($location2,$name2);
            $name2 = $name2;
           // $old_name = Permit::where('id',$id)->get();
            //if(file_exists($old_name[0]->upload_unique_id)){
               // unlink($old_name[0]->upload_unique_id);
           // }
        }else{
            $name2='';
        }
if($request->hasFile('upload_fitness')){
	$request->validate([
        'upload_fitness' => 'mimes:png,jpeg,jpg,pdf,doc,docx,ppt,pptx', // max file size in kilobytes (optional)
    ]);
            $location3 = 'documents/clm_pics/';
            $extension3 = '.'.$request->upload_fitness->getClientOriginalExtension();
            $name3 = basename($request->upload_fitness->getClientOriginalName(),$extension3).time();
            //$name3 = $name3.$extension3;
			$uid3 = Session::get('user_idSession');
			$datetime3 = date('Ymd_H_i_s');
			$randn3 = rand(0000,9999);
			$name3 = $uid3.'_'.$datetime3.'_'.$randn3.$extension3;
            $path3 = $request->upload_fitness->move($location3,$name3);
            $name3 = $name3;
           // $old_name = Permit::where('id',$id)->get();
            //if(file_exists($old_name[0]->upload_unique_id)){
               // unlink($old_name[0]->upload_unique_id);
           // }
        }else{
            $name3=$request->upload_fitness1;
        }
		

        if($request->hasFile('upload_photo')){
			
			$request->validate([
        'upload_photo' => 'mimes:png,jpeg,jpg,pdf,doc,docx,ppt,pptx', // max file size in kilobytes (optional)
    ]);
            $location4 = 'documents/clm_pics/';
            $extension4 = '.'.$request->upload_photo->getClientOriginalExtension();
            $name4 = basename($request->upload_photo->getClientOriginalName(),$extension4).time();
            //$name4 = $name4.$extension4;
			$uid4 = Session::get('user_idSession');
			$datetime4 = date('Ymd_H_i_s');
			$randn4 = rand(0000,9999);
			$name4 = $uid4.'_'.$datetime4.'_'.$randn4.$extension4;
            $path4 = $request->upload_photo->move($location4,$name4);
            $name4 = $name4;
           // $old_name = Permit::where('id',$id)->get();
            //if(file_exists($old_name[0]->upload_unique_id)){
               // unlink($old_name[0]->upload_unique_id);
           // }
        }else{
			$name4=$request->upload_photo;
		}
 if($request->hasFile('esic_document')){
	 $request->validate([
        'esic_document' => 'mimes:png,jpeg,jpg,pdf,doc,docx,ppt,pptx', // max file size in kilobytes (optional)
    ]);
            $location5 = 'documents/clm_pics/';
            $extension5 = '.'.$request->esic_document->getClientOriginalExtension();
            $name5 = basename($request->esic_document->getClientOriginalName(),$extension5).time();
            //$name5 = $name5.$extension5;
			$uid5 = Session::get('user_idSession');
			$datetime5 = date('Ymd_H_i_s');
			$randn5 = rand(0000,9999);
			$name5 = $uid5.'_'.$datetime5.'_'.$randn5.$extension5;
            $path5 = $request->esic_document->move($location5,$name5);
            $name5 = $name5;
           // $old_name = Permit::where('id',$id)->get();
            //if(file_exists($old_name[0]->upload_unique_id)){
               // unlink($old_name[0]->upload_unique_id);
           // }
        }else{
            $name5=$request->esic_document;
        }
if($request->hasFile('upload_result')){
	$request->validate([
        'upload_result' => 'mimes:png,jpeg,jpg,pdf,doc,docx,ppt,pptx', // max file size in kilobytes (optional)
    ]);
            $location6 = 'documents/clm_pics/';
            $extension6 = '.'.$request->upload_result->getClientOriginalExtension();
            $name6 = basename($request->upload_result->getClientOriginalName(),$extension6).time();
            //$name6 = $name6.$extension6;
			$uid6 = Session::get('user_idSession');
			$datetime6 = date('Ymd_H_i_s');
			$randn6 = rand(0000,9999);
			$name6 = $uid6.'_'.$datetime6.'_'.$randn6.$extension6;
            $path6 = $request->upload_result->move($location6,$name6);
            $name6 = $name6;
           // $old_name = Permit::where('id',$id)->get();
            //if(file_exists($old_name[0]->upload_unique_id)){
               // unlink($old_name[0]->upload_unique_id);
           // }
        }else{
            $name6=$request->upload_result;
        }
if($request->hasFile('police_verification_copy')){
	$request->validate([
        'police_verification_copy' => 'mimes:png,jpeg,jpg,pdf,doc,docx,ppt,pptx', // max file size in kilobytes (optional)
    ]);
            $location7 = 'documents/clm_pics/';
            $extension7 = '.'.$request->police_verification_copy->getClientOriginalExtension();
            $name7 = basename($request->police_verification_copy->getClientOriginalName(),$extension7).time();
            //$name7 = $name7.$extension7;
			$uid7 = Session::get('user_idSession');
			$datetime7 = date('Ymd_H_i_s');
			$randn7 = rand(0000,9999);
			$name7 = $uid7.'_'.$datetime7.'_'.$randn7.$extension7;
            $path7 = $request->police_verification_copy->move($location7,$name7);
            $name7 = $name7;
           // $old_name = Permit::where('id',$id)->get();
            //if(file_exists($old_name[0]->upload_unique_id)){
               // unlink($old_name[0]->upload_unique_id);
           // }
        }else{
            $name7=$request->police_verification_copy;
        }


if($request->hasFile('passport_copy')){
	$request->validate([
        'passport_copy' => 'mimes:png,jpeg,jpg,pdf,doc,docx,ppt,pptx', // max file size in kilobytes (optional)
    ]);
            $location9 = 'documents/clm_pics/';
            $extension9 = '.'.$request->passport_copy->getClientOriginalExtension();
            $name9 = basename($request->passport_copy->getClientOriginalName(),$extension9).time();
            //$name9 = $name9.$extension9;
			$uid9 = Session::get('user_idSession');
			$datetime9 = date('Ymd_H_i_s');
			$randn9 = rand(0000,9999);
			$name9 = $uid9.'_'.$datetime9.'_'.$randn9.$extension9;
            $path9 = $request->passport_copy->move($location9,$name9);
            $name9 = $name9;
           // $old_name = Permit::where('id',$id)->get();
            //if(file_exists($old_name[0]->upload_unique_id)){
               // unlink($old_name[0]->upload_unique_id);
           // }
        }else{
            $name9=$request->passport_copy;
        }


if($request->identity_proof=="Aadhar"){
$uniqid=$request->unique_id_no;
}else{
   $uniqid=$request->unique_id_no1; 
}

$medical_exam_date = date("Y-m-d", strtotime($request->medical_exam_date ."+6 month"));
$police_verification_date = date('Y-m-d', strtotime($request->police_verification_date . '+3 years'));


if($medical_exam_date < $police_verification_date && $medical_exam_date < $request->work_order_validity){
	
     $till_date=$medical_exam_date;
	 
}else if($medical_exam_date > $police_verification_date && $request->work_order_validity > $police_verification_date){

 $till_date=$police_verification_date;
 }else if($medical_exam_date > $police_verification_date && $request->work_order_validity < $police_verification_date){

 $till_date=$request->work_order_validity;
 }else{

     $till_date=$request->work_order_validity;
 }
/*if($request->medical_exam_date < $request->police_verification_date && $request->medical_exam_date < $request->work_order_validity){
     $till_date=$request->medical_exam_date;
}else if($request->medical_exam_date > $request->police_verification_date && $request->work_order_validity > $request->police_verification_date){
 $till_date=$request->police_verification_date;
 }else{
     $till_date=$request->work_order_validity;
 }*/


    //$due = '180';
   // $till_date=$request->medical_exam_date;
    
   // $till = date('Y-m-d', strtotime($till_date . ' + ' . $due . ' days'));
if($request->gp_status){
    $gp_status1 = 'Renew';
	
}else{
    $gp_status1 = 'New';
	/*$request->validate([
			  'upload_unique_id' => 'required|file|mimes:pdf',
			  'upload_unique_id_back' => 'required|file|mimes:pdf',
			  'esic_document' => 'required|file|mimes:pdf',
			  'uan_copy' => 'required|file|mimes:pdf',
			  'upload_fitness' => 'required|file|mimes:pdf'
        ]);*/
}

        $clms=  DB::table('Clms_gatepass')->insert([
            'sl' => $serial_no,
            'full_sl'   =>$full_sl,
            'work_order_no'=>$request->work_order,
            'work_order_validity'=>$request->work_order_validity,
            'name' => $request->name,
			'son_of' => $request->son_of,
			'gender' =>$request->gender,
			'date_of_birth' => $request->dob,
			'any_diseace' =>$request->any_disease,
            'uan_no' =>$request->uan_no,
            'upload_pf_copy' =>$name1,
			'blood_group' =>$request->blood_group,
            'skill_type' =>$request->skilled_type,
            'address' =>$request->address,
            'mobile_no' =>$request->mobile_no,
            'job_role' =>$request->job_role,
            'identity_proof' =>$request->identity_proof,
            'unique_id_no' =>$uniqid,
            'upload_id_proof' =>$name,
            'upload_id_proof_back'=>$name8,
            'id_mark' =>$request->id_mark,
            'esic' =>$request->esic_no,
            'esic_document'=>$name5,
            'insurance_valid_from' =>$request->ins_valid_from,
            'insurance_valid_to' =>$request->ins_valid_to,
            'upload_insurance' =>$name2,
            'medical_examination_date' =>$request->medical_exam_date,
            'upload_fittenss_copy' =>$name3,
            'police_verification_date'=>$request->police_verification_date,
            'police_verification_copy'=>$name7,
            'passport_no' =>$request->passport_no,
            'passport_validity' =>$request->passport_validity,
            'passport_copy'=>$name9,
            'upload_photo' =>$name4,
            'education' =>$request->education,
            'board_name'=>$request->board_name,
            'upload_result'=>$name6,
            'experience' =>$request->experience,
            'created_by'=> Session::get('user_idSession'),
            'created_datetime' =>$date,
            'division' =>$DIv,
            'status' =>'Pending_executing',
            'valid_to'=>$date,
            'valid_till'=>$till_date,
            'gp_status' => $gp_status1,
            'pending_excueting_by' =>$request->excuting_agency
			]);
		
$gatepass = DB::table('Clms_gatepass')->where('created_by',Session::get('user_idSession'))->first();


    $approver = UserLogin::where('id',$gatepass->created_by)->first();
    
    $work = DB::table('work_order')->where('order_code',$request->work_order)->first();
     $excuting_id = $request->excuting_agency;
        $getUserUpdateDetails = UserLogin::where('clm_role','Executing_agency')->where('id',$excuting_id) ->first();
                $user = array('name'        => $getUserUpdateDetails->name,
                            'email'         => $getUserUpdateDetails->email,
                            'vendor_code'   => $getUserUpdateDetails->vendor_code,
                            'subject'       => "Pending For Approval [Notification]",
                            'id'            => $request->id,
                            'vendor'        => $approver->name,
                            'workorder'     => $work->order_code,
                            'full_sl'       => $gatepass->full_sl
                    );

        if($clms){

  Mail::send('admin.gatepass_approvals.send_pwd',['data' => $user

            ],function($message) use ($user){
                        $message->to($user['email'])
                                ->subject($user['subject']);
                        $message->from('web@jamipol.com');
                    });

            return back()->with('message','Gatepass Requested Successfully!');
        }else{
            return back()->with('message','Error!');

        }
		
    	

}

}
/*public function index1(){
	     // $divisions = Division::all();
 //$gatepasss = DB::table('visitor_gate_pass')->orderBy('id','desc')->get();
       // return view('admin.gatepass_approvals.approve',compact('gatepasss'));
		
		$gatepasss = DB::table('visitor_gate_pass')->orwhere('approver',Session::get('user_idSession'))
		                        ->where('status','Pending_to_approve')
                              ->orderBy('id', 'DESC')->get();
							  
		$gatepassss = DB::table('visitor_gate_pass')->where('approver',Session::get('user_idSession'))
                              ->where('status','issued')
							  ->orwhere('status','Rejected')
							  ->where('approver',Session::get('user_idSession'))
							   ->orwhere('status','Completed')
							  ->where('approver',Session::get('user_idSession'))
							  ->orderBy('id', 'DESC')->get();	
		$gatepasss_sec = DB::table('visitor_gate_pass')->where('status','issued')
                              ->orderBy('id', 'DESC')->get();	

        $gatepasss_sec_com = DB::table('visitor_gate_pass')->where('status','Completed')
		                     ->where('security_print_id',Session::get('user_idSession'))
							 ->orwhere('status','Rejected')
							  ->where('security_print_id',Session::get('user_idSession'))
                              ->orderBy('id', 'DESC')->get();	

							  
							  return view('admin.gatepass_approvals.approve',compact('gatepasss','gatepassss','gatepasss_sec','gatepasss_sec_com'));
        //return view('RequestVGatepass');
    }	
	 public function getDepartment($id){
        $depart = Department::where('division_id',$id)->get();
        return $depart;
    }
	public function getapprover($id){
        $approver = UserLogin::where('department_id',$id)->get();
        return $approver;
    }
	
	
	//public function create()
 //   {
      //  return view('admin.visitor_gate_pass.create');
   // }
	
	public function store(Request $request)
    {
		$date =  date('Y-m-d H:i:s');
        // dd( $request->all());
        //$request->validate([
         //   'name'         => 'required',
         //   'abbreviation' => 'required'
       // ]);
	   
	   
	   $transdate = date('Y-m-d');
	   $transdate1 = date('m-d');
        $month = date('m', strtotime($transdate));
        $year  = date('Y', strtotime($transdate));
		
      //  $users = DB::table('users')->get();
		
		
        $divv = DB::table('visitor_gate_pass')->where('division_id',$request->division_id)
                ->whereYear('created_datetime', '=', $year)
                ->whereMonth('created_datetime', '=', $month)
                ->orderBy('id', 'DESC')->first();
        if ($divv)
        {
            $v=$divv->sl;
            $v++;
            $serial_no=$v;
        }
        else{
            $serial_no="1"; 
        }
		
		 $divv = Division::where('id',$request->division_id)
                ->orderBy('id', 'DESC')->first();
        if ($divv)
        {
            $v=$divv->abbreviation;
           
            $abb=$v;
        }
   
   $fill_sl='VGP'.'/'.$abb.'/'.$transdate1.'/'.$serial_no;
	   
        $visitor_gatepass = DB::table('visitor_gate_pass')->insert([
        //$division = Division::create([
		    'sl'       =>$serial_no,
		    'full_sl'       =>$fill_sl,
            'visitor_mobile_no'=> $request->visitor_mobile,
            'visitor_name' => $request->visitor_name,
            'visitor_company' => $request->visitor_company,
            'visitor_email' => $request->visitor_email,
            'visitor_emergency_contact_no' => $request->visitor_emergency_contact_no,
            'division_id' => $request->division_id,
            'department' => $request->department_id,
            'approver' => $request->approver_id,
            'to_meet' => $request->to_meet,
            'from_date' => $request->from_date,
            'from_time' => $request->from_time,
            'to_time' => $request->to_time,
            'any_material' => $request->any_material,
            'material_name' => $request->material_name,
            'material_identification_no' => $request->material_idenrification_no,
            'returnable' => $request->returnable,
            'propose_of_entry' => $request->purpose_of_material_entry,
            'visitor_any_vehicle' => $request->any_vehicle,
            'driving_mode' => $request->driving_mode,
            'driver_name' => $request->driver_name,
            'vehicle_no' => $request->vehicle_no,
            'dl_no' => $request->dl_no,
            'status' => 'Pending_to_approve',
			'created_datetime'=>$date
            
        ]);
      
        if($visitor_gatepass){
            return back()->with('message','Visitor Gatepass Requested Successfully! VGP No :-'.$fill_sl);
        }else{
            return back()->with('message','Error!');

        }
    } 
	public function edit($vgpid)
    {
        $id = \Crypt::decrypt($vgpid);
        //Unique Record
		//$gatepass = DB::table('visitor_gate_pass');
        $gatepass  =DB::table('visitor_gate_pass')->where('id',$id)->get();
        $divisions   = Division::all();
        $department   = Department::all();
		
        return view('admin.gatepass_approvals.edit',compact('id','gatepass','divisions','department'));
    }
	
	public function printg($pgid)
    {
        $id = \Crypt::decrypt($pgid);
        //Unique Record
		//$gatepass = DB::table('visitor_gate_pass');
       $gatepass  =DB::table('visitor_gate_pass')->where('id',$id)->get();
        $divisions   = Division::all();
        $department   = Department::all();
		
        return view('admin.gatepass_approvals.printg',compact('id','gatepass','divisions','department'));
    }
	
	 public function update(Request $request)
    {
		$date =  date('Y-m-d H:i:s');
    if($request->approver_decision=='approve'){
		$status='issued';
	}else{
		$status='Rejected';
	}
        $gatepassv  =  DB::table('visitor_gate_pass')->where('id',$request->id)->update([
                   
                      'approver_decision'           => $request->approver_decision,
                    'approver_remarks'              => $request->approver_remarks,
                    'status'              => $status,
					'approver_datetime'=>$date
                   
				   
                ]);
        
              
        if($gatepassv){
            return back()->with('message',' Approved Successfully');
        }else{
            return back()->with('message','Error While Approve');

        }
    }
	public function update_security(Request $request)
    {
		$date =  date('Y-m-d H:i:s');
    
        $gatepassv =  DB::table('visitor_gate_pass')->where('id',$request->ida)->update([
                   
                   'security_print_id'            =>Session::get('user_idSession'),
                    'security_print_remarks'       =>$request->security_remarks,
					'security_print_datetime'      =>$date,
					'status'                       =>'Completed'
                  ]);
          
        if($gatepassv){
            return back()->with('message',' Returned Successfully');
        }else{
            return back()->with('message','Error While Returned');

        }
    }
	public function RequestVGatepassPost(Request $request){
//echo $request->visitor_mobile;
//echo $request->name;
//exit;
        return view('RequestVGatepass');
    } 
	//public function RequestVGatepassPostup(Request $request){
//echo $request->approval_remarks;
//echo $request->name;
//exit;
        //return view('admin.gatepass_approvals.edit');
    //} 
	//public function approvePost(Request $request){
//echo $request->visitor_mobile;
//echo $request->name;
//exit;
       // return view('approve');
    //}
}*/
