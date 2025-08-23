<?php

namespace App\Http\Controllers\API;

use JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserLogin;
use App\Job;
use Auth;

class LoginController extends Controller
{
    public $successStatus = 200;

    // public $loginAfterSignUp = true;

    // public function login(Request $request)
    // {
    //     $credentials = $request->only("vendor_code","password");
    //     $token = null;   

    //     if (!$token = JWTAuth::attempt($credentials)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid Email or Password',
    //         ], 401);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'token'   => $token,
    //     ]);
    // }

    // public function register(Request $request)
    // {
    //     // if($request->user_type == 2){
    //         $request->validate([
    //             'name'        => 'required|min:4|max:50',
    //             'vendor_code' => 'required|numeric',
    //             'user_type'   => 'numeric',
    //         ]);
    
    //         // $get_vendor_id = UserLogin::where('vendor_code',$request->vendor_code)->where('user_type',2)->first(); 
    //         // if($get_vendor_id == null)
    //         // {
    //             $password  = rand(100000, 999999);
    //             $vendor    =  UserLogin::create([
    //                 'name'           => $request->name,
    //                 'password'       => $password,
    //                 'vendor_code'    => $request->vendor_code,
    //                 'user_type'      => $request->user_type,
    //                 'user_sub_type'  => '2'
    //             ]);

    //             if ($this->loginAfterSignUp) {
    //                 return $this->login($request);
    //             }
        
    //             return response()->json([
    //                 'success'   =>  true,
    //                 'data'      =>  $vendor
    //             ], 200);

                // if($vendor){
                //     return back()->with('message','Vendor Create Suceessfully');
                // }
                // else{
                //     return back()->with('message','Ooops... Error While Adding user');
                // }
            // }
            // else{
            //     return back()->with('message','Vendor Already Registered');
            // }
        // } 
    // }

    // public function logout(Request $request)
    // {
    //     $this->validate($request, [
    //         'token' => 'required'
    //     ]);

    //     try {
    //         JWTAuth::invalidate($request->token);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User logged out successfully'
    //         ]);

    //     } catch (JWTException $exception) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Sorry, the user cannot be logged out'
    //         ], 500);
    //     }
    // }

    // public function job()
    // {
    //     $job = Job::get();
    //     return $job;
    // }


    public function login(Request $request){

        $loginData = $request->validate([
            'vendor_code'  =>   'required',
            'password'     =>   'required|min:5|max:50',
        ]);

        if(UserLogin::where('vendor_code',$request->vendor_code)->first()){
            $user_datas = UserLogin::where('vendor_code',$request->vendor_code)->get();

            $success['token'] =  $user_datas->createToken('MyApp')->accessToken; 
            return response()->json(['success' => $success], $this->successStatus);
        }
        else{
            return response()->json(['message' => "Invalid Login"]);
        }
    
    }


    public function register(Request $request)
    { 
        $request->validate([
            'vendor_code'  =>   'required',
            'password'     =>   'required',
        ]);

        return response()->json(["message" => "invalid Data"]);

        // $validatedata = array([
        //     'vendor_code'  =>  'required',
        //     'password'    
        
        // ]);



        $user = UserLogin::create($validatedata); 

        $success['token'] =  $user->createToken('MyApp')->accessToken;

        return response()->json(['vendor' => $vendor, 'accessToken' => $accessToken],$this->successStatus);
        
    }
}
