<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserLogin;
use Session;
use Mail;

class RegisterController extends Controller
{
    public function forgotPage(){

        return view('forgotPage');
    } 

    public function forgotPost(Request $request)
    {
		$request->validate([ 
            'g-recaptcha-response' => 
            ['required']]);
        // dd($request->all());
       // if ($request->g-recaptcha-response != Session::get('capchaSession') OR Session::get('capchaSession')=='')  {
       //     return back()->with('message','Oooops... Captcha Incorrect.');
        //}else{
            $getUserDetails  = UserLogin::where('vendor_code',$request->vendor_code)->first();
            if($getUserDetails){
                $seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789!@#$%^&*()'); // and any other characters
                shuffle($seed); // probably optional since array_is randomized; this may be redundant
                $rand = '';
                foreach (array_rand($seed, 10) as $k) $rand .= $seed[$k];
                $password = $rand;
                $employee =  UserLogin::where('id',$getUserDetails->id)->update([
                    'password'       => md5($password),
                ]);
        
                $getUserUpdateDetails = UserLogin::where('id',$getUserDetails->id)->first();
                $user = array('name'        => $getUserUpdateDetails->name,
                            'email'         => $getUserUpdateDetails->email,
                            'vendor_code'   => $getUserUpdateDetails->vendor_code,
                            'password'      => $password,
                            'subject'       => "JAMIPOL App Password"
                            );

                if($employee){
                    Mail::send('admin.users.send_pwd',['data' => $user],function($message) use ($user){
                        $message->to($user['email'])
                                ->subject($user['subject']);
                        $message->from('web@jamipol.com');
                    });
                    return redirect('forgotPage')->with('message','Password Reset,Please Check Your email for password');
                }
            }
            else{
                return redirect('forgotPage')->with('message','Password Reset,Please Check Your email for password');
            }
        //}
    }

}
