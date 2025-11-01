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

class LoginController extends Controller
{

    public function index(){
        return view('loginPage');
    } 


    //Show the Login Page
    public function LoginPage()
    {
        return view('loginPage');
    }
 

    //login the user
    public function LoginPost(Request $request){
       
        $request->validate([
            'vendor_code'  =>   'required',
            'password'     =>   'required|min:5|max:50',
			'g-recaptcha-response' =>'required',
        ]);
    
        $vendor_code     =  $request->vendor_code;
        $password        =  $request->password;
        $password        =  md5($password);
        
        //if ($request->vercode != Session::get('capchaSession') OR Session::get('capchaSession')=='')  {
        //    return back()->with('message','Oooops... Captcha Incorrect.');
        //}else{
            $getUserDetails  = UserLogin::where('vendor_code',$vendor_code)->where('password',$password)->first();
			$remember        =  $request->remember_me == "on";
           // return $getUserDetails;
            if($getUserDetails){
                if($getUserDetails){
					$otp = rand(0000,9999);

                          $user = array('email'  => $getUserDetails->email,
                            'name'     => $getUserDetails->name,
                            'vendor_code'=> $getUserDetails->vendor_code,
                            'otp'  => $otp,
                            'subject'   => "OTP For Jamipol Suraksha");
					
					
					
					Mail::send('send_otp',['data' => $user],function($message) use ($user){
                        $message->to($user['email'])
                                ->subject($user['subject']);
						 $message->from('web@jamipol.com');
                    });
					
					
                    $vcode                      =  $getUserDetails->vendor_code;
                    $vcode2                     =  $getUserDetails->vendor_name_code;
					$password                   =  $getUserDetails->password;
                    $otp                        =  $otp;
                    $session_data =   array(
                        'vcode'                => $vcode,
                        'vcode2'               => $vcode2,
                        'password'             => $password,
						'otp'                  => $otp
                    );
                    Session::put($session_data);
					Auth::login($getUserDetails, $remember);
					$user_id_Session            =  $getUserDetails->id;
                    $user_name_Session          =  $getUserDetails->name;
                    $user_type_Session          =  $getUserDetails->user_type;
                    $user_sub_type_Session      =  $getUserDetails->user_sub_type;
                    $user_DivisionID_Session    =  $getUserDetails->division_id;
                    $user_DepartmentID_Session  =  $getUserDetails->department_id;
                    $user_SectionID_Session     =  $getUserDetails->section_id;
                   $vcode                      =  $getUserDetails->vendor_code;
                    $vcode2                     =  $getUserDetails->vendor_name_code;
                    $vms_role                   =  $getUserDetails->vms_roll;
                    $vms_yes_no                 =  $getUserDetails->vms;
                    $vms_admin                  =  $getUserDetails->vms_admin;
                    $clm_yes_no                 =  $getUserDetails->clm;
                    $clm_role                   =  $getUserDetails->clm_role;
                    $clms_admin                 =  $getUserDetails->clms_admin;
                    $safety_yes_no              =  $getUserDetails->safety;
                    $safety_role                =  $getUserDetails->safety_role;
                    $safety_admin               =  $getUserDetails->safety_admin;
                    $status                     =  $getUserDetails->status;
					$wps                        =  $getUserDetails->wps;
                  /* $session_data =   array(
                        'user_idSession'       => $user_id_Session,
                        'user_nameSession'     => $user_name_Session,
                        'user_typeSession'     => $user_type_Session,
                        'user_sub_typeSession' => $user_sub_type_Session,
                        'user_DivID_Session'   => $user_DivisionID_Session,  
                        'user_DeptID_Session'  => $user_DepartmentID_Session,  
                        'user_SecID_Session'   => $user_SectionID_Session,
                        'vcode'                => $vcode,
                        'vcode2'               => $vcode2,
                        'vms_role'             => $vms_role,
                        'vms_yes_no'           => $vms_yes_no,
                        'vms_admin'            => $vms_admin,
                        'clm_yes_no'           => $clm_yes_no,
                        'clm_role'             => $clm_role,
                        'clms_admin'           => $clms_admin,
                        'safety_yes_no'        => $safety_yes_no,
                        'safety_role'          => $safety_role,
                        'safety_admin'         => $safety_admin,
                        'status'               => $status,
						'wps'                  => $wps
                    ); */
                   // Session::put($session_data);
					
                  //  return redirect('admin/dashboard');
				   return redirect('otpPage');
                }
            }
            else{
                return back()->with('message','Error While Login, Password does not Match!!!');
            }
        //}       
    }
	// Show the OTP Page
    public function OTPPage()
    {
        return view('otpPage');
    }
public function OTPPost(Request $request){
       
$remember  =  $request->remember_me == "on";

        if ($request->enter_otp != Session::get('otp'))  {
            return back()->with('message','Oooops... OTP Incorrect.');
        }else{
            $getUserDetails  = UserLogin::where('vendor_code',Session::get('vcode'))->where('password',Session::get('password'))->first();
            //return $getUserDetails;
            if($getUserDetails){
                if($getUserDetails){
                    Auth::login($getUserDetails, $remember);
                    $user_id_Session            =  $getUserDetails->id;
                    $user_name_Session          =  $getUserDetails->name;
                    $user_type_Session          =  $getUserDetails->user_type;
                    $user_sub_type_Session      =  $getUserDetails->user_sub_type;
                    $user_DivisionID_Session    =  $getUserDetails->division_id;
                    $user_DepartmentID_Session  =  $getUserDetails->department_id;
                    $user_SectionID_Session     =  $getUserDetails->section_id;
                    $vcode                      =  $getUserDetails->vendor_code;
                    $vcode2                     =  $getUserDetails->vendor_name_code;
                    $vms_role                   =  $getUserDetails->vms_roll;
                    $vms_yes_no                 =  $getUserDetails->vms;
                    $vms_admin                  =  $getUserDetails->vms_admin;
                    $clm_yes_no                 =  $getUserDetails->clm;
                    $clm_role                   =  $getUserDetails->clm_role;
                    $clms_admin                 =  $getUserDetails->clms_admin;
                    $safety_yes_no              =  $getUserDetails->safety;
                    $safety_role                =  $getUserDetails->safety_role;
                    $safety_admin               =  $getUserDetails->safety_admin;
                    $status                     =  $getUserDetails->status;
					$wps                        =  $getUserDetails->wps;
                    $session_data =   array(
                        'user_idSession'       => $user_id_Session,
                        'user_nameSession'     => $user_name_Session,
                        'user_typeSession'     => $user_type_Session,
                        'user_sub_typeSession' => $user_sub_type_Session,
                        'user_DivID_Session'   => $user_DivisionID_Session,  
                        'user_DeptID_Session'  => $user_DepartmentID_Session,  
                        'user_SecID_Session'   => $user_SectionID_Session,
                        'vcode'                => $vcode,
                        'vcode2'               => $vcode2,
                        'vms_role'             => $vms_role,
                        'vms_yes_no'           => $vms_yes_no,
                        'vms_admin'            => $vms_admin,
                        'clm_yes_no'           => $clm_yes_no,
                        'clm_role'             => $clm_role,
                        'clms_admin'           => $clms_admin,
                        'safety_yes_no'        => $safety_yes_no,
                        'safety_role'          => $safety_role,
                        'safety_admin'         => $safety_admin,
                        'status'               => $status,
						'wps'                  => $wps
                    );
                    Session::put($session_data);
                    return redirect('admin/dashboard');
                }
            }
            else{
                return back()->with('message','Error While Login, Password does not Match!!!');
            }
        }       
    }
    public function dashboard(){
        
        $my_permits      = Permit::where('entered_by',Session::get('user_idSession'))->count();
        $permit_approval  = Permit::where('issuer_id',Session::get('user_idSession'))
                            ->where('status','Requested')
                            ->orWhere('area_clearence_id',Session::get('user_idSession'))
                            ->where('status','Parea')->count();
        $issued_permit   = Permit::where('issuer_id',Session::get('user_idSession'))
                        ->where('status','Issued')
			            ->orWhere('issuer_id',Session::get('user_idSession'))
                        ->where('status','Returned')
                        ->orWhere('area_clearence_id',Session::get('user_idSession'))
                        ->where('status','Issued')
			->orWhere('area_clearence_id',Session::get('user_idSession'))
                        ->where('status','Returned')->count();
        $pending_for_returns = Permit::where('issuer_id',Session::get('user_idSession'))
                            ->where('status','Issued')
                            ->where('return_status','Pending')
                            ->orwhere('return_status','Pending_area')
                            ->where('area_clearence_id',Session::get('user_idSession'))
                            ->orwhere('return_status','Pending_issuer')
                            ->where('issuer_id',Session::get('user_idSession'))
                            ->orderBy('id','DESC')->count(); 

        $renew_lists =  RenewPermit::where('issuer_id',Session::get('user_idSession'))
                                ->where('status','Pending_Renew_Issuer')
                                ->orWhere('area_id',Session::get('user_idSession'))
                                ->where('status','Pending_Renew_Area')
                                ->orderBy('id', 'DESC')->count();
        

        $expiryPermits = Permit::where('issuer_id',Session::get('user_idSession'))
                            ->where('status','Issued')
                            ->where('end_date',"<",date('Y-m-d H:i:s'))
                            ->orderBy('id', 'DESC')
                            ->count();
        $job_count = Job::count();
        $user_count = UserLogin::count();
        $division_count = Division::count();
        $department_count = Department::count();

      
if(Session::get('clm_role') =='hr_dept'){

$gatepasss_clms = DB::table('Clms_gatepass')->where('status','Pending_for_hr')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count();


$gatepasss_clms2 = DB::table('Clms_gatepass')
                              ->where('hr_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count();


}elseif(Session::get('user_typeSession') =='2'){

$gatepasss_clms = DB::table('Clms_gatepass')->where('created_by',Session::get('user_idSession'))
                                            ->where('status','Pending_for_shift_incharge')
                                            ->where('created_by',Session::get('user_idSession'))
                                            ->orwhere('status','Pending_for_hr')
                                            ->where('created_by',Session::get('user_idSession'))
                                            ->orwhere('status','Pending_for_safety')
                                            ->where('created_by',Session::get('user_idSession'))
                                            ->orwhere('status','Pending_for_plant_head')
                                            ->where('created_by',Session::get('user_idSession'))
                                            ->orwhere('status','Pending_executing')
                                            ->orderBy('id', 'DESC')->count();


        $gatepasss_clms2 = DB::table('Clms_gatepass')->where('created_by',Session::get('user_idSession'))
                             ->where('status','Pending_for_security')
                             ->where('created_by',Session::get('user_idSession'))
                              ->orderBy('id', 'DESC')->count();

}elseif(Session::get('clm_role') =='Executing_agency'){

              $gatepasss_clms = DB::table('Clms_gatepass')->where('status','Pending_executing')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->where('pending_excueting_by',Session::get('user_idSession'))
                              ->orderBy('id', 'DESC')->count();  

                   $gatepasss_clms2 = DB::table('Clms_gatepass')
                              
                             ->where('pending_excueting_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count();

}elseif(Session::get('clm_role') =='Safety_dept'){

$gatepasss_clms = DB::table('Clms_gatepass')->where('status','Pending_for_safety')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count();    


$gatepasss_clms2 = DB::table('Clms_gatepass')
                              ->where('safety_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count();  


}elseif(Session::get('clm_role') =='plant_head'){

$gatepasss_clms = DB::table('Clms_gatepass')->where('status','Pending_for_plant_head')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count();   


$gatepasss_clms2 = DB::table('Clms_gatepass')
                              
                              ->where('plant_head_by',Session::get('user_idSession'))
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count(); 


}
elseif(Session::get('clm_role') =='security' || Session::get('vms_role') =='Security'){

$gatepasss_clms = DB::table('Clms_gatepass')->where('status','Pending_for_security')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count();   


$gatepasss_clms2 = DB::table('Clms_gatepass')->where('status','Pending_for_security')
                              ->where('division',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count(); 


}else{ 
     $gatepasss_clms = DB::table('Clms_gatepass')->orderBy('id', 'DESC')->count();

    // $gatepasss_clms2= DB::table('Clms_gatepass')->where('division_id',Session::get('user_DivID_Session'))->orderBy('id', 'DESC')->count();
	$gatepasss_clms2 ="0";
}

if(Session::get('vms_yes_no')=='Yes' && Session::get('vms_role') =='Approver') {
$gatepasss = DB::table('visitor_gate_pass')->where('approver',Session::get('user_idSession'))
                                ->where('status','Pending_to_approve')
                              ->orderBy('id', 'DESC')->count();   
                      
$gatepasss2 = DB::table('visitor_gate_pass')->where('approver',Session::get('user_idSession'))
                              ->where('status','issued')
                              ->orwhere('status','Rejected')
                              ->where('approver',Session::get('user_idSession'))
                               ->orwhere('status','Completed')
                              ->where('approver',Session::get('user_idSession'))
                              ->orderBy('id', 'DESC')->count();  

 }elseif(Session::get('clm_role') =='security' || Session::get('vms_role') =='Security'){
	 
$gatepasss = DB::table('visitor_gate_pass')->where('status','issued')
                    ->where('division_id',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count();   

$gatepasss2 = DB::table('visitor_gate_pass')->where('status','Completed')
                               ->where('division_id',Session::get('user_DivID_Session'))
							 ->orwhere('status','Rejected')
							   ->where('division_id',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count();

}/*elseif(Session::get('user_sub_typeSession') == '3'){
		$gatepasss = DB::table('visitor_gate_pass')
                              ->orderBy('id', 'DESC')->count();
	
           $gatepasss2 = DB::table('visitor_gate_pass')->where('approver',Session::get('user_idSession'))
                                ->where('status','Pending_to_approve')
                              ->orderBy('id', 'DESC')->count();
 }*/elseif(Session::get('vms_yes_no')=='Yes' && Session::get('vms_role') =='Requester' ||  Session::get('vms_role')!= 'Security' && Session::get('vms_role')!= 'Requester'){

	$gatepasss = DB::table('visitor_gate_pass')->where('created_by',Session::get('user_idSession'))
            ->where('status','Pending_to_approve')
            ->orderBy('id', 'DESC')->count();  

$gatepasss2 = DB::table('visitor_gate_pass')->where('created_by',Session::get('user_idSession'))
            ->where('status','issued')
            ->where('created_by',Session::get('user_idSession'))
            ->orwhere('status','Rejected')
            ->where('created_by',Session::get('user_idSession'))
            ->where('status','Completed')
            ->orderBy('id', 'DESC')->count();

	

}

 if(Session::get('safety_yes_no')=='Yes')  {
     $safety_data = DB::table('safety_data_entry')->where('created_by',Session::get('user_idSession'))
                               ->where('division_id',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->count();
	 
     }else{                         
       $safety_data = DB::table('safety_data_entry')
                              ->orderBy('id', 'DESC')->count();
	 
}
		
	
		
  return view('admin.dashboard',compact('my_permits','permit_approval','issued_permit',
                    'job_count','user_count','division_count','department_count','expiryPermits',
                    'pending_for_returns','renew_lists','gatepasss_clms','gatepasss_clms2','gatepasss','gatepasss2','safety_data'));
    }

    public function captcha(Request $request)
    {
        $text = rand(10000,99999); 
        $sessioncreate =   array(
            'capchaSession'       => $text
        );
        Session::put($sessioncreate);
        $height = 36; 
        $width  = 80;   
        $image_p = imagecreate($width, $height); 
        $blue  = imagecolorallocate($image_p, 30, 65, 171); 
        $white = imagecolorallocate($image_p, 255, 255, 255); 
        $font_size = 20; 
        imagestring($image_p, $font_size, 10,10, $text, $white); 
        imagejpeg($image_p, null, 80);

        // Free memory
        imagedestroy($image_p);
    }
	 public function logout(Request $request)
    {
       Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if (\Cookie::has('remember_token')) {
            Cookie::queue(\Cookie::forget('remember_token'));
        }
        session_unset();
		
        $response = redirect('/login');

        foreach ($_COOKIE as $name => $value) {
          $response->withCookie(\Cookie::forget($name));
       }
 
        return $response;
    } 
public function logout1()
    {
      Session::flush();
      return redirect('login');
    }
	
    public function vendor_details(){
        return view('admin.vendor_details');
    }

}
