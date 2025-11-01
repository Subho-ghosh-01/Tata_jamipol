<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserLogin;
use Session;
use App\Division;
use App\Department;
use App\VendorSupervisor;
use Str;
use Hash;
use App\VendorEmployeeDetails;
use App\ShutdownChild;
use App\Permit;
use Mail;

class UserController extends Controller
{
    public function index()
    {
        if (Session::get('vms_role') != 'Security') {
            $divisions = Division::all();
            if (Session::get('user_sub_typeSession') == 3) {
                $users = UserLogin::where('user_type', 1)->orderBy('id', 'desc')->get();
                $vendors = UserLogin::where('user_type', 2)->orderBy('id', 'desc')->get();
                $vendors_approvals = UserLogin::where('user_type', 2)->where('status', 'pending_clms_vendor')->orwhere('status', 'Pending_for_hr')->orwhere('status', 'Pending_for_safety')->orderBy('id', 'desc')->get();
            } else {
                $users = UserLogin::where('user_type', 1)->where(['division_id' => Session::get('user_DivID_Session')])
                    ->where('id', '!=', Session::get('user_idSession'))->orderBy('id', 'desc')->get();
                $vendors = UserLogin::where('user_type', 2)->where(['division_id' => Session::get('user_DivID_Session')])
                    ->where('id', '!=', Session::get('user_idSession'))->orderBy('id', 'desc')->get();
                $vendors_approvals = UserLogin::where(['division_id' => Session::get('user_DivID_Session')])
                    ->where('id', '!=', Session::get('user_idSession'))->where('status', 'pending_clms_vendor')->orwhere('status', 'Pending_for_hr')->orwhere('status', 'Pending_for_safety')->orderBy('id', 'desc')->get();
            }
            return view('admin.users.index', compact('users', 'vendors', 'divisions', 'vendors_approvals'));
        } else {
            return redirect()->route('admin.dashboard');
            // return view('admin.dashboard');
        }

    }


    public function create()
    {
        $divisions = Division::all();
        $users = UserLogin::all();
        return view('admin.users.create', compact('users', 'divisions'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        $date = date('Y-m-d H:i:s');
        //----------------------------------- Vendor Approval HR AND SAFETY ------------------------//
        /*  if($request->user_type == 2 && $request->clms == 'Yes')
          {}
       
              $status ='Pending_clms_vendor';
              $today  = date('Y-m-d');
              $expectedEndDate = date('Y-m-d', strtotime($today. ' + 30 days'));
             
              $request->validate([
                  'name'        => 'required|min:1|max:20',
                  'vendor_code' => 'required',
                  'user_type'   => 'numeric',
                  'email'       => 'required',
                  'vendor_division_id' => 'required',
                  'supervisor_name.*'  => 'required',
                  'vendor_name_code'   => 'required'
                  

              ],[
                  'vendor_name_code.'.'required'  => 'Vendor Code is Required.',
                  'vendor_code.'.'required' => 'Vendor for Login Name is Required.',
                  'vendor_division_id.'.'required'   => 'Select Division For Vendor',
                  
              ]);
      
              $get_vendor_id = UserLogin::where('vendor_code',$request->vendor_code)->where('user_type',2)->first(); 
              if($get_vendor_id == null)
              {
                  $seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789!@#$%^&*()'); // and any other characters
                  shuffle($seed); // probably optional since array_is randomized; this may be redundant
                  $rand = '';
                  foreach (array_rand($seed, 10) as $k)  $rand .= $seed[$k];
                  $password = $rand;
                  
                  $vendor   =  UserLogin::create([
                      'name'           => $request->name,
                      'password'       => md5($password),
                      'vendor_code'    => $request->vendor_code,
                      'vendor_name_code' => $request->vendor_name_code,
                      'user_type'      => $request->user_type,
                      'user_sub_type'  => '2',
                      'email'          => $request->email,
                      'division_id'    => $request->vendor_division_id,
                      'created_by'     => Session::get('user_idSession')
                  ]);

                  if($request->supervisor){
                      foreach($request->supervisor as $super){
                          $insertSupervisor = VendorSupervisor::create([
                              'vendor_id'         => @$vendor->id,
                              'supervisor_name'   => @$super
                          ]);
                      }
                  }
                   
                  if($request->employee){
                      foreach ($request->employee as $key => $value) {
                          VendorEmployeeDetails::insert([
                              'userlogins_id'  => @$vendor->id,
                              'employee'       => @$request->employee[$key],
                              'gatepass'       => @$request->gatepass[$key],
                              'designation'    => @$request->designation[$key],
                              'age'            => @$request->age[$key],
                              'expiry'         => @$request->expirydate[$key],
                              'created_at'     => @$date
                          ]); 
                      }
                  }     
                  if($request->ElectricalVendor == 'yes'){
                      if($request->supervisor_ven){
                          foreach($request->supervisor_ven as $key => $value){
                              $vendorChildInsert  = ShutdownChild::create([
                                  'userlogins_id'         => @$vendor->id,
                                  'supervisor_name'       => @$request->supervisor_ven[$key],
                                  'electrical_license'    => @$request->electrical_license_ven[$key],
                                  'validity_date'         => @$request->license_validity_ven[$key],
                                  '132KV'                 => @$request->v132kv_ven[$key],
                                  '33KV'                  => @$request->v33kv_ven[$key],
                                  '11KV'                  => @$request->v11kv_ven[$key],
                                  'LT'                    => @$request->vlt_ven[$key],
                                  'issue_power'           => @$request->issue_power_ven[$key],
                                  'receive_power'         => @$request->rec_power_ven[$key],
                              ]);
                          }
                      }
                  }

                  $user = array('email'  => $request->email,
                              'name'     => $request->name,
                              'vendor_code'=> $request->vendor_code,
                              'password'  => $password,
                              'subject'   => "JAMIPOL App Password");

                  if($vendor){
                      Mail::send('admin.users.send_pwd',['data' => $user],function($message) use ($user){
                          $message->to($user['email'])
                                  ->subject($user['subject']);
                          $message->from('saprly@jamipol.com');
                      });
                                      
                      $msg = "User added Sucessfully.Please Check Your email for password " . $password;
                      return back()->with('message',$msg);
                  }
                  else{
                      return back()->with('message','Ooops... Error While Adding user');
                  }
              }
              else{
                  return back()->with('message','Vendor Already Registered');
              }
          }*/

        //------------------------------------------ Vendor ------------------------------------//
        if ($request->user_type == 2) {
            $today = date('Y-m-d');
            $expectedEndDate = date('Y-m-d', strtotime($today . ' + 30 days'));

            $request->validate([
                'name' => 'required|min:1',
                'vendor_code' => 'required',
                'user_type' => 'numeric',
                'email' => 'required',
                'vendor_division_id' => 'required',
                'supervisor_name.*' => 'required',
                'vendor_name_code' => 'required'
            ], [
                'vendor_name_code.' . 'required' => 'Vendor Code is Required.',
                'vendor_code.' . 'required' => 'Vendor for Login Name is Required.',
                'vendor_division_id.' . 'required' => 'Select Division For Vendor',

            ]);

            $get_vendor_id = UserLogin::where('vendor_code', $request->vendor_code)->where('user_type', 2)->first();
            if ($get_vendor_id == null) {
                $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789!@#$%^&*()'); // and any other characters
                shuffle($seed); // probably optional since array_is randomized; this may be redundant
                $rand = '';
                foreach (array_rand($seed, 10) as $k)
                    $rand .= $seed[$k];
                $password = $rand;

                if ($request->user_type == 2 && $request->clms == 'Yes') {
                    $status = 'pending_clms_vendor';
                } else {
                    $status = '';
                }

                $vendor = UserLogin::create([
                    'name' => $request->name,
                    'password' => md5($password),
                    'vendor_code' => $request->vendor_code,
                    'vendor_name_code' => $request->vendor_name_code,
                    'user_type' => $request->user_type,
                    'user_sub_type' => '2',
                    'email' => $request->email,
                    'division_id' => $request->vendor_division_id,
                    'created_by' => Session::get('user_idSession'),
                    'clm' => $request->clms,
                    'clms_admin' => $request->clms_admin,
                    'status' => $status,
                    'active' => 'Yes',
                    'vendor_abb' => $request->vendor_abb,
                    'unskilled' => $request->unskilled,
                    'semi_skilled' =>$request->semi_skilled,
                    'skilled' => $request->skilled,
                    'high_skilled' => $request->high_skilled,
                    'lobour_capacity' =>$request->labour_capacity
                ]);

                if ($request->supervisor) {
                    foreach ($request->supervisor as $super) {
                        $insertSupervisor = VendorSupervisor::create([
                            'vendor_id' => @$vendor->id,
                            'supervisor_name' => @$super
                        ]);
                    }
                }

                if ($request->employee) {
                    foreach ($request->employee as $key => $value) {
                        $data = [
                            'userlogins_id' => @$vendor->id,
                            'employee' => @$request->employee[$key],
                            'gatepass' => @$request->gatepass[$key],
                            'designation' => @$request->designation[$key],
                            'age' => @$request->age[$key],
                            'expiry' => @$request->expirydate[$key],
                            'created_at' => @$date
                        ];
                
                        
                
                        VendorEmployeeDetails::insert($data);
                    }
                }
                
                if ($request->ElectricalVendor == 'yes') {
                    if ($request->supervisor_ven) {
                        foreach ($request->supervisor_ven as $key => $value) {
                            $vendorChildInsert = ShutdownChild::create([
                                'userlogins_id' => @$vendor->id,
                                'supervisor_name' => @$request->supervisor_ven[$key],
                                'electrical_license' => @$request->electrical_license_ven[$key],
                                'validity_date' => @$request->license_validity_ven[$key],
                                '132KV' => @$request->v132kv_ven[$key],
                                '33KV' => @$request->v33kv_ven[$key],
                                '11KV' => @$request->v11kv_ven[$key],
                                'LT' => @$request->vlt_ven[$key],
                                'issue_power' => @$request->issue_power_ven[$key],
                                'receive_power' => @$request->rec_power_ven[$key],
                            ]);
                        }
                    }
                }

                $user = array(
                    'email' => $request->email,
                    'name' => $request->name,
                    'vendor_code' => $request->vendor_code,
                    'password' => $password,
                    'subject' => "JAMIPOL App Password"
                );

                if ($vendor) {
                    Mail::send('admin.users.send_pwd', ['data' => $user], function ($message) use ($user) {
                        $message->to($user['email'])
                            ->subject($user['subject']);
                        $message->from('web@jamipol.com');
                    });
                }
                if ($vendor) {
                    $msg = "User added Sucessfully.Please Check Your email for password " . $password;
                    return back()->with('message', $msg);
                } else {
                    return back()->with('message', 'Ooops... Error While Adding user');
                }
            } else {
                return back()->with('message', 'Vendor Already Registered');
            }
        }


        //------------------------------------------ Employee ------------------------------------//
        elseif ($request->user_type == 1) {
            //echo $request->user_type;
//exit;
            $today = date('Y-m-d');
            $expectedEndDate = date('Y-m-d', strtotime($today . " + 30 days"));

            //if($request->wps_user=='No'){
            //$user_type='1';
            //}else{
            //	$user_type=$request->user_type;
            //}
            //if($request->wps_user=='No'){
            //	$user_sub_type='2';
            //}else{
            //	$user_sub_type=$request->user_sub_type;
            //}

            $request->validate([
                'name' => 'required|min:1',
                'email' => 'required|email',
                'vendor_code' => 'required|numeric',
                'user_type' => 'numeric',
                //  'user_sub_type' => 'numeric',
                'division_id' => 'required|numeric',
                'department_id' => 'numeric',
                //'power_cutting' => 'required',
                //'power_getting' => 'required',
                //'confined_space' => 'required',
                // 'license_validity_emp' => 'date|after:'.$expectedEndDate
            ], [
                // 'license_validity_emp.'.'date'  => 'Enter license validity date',
                // 'license_validity_emp.'.'after' => 'The license validity date must be more then 30 days from current date.',
                'division_id.' . 'numeric' => 'Please Select Division.',
                'department_id.' . 'numeric' => 'Please Select Department',

            ]);

            $get_vendor_id = UserLogin::where('vendor_code', $request->vendor_code)->where('user_type', 1)->first();
            if ($get_vendor_id == null) {
                $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789!@#$%^&*()'); // and any other characters
                shuffle($seed); // probably optional since array_is randomized; this may be redundant
                $rand = '';
                foreach (array_rand($seed, 10) as $k)
                    $rand .= $seed[$k];
                $password = $rand;

                $employee = UserLogin::create([
                    'email' => $request->email,
                    'name' => $request->name,
                    'password' => md5($password),
                    'vendor_code' => $request->vendor_code,
                    'user_type' => '1',
                    'user_sub_type' => $request->user_sub_type,
                    'division_id' => $request->division_id,
                    'department_id' => $request->department_id,
                    'power_cutting' => $request->power_cutting,
                    'power_getting' => $request->power_getting,
                    'confined_space' => $request->confined_space,
                    'wps' => $request->wps_user,
                    'vms' => $request->vms,
                    'vms_roll' => $request->vms_role,
                    'clm' => $request->clms,
                    'clm_role' => $request->clms_role,
                    'safety' => $request->safety,
                    'safety_role' => $request->safety_role,
                    'vms_admin' => $request->vms_admin,
                    'clms_admin' => $request->clms_admin,
                    'safety_admin' => $request->safety_admin,
                    'count' => $request->count,
                    'active' => 'Yes'

                ]);

                if ($request->ElectricalSup == 'yes') {
                    $powerShutdownchild = ShutdownChild::create([
                        'userlogins_id' => $employee->id,
                        'electrical_license' => $request->electrical_license_emp,
                        'validity_date' => $request->license_validity_emp,
                        '132KV' => $request->v133kv_emp,
                        '33KV' => $request->v33kv_emp,
                        '11KV' => $request->v11kv_emp,
                        'LT' => $request->vlt_emp,
                        'issue_power' => $request->issue_power,
                        'receive_power' => $request->rec_power
                    ]);
                }

                $user = array(
                    'email' => $request->email,
                    'name' => $request->name,
                    'vendor_code' => $request->vendor_code,
                    'password' => $password,
                    'subject' => "JAMIPOL App Password"
                );

                if ($employee) {
                    Mail::send('admin.users.send_pwd', ['data' => $user], function ($message) use ($user) {
                        $message->to($user['email'])
                            ->subject($user['subject']);
                        $message->from('web@jamipol.com');
                    });
                    return back()->with('message', 'User added Sucessfully.Please Check Your email for password ' . $password);
                } else {
                    return back()->with('message', 'Ooops... Error While Adding user');
                }
            } else {
                return back()->with('message', 'Employee Already Registered');
            }
        }

        // 
        elseif ($request->wps_user == 'No') {

            $today = date('Y-m-d');
            $expectedEndDate = date('Y-m-d', strtotime($today . " + 30 days"));

            if (($request->vms_role == 'Security') || ($request->vms_role == 'Approver') || ($request->clms_role == 'Shift_incharge') || ($request->clms_role == 'hr_dept') || ($request->clms_role == 'Safety_dept') || ($request->clms_role == 'plant_head') || ($request->clms_role == 'security') || ($request->safety == 'Yes')) {
                $user_type = '1';
                $user_sub_type = '2';
            } else {
                $user_type = '2';
                $user_sub_type = '2';
            }


            $get_vendor_id = UserLogin::where('vendor_code', $request->vendor_code)->where('user_type', 1)->first();
            if ($get_vendor_id == null) {
                $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789!@#$%^&*()'); // and any other characters
                shuffle($seed); // probably optional since array_is randomized; this may be redundant
                $rand = '';
                foreach (array_rand($seed, 10) as $k)
                    $rand .= $seed[$k];
                $password = $rand;

                $employee = UserLogin::create([
                    'email' => $request->email,
                    'name' => $request->name,
                    'password' => md5($password),
                    'vendor_code' => $request->vendor_code,
                    'user_type' => $user_type,
                    'user_sub_type' => $user_sub_type,
                    'division_id' => $request->division_id,
                    'department_id' => $request->department_id,
                    'power_cutting' => $request->power_cutting,
                    'power_getting' => $request->power_getting,
                    'confined_space' => $request->confined_space,
                    'wps' => $request->wps_user,
                    'vms' => $request->vms,
                    'vms_roll' => $request->vms_role,
                    'clm' => $request->clms,
                    'clm_role' => $request->clms_role,
                    'safety' => $request->safety,
                    'safety_role' => $request->safety_role,
                    'vms_admin' => $request->vms_admin,
                    'clms_admin' => $request->clms_admin,
                    'safety_admin' => $request->safety_admin,
                    'count' => $request->count,
                    'active' => 'Yes'

                ]);

                if ($request->ElectricalSup == 'yes') {
                    $powerShutdownchild = ShutdownChild::create([
                        'userlogins_id' => $employee->id,
                        'electrical_license' => $request->electrical_license_emp,
                        'validity_date' => $request->license_validity_emp,
                        '132KV' => $request->v133kv_emp,
                        '33KV' => $request->v33kv_emp,
                        '11KV' => $request->v11kv_emp,
                        'LT' => $request->vlt_emp,
                        'issue_power' => $request->issue_power,
                        'receive_power' => $request->rec_power
                    ]);
                }

                $user = array(
                    'email' => $request->email,
                    'name' => $request->name,
                    'vendor_code' => $request->vendor_code,
                    'password' => $password,
                    'subject' => "JAMIPOL App Password"
                );

                if ($employee) {
                    Mail::send('admin.users.send_pwd', ['data' => $user], function ($message) use ($user) {
                        $message->to($user['email'])
                            ->subject($user['subject']);
                        $message->from('web@jamipol.com');
                    });
                    return back()->with('message', 'User added Sucessfully.Please Check Your email for password ' . $password);
                } else {
                    return back()->with('message', 'Ooops... Error While Adding user');
                }
            } else {
                return back()->with('message', 'Employee Already Registered');
            }
        }


    }
    public function vendor_clms()
    {


    }
    public function show($id)
    {
        //
    }

    public function edit($idenc)
    {
        $id = \Crypt::decrypt($idenc);
        //Unique Record
        $user = UserLogin::where('id', $id)->first();
        $divisions = Division::all();
        $departments = Department::where('division_id', $user->division_id)->get();
        $powershutdown = UserLogin::leftjoin('shutdownchilds', 'shutdownchilds.userlogins_id', '=', 'userlogins.id')
            ->where('userlogins.id', $id)
            ->select(
                'shutdownchilds.id',
                'shutdownchilds.supervisor_name',
                'shutdownchilds.electrical_license',
                'shutdownchilds.validity_date',
                'shutdownchilds.132KV as KV132',
                'shutdownchilds.33KV as KV33',
                'shutdownchilds.11KV as KV11',
                'shutdownchilds.LT as LT',
                'shutdownchilds.issue_power',
                'shutdownchilds.receive_power'
            )->get();

        $gatepass = UserLogin::leftjoin('userlogins_employee_details', 'userlogins_employee_details.userlogins_id', '=', 'userlogins.id')
            ->where('userlogins.id', $id)
            ->select('userlogins_employee_details.*')->get();
        // echo $gatepass;
        // exit;

        $get_supervisors = VendorSupervisor::where('vendor_id', $id)->get();
        return view('admin.users.edit', compact('id', 'user', 'divisions', 'departments', 'get_supervisors', 'powershutdown', 'gatepass'));
    }


    public function update(Request $request, $id)
    {
        if ($request->user_type == 2) {
            $request->validate([
                'name' => 'required|min:1',
                'vendor_code' => 'required',
                'user_type' => 'numeric',
                'email' => 'required',
                'vendor_division_id' => 'required',
                'vendor_name_code' => 'required'
            ], [
                'vendor_division_id.' . 'required' => 'Vendor Division is Required',
                'vendor_name_code.' . 'required' => 'Vendor Code  is Required'

            ]);

            $check_vendor = UserLogin::where('vendor_code', $request->vendor_code)
                ->where('user_type', 2)->first();


            if ($request->user_type == 2 && $request->clms == 'Yes' && $check_vendor->company_name == '') {
                $status = 'pending_clms_vendor';
            } elseif ($request->clms == 'No') {
                $status = '';
            } else {
                $status = '';
            }

            $get_vendor_id = UserLogin::where('vendor_code', $request->vendor_code)
                ->where('user_type', 2)->where('id', '!=', $id)->first();

            if (!$get_vendor_id) {
                $vendor = UserLogin::where('id', $id)->update([
                    'name' => $request->name,
                    'vendor_code' => $request->vendor_code,
                    'user_type' => $request->user_type,
                    'user_sub_type' => '2',
                    'email' => $request->email,
                    'division_id' => $request->vendor_division_id,
                    'vendor_name_code' => $request->vendor_name_code,
                    'wps' => $request->wps_user,
                    'vms' => $request->vms,
                    'vms_roll' => $request->vms_role,
                    'clm' => $request->clms,
                    'clm_role' => $request->clms_role,
                    'safety' => $request->safety,
                    'safety_role' => $request->safety_role,
                    'vms_admin' => $request->vms_admin,
                    'clms_admin' => $request->clms_admin,
                    'safety_admin' => $request->safety_admin,
                    'status' => $status,
                    'active' => $request->active,
                    'lobour_capacity' => $request->labour_capacity,
                    'vendor_abb' => $request->vendor_abb,
                    'unskilled' =>$request->unskilled,
                    'semi_skilled' => $request->semi_skilled,
                    'skilled' =>$request->skilled,
                    'high_skilled' => $request->high_skilled
                ]);
                if ($request->supervisor) {
                    foreach ($request->supervisor as $key => $value) {
                        $unique_id = $request->uni_sup[$key];
                        $vendor_old = VendorSupervisor::where('id', $unique_id)->first();
                        if ($vendor_old != null) {
                            VendorSupervisor::where('id', $unique_id)->update([
                                'supervisor_name' => @$request->supervisor[$key],
                            ]);
                        } else {
                            VendorSupervisor::insert([
                                'vendor_id' => $id,
                                'supervisor_name' => @$request->supervisor[$key],
                            ]);
                        }
                    }
                }

               
 //if ($request->employee != '') {
    foreach ($request->employee as $key => $employee) {

        $unique_id = $request->oldgatepassid[$key] ?? null; // Use null if not set

        if ($unique_id) {
          
            // Update existing record
            VendorEmployeeDetails::where('id', $unique_id)->update([
                'employee'    => $employee,
                'gatepass'    => $request->gatepass[$key] ?? null,
                'designation' => $request->designation[$key] ?? null,
                'age'         => $request->age[$key] ?? null,
                'expiry'      => $request->expirydate[$key] ?? null,
            ]);
        } else {
            if($employee !=''){
            // Insert new record
            VendorEmployeeDetails::insert([
                'userlogins_id' => $id,
                'employee'      => $employee,
                'gatepass'      => $request->gatepass[$key] ?? null,
                'designation'   => $request->designation[$key] ?? null,
                'age'           => $request->age[$key] ?? null,
                'expiry'        => $request->expirydate[$key] ?? null,
            ]);
            }
        }
    }
             //   }

                if ($request->supervisor_ven) {
                    foreach ($request->supervisor_ven as $key => $value) {
                        $unique_id = $request->uni_id[$key];
                        $vendor_old = ShutdownChild::where('id', $unique_id)->first();
                        if ($vendor_old != null) {
                            $vendorChildInsert = ShutdownChild::where('id', $unique_id)->update([
                                'supervisor_name' => @$request->supervisor_ven[$key],
                                'electrical_license' => @$request->electrical_license_ven[$key],
                                'validity_date' => @$request->license_validity_ven[$key],
                                '132KV' => @$request->v132kv_ven[$key],
                                '33KV' => @$request->v33kv_ven[$key],
                                '11KV' => @$request->v11kv_ven[$key],
                                'LT' => @$request->vlt_ven[$key],
                                'issue_power' => @$request->issue_power_ven[$key],
                                'receive_power' => @$request->rec_power_ven[$key],
                            ]);
                        } else {
                            $vendorChildInsert = ShutdownChild::insert([
                                'userlogins_id' => $id,
                                'supervisor_name' => @$request->supervisor_ven[$key],
                                'electrical_license' => @$request->electrical_license_ven[$key],
                                'validity_date' => @$request->license_validity_ven[$key],
                                '132KV' => @$request->v132kv_ven[$key],
                                '33KV' => @$request->v33kv_ven[$key],
                                '11KV' => @$request->v11kv_ven[$key],
                                'LT' => @$request->vlt_ven[$key],
                                'issue_power' => @$request->issue_power_ven[$key],
                                'receive_power' => @$request->rec_power_ven[$key],
                            ]);
                        }
                    }
                }


                if ($vendor) {
                    return back()->with('message', 'Vendor Update Suceessfully');
                } else {
                    return back()->with('message', 'Ooops... Error While Adding user');
                }
            } else {
                return back()->with('message', 'Duplicate Vendor Code');
            }
        } elseif ($request->user_type == 1) {
            $request->validate([
                'user_type' => 'numeric',
                'name' => 'required|min:1',
                'vendor_code' => 'required|numeric',
                'email' => 'required|email',
                'user_sub_type' => 'numeric',
                'division_id' => 'required|numeric',
                'department_id' => 'numeric',
            ]);

            $get_vendor_id = UserLogin::where('vendor_code', $request->vendor_code)->where('user_type', 1)
                ->where('id', '!=', $id)->first();
            if (!$get_vendor_id) {
                $employee = UserLogin::where('id', $id)->update([
                    'user_type' => '1',
                    'name' => $request->name,
                    'vendor_code' => $request->vendor_code,
                    'email' => $request->email,
                    'user_sub_type' => $request->user_sub_type,
                    'division_id' => $request->division_id,
                    'department_id' => $request->department_id,
                    'wps' => $request->wps_user,
                    'vms' => $request->vms,
                    'vms_roll' => $request->vms_role,
                    'clm' => $request->clms,
                    'clm_role' => $request->clms_role,
                    'safety' => $request->safety,
                    'safety_role' => $request->safety_role,
                    'vms_admin' => $request->vms_admin,
                    'clms_admin' => $request->clms_admin,
                    'safety_admin' => $request->safety_admin,
                    'power_cutting' => $request->power_cutting,
                    'power_getting' => $request->power_getting,
                    'confined_space' => $request->confined_space,
                    'active' => $request->active,
                    'lobour_capacity' => $request->labour_capacity,

                ]);


                if ($request->ElectricalSup == "yes") {
                    $checkshutdown = ShutdownChild::where('userlogins_id', $id)->get();
                    if (@$checkshutdown[0]->id) {
                        $powerShutdownchild = ShutdownChild::where('id', $checkshutdown[0]->id)->update([
                            'electrical_license' => $request->electrical_license_emp,
                            'validity_date' => $request->license_validity_emp,
                            '132KV' => $request->v133kv_emp,
                            '33KV' => $request->v33kv_emp,
                            '11KV' => $request->v11kv_emp,
                            'LT' => $request->vlt_emp,
                            'issue_power' => $request->issue_power,
                            'receive_power' => $request->rec_power
                        ]);
                    } else {
                        $powerShutdownchild = ShutdownChild::insert([
                            'userlogins_id' => $id,
                            'electrical_license' => $request->electrical_license_emp,
                            'validity_date' => $request->license_validity_emp,
                            '132KV' => $request->v133kv_emp,
                            '33KV' => $request->v33kv_emp,
                            '11KV' => $request->v11kv_emp,
                            'LT' => $request->vlt_emp,
                            'issue_power' => $request->issue_power,
                            'receive_power' => $request->rec_power
                        ]);
                    }
                } else if ($request->ElectricalSup == "no") {
                    $delete = ShutdownChild::where('userlogins_id', $id)->delete();
                }

                if ($employee) {
                    return back()->with('message', 'Employee Update Suceessfully');
                } else {
                    return back()->with('message', 'Ooops... Error While Adding user');
                }
            } else {
                return back()->with('message', 'Employee Already Registered');
            }
        }
    }


    public function destroy($id)
    {
        if ($id != 0) {
            $permits = Permit::where('entered_by', '=', $id)->get();
            if (@$permits[0]->id != "") {
                return redirect('admin/user')->with('message', "User Can't be Delete");
            } else {
                $destroy = UserLogin::where('id', $id)->delete();
                $vendor_sup = VendorSupervisor::where('vendor_id', $id)->delete();
                $venemp = VendorEmployeeDetails::where('userlogins_id', $id)->delete();
                $shut = ShutdownChild::where('userlogins_id', $id)->delete();
                return redirect('admin/user')->with('message', 'Record Successfully Deleted!!');
            }
        }
    }

    public function show_pwd()
    {

        return view('admin.users.show_password');
    }
    public function UpdatePwd(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'old_pwd' => 'required|min:5|max:50',
            'new_pwd' => 'required|min:6|max:15|required_with:confirm_pwd|same:confirm_pwd',
            'confirm_pwd' => 'required|min:6|max:15'
        ]);

        $o_pwd = UserLogin::where('id', Session::get('user_idSession'))->first();

        if ($request->old_pwd) {
            if ($o_pwd->password == md5($request->old_pwd)) {

                $update_pwd = UserLogin::where('id', Session::get('user_idSession'))->update([
                    'password' => md5($request->new_pwd),
                ]);

                if ($update_pwd) {
                    return back()->with('message', 'Password Successfully Changed !!');
                } else {
                    return back()->with('message', 'Oops Something Worng......');
                }
            } else {
                return back()->with('message', 'Password Not Matched !!');
            }
        }
    }

    public function UserDepartment($id)
    {
        $toReturn = Department::where('division_id', $id)->get();
        return $toReturn;
    }

    public function ResetPassword($id)
    {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789!@#$%^&*()'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 10) as $k)
            $rand .= $seed[$k];
        $password = $rand;
        // return $password;

        $employee = UserLogin::where('id', $id)->update([
            'password' => md5($password),
        ]);

        $getUserDetails = UserLogin::where('id', $id)->first();
        $user = array(
            'name' => $getUserDetails->name,
            'email' => $getUserDetails->email,
            'vendor_code' => $getUserDetails->vendor_code,
            'password' => $password,
            'subject' => "JAMIPOL App Password"
        );
        if ($employee) {
            Mail::send('admin.users.send_pwd', ['data' => $user], function ($message) use ($user) {
                $message->to($user['email'])
                    ->subject($user['subject']);
                $message->from('web@jamipol.com');
            });
            return redirect('admin/user')->with('message', 'Password Reset,Please Check Your email for password');
        }
    }

    public function getIssuer2($pnumber)
    {
        if ($pnumber) {
            $toReturn = UserLogin::where('user_type', 1)->where('vendor_code', $pnumber)->get();
            return $toReturn;
        }
    }
    public function DeleteSupervisior($id)
    {
        $get_supervisors = VendorSupervisor::where('id', $id)->delete();
        return back()->with('message', 'Deleted');
    }
    public function DeleteGatePass($id)
    {
        $delete = VendorEmployeeDetails::where('id', $id)->delete();
        return back()->with('message', 'Deleted');
    }

    public function DeleteShutDown($id)
    {
        $delete = ShutdownChild::where('id', $id)->delete();
        return back()->with('message', 'Deleted');
    }

    public function getListUsers(Request $request)
    {
        // dd($request->all());
        $divisions = Division::all();
        if (Session::get('user_sub_typeSession') == 3) {
            $divisions = Division::all();
            if ($request->input('division_id') <> '' && $request->input('department_id') <> '' && $request->input('type') == 'Employee') {
                $users = UserLogin::where('user_type', 1)
                    ->where('division_id', $request->input('division_id'))
                    ->where('department_id', $request->input('department_id'))
                    ->orderBy('id', 'desc')->get();
                $vendors = UserLogin::where('user_type', 2)->orderBy('id', 'desc')->get();
            }
            if ($request->input('division_id') <> '' && $request->input('type') == 'Vendor') {
                $users = UserLogin::where('user_type', 1)->orderBy('id', 'desc')->get();
                $vendors = UserLogin::where('user_type', 2)
                    ->where('division_id', $request->input('division_id'))
                    ->where('department_id', $request->input('department_id'))
                    ->orderBy('id', 'desc')->get();
            }
            return view('admin.users.index', compact('users', 'vendors', 'divisions'));
        }
    }
}