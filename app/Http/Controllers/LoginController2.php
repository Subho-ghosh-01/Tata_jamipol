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
        ]);
    
        $vendor_code     =  $request->vendor_code;
        $password        =  $request->password;
        $password        =  md5($password);
        $remember        =  $request->remember_me == "on";
        if ($request->vercode != Session::get('capchaSession') OR Session::get('capchaSession')=='')  {
            return back()->with('message','Oooops... Captcha Incorrect.');
        }else{
            $getUserDetails  = UserLogin::where('vendor_code',$vendor_code)->where('password',$password)->first();
			Auth::login($getUserDetails, $remember);
			
            //return $getUserDetails;
            if($getUserDetails){
                if($getUserDetails){
                    $user_id_Session            =  $getUserDetails->id;
                    $user_name_Session          =  $getUserDetails->name;
                    $user_type_Session          =  $getUserDetails->user_type;
                    $user_sub_type_Session      =  $getUserDetails->user_sub_type;
                    $user_DivisionID_Session    =  $getUserDetails->division_id;
                    $user_DepartmentID_Session  =  $getUserDetails->department_id;
                    $user_SectionID_Session     =  $getUserDetails->section_id;
                    $vcode                      =  $getUserDetails->vendor_code;
                    $vcode2                     =  $getUserDetails->vendor_name_code;
                                        
                    $session_data =   array(
                        'user_idSession'       => $user_id_Session,
                        'user_nameSession'     => $user_name_Session,
                        'user_typeSession'     => $user_type_Session,
                        'user_sub_typeSession' => $user_sub_type_Session,
                        'user_DivID_Session'   => $user_DivisionID_Session,  
                        'user_DeptID_Session'  => $user_DepartmentID_Session,  
                        'user_SecID_Session'   => $user_SectionID_Session,
                        'vcode'                => $vcode,
                        'vcode2'               => $vcode2

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

        return view('admin.dashboard',compact('my_permits','permit_approval','issued_permit',
                    'job_count','user_count','division_count','department_count','expiryPermits',
                    'pending_for_returns','renew_lists'));
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

}
