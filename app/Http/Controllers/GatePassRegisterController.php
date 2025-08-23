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
class GatePassRegisterController extends Controller
{

    
public function index(){
	      $divisions = Division::all();

        return view('RegisterGatepass',compact('divisions'));
	}

	public function store(Request $request)
    {
		$date =  date('Y-m-d H:i:s');
        $transdate = date('Y-m-d');
	    $transdate1 = date('m-d');
        $month = date('m', strtotime($transdate));
        $year  = date('Y', strtotime($transdate));
		$request->validate([ 
            'g-recaptcha-response' => 
            ['required']]);
		if($request->enter_otp == Session::get('otp')) {	
		$seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789!@#$%^&*()'); // and any other characters
                shuffle($seed); // probably optional since array_is randomized; this may be redundant
                $rand = '';
                foreach (array_rand($seed, 10) as $k) $rand .= $seed[$k];
                $password = $rand;
		
        $Register =  UserLogin::create([
            'name' =>$request->name,
            'vendor_code' => $request->email,
			'email' => $request->email,
			'wps' =>'No',
			'vms' => 'Yes',
			'vms_roll' => 'Requester',
			'user_type' =>'2',
			'user_sub_type' => '2',
			'password' => md5($password)
			]);
			
			 $user = array('email'  => $request->email,
                            'name'     => $request->name,
                            'vendor_code'=> $request->email,
                            'password'  => $password,
                            'subject'   => "JAMIPOL App Password");
						   
			Mail::send('send_pwd',['data' => $user],function($message) use ($user){
                        $message->to($user['email'])
                                ->subject($user['subject']);
                        $message->from('web@jamipol.com');
                    });
			 Session::flush();
            return back()->with('message','Register Successfully! Password :-'.$password);
		}else{
			 Session::flush();
			 return back()->with('message2','Something Went Wrong!!!');
			
             return redirect('RegisterGatepass');
		}
		
	
}

 //for validate email and otp
public function check_otp2(Request $request)
    {
		$date =  date('Y-m-d H:i:s');
        $transdate = date('Y-m-d');
	    $transdate1 = date('m-d');
        $month = date('m', strtotime($transdate));
        $year  = date('Y', strtotime($transdate));
		$request->validate([ 
            'g-recaptcha-response' => 
            ['required']]);
			
		$seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789!@#$%^&*()'); // and any other characters
                shuffle($seed); // probably optional since array_is randomized; this may be redundant
                $rand = '';
                foreach (array_rand($seed, 10) as $k) $rand .= $seed[$k];
                $password = $rand;
		
		$getUserDetails  = UserLogin::where('email',$request->email)->first();
		if(!$getUserDetails){
			$otp = rand(0000,9999);
			$user = array('email'  => $request->email,
			               'name'     => $request->name,
						   'otp'     => $otp,
                           'subject'   => "OTP For Sign Up (JAMIPOL)");
							
			Mail::send('send_otp_register',['data' => $user],function($message) use ($user){
                        $message->to($user['email'])
                                ->subject($user['subject']);
                        $message->from('web@jamipol.com');
                    });
					 $session_data =   array(
                        'name'                => $request->name,
						'email'               => $request->email,
						'otp'                  => $otp
                    );
                    Session::put($session_data);
			return back()->with('message','OTP Send in Your Email Id !!!');
		}else{
			 return back()->with('message2','Your Email Id Already Register !!!');
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
