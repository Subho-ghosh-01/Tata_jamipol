<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use App\Job;
use App\UserLogin;
use App\Permit;
use App\GatePassDetail;
use Session;
use App\Swp_File;
use App\Hazarde;
use App\Permit_Hazard;
use App\Department;
use App\VendorSupervisor;
use App\JobLinking;
use App\VendorEmployeeDetails;
use Mail;
use DB;


class PermitController extends Controller
{
    
    public function index()
    {
        // return view('admin.request_permits.index');
    }


    public function create()
    {
        $divisions = Division::select('id','name')->get();
        $GatePassDetails  = UserLogin::where('userlogins.id',Session::get('user_idSession'))
                ->where('userlogins.user_type',2)
                ->leftjoin('userlogins_employee_details','userlogins_employee_details.userlogins_id','=','userlogins.id')
                ->select('userlogins_employee_details.employee',
                        'userlogins_employee_details.gatepass',
                        'userlogins_employee_details.designation',
                        'userlogins_employee_details.age')->get();
        // return $users;
        $vendor_supervisors   = VendorSupervisor::where('vendor_id',Session::get('user_idSession'))->get();
        // return $vendor;
        return view('admin.request_permits.create',compact('divisions','vendor_supervisors','GatePassDetails'));
    }

 public function store(Request $request)
    {
        // dd($request->all()); 
        $startDate = $request->start_date;
        $expectedEndDate=date('Y-m-d H:i:s', strtotime($startDate. ' +12 hours'));

        if(Session::get('user_typeSession') == 1){
            $ordernumber = '';
            $orderval  = '';
            $enddt  = 'required|date|after:start_date|before_or_equal:'.$expectedEndDate;
        }
        //vendor
        elseif (Session::get('user_typeSession') == 2) {
            $ordernumber = 'numeric';
            $orderval = 'required';
            $enddt    = 'required|date|after:start_date|before_or_equal:order_validity|before_or_equal:'.$expectedEndDate;
        }

        $request->validate([
            'division_id'       => 'required|numeric',
            'department_id'     => 'required|numeric',
            'order_no'          =>  $ordernumber,
            'order_validity'    =>  $orderval,
            'start_date'        => 'required|date',
            'end_date'          =>  $enddt,
            'job_id'            => 'numeric',
            'issuer_id'         => 'numeric',
            'supervisor_name'   => 'numeric',
            'expiry_date.*'     => 'required|date|after_or_equal:'.$expectedEndDate,
        ],[
            'expiry_date.*'.'after_or_equal' => 'Gate Pass Expired'
        ]);
            
       //Get Job detail according to job id
        $job_details = Job::where('id',$request->job_id)->first();
        $users       = UserLogin::where('id',$request->issuer_id)->first();

        $transdate = date('Y-m-d');
        $month = date('m', strtotime($transdate));
        $year  = date('Y', strtotime($transdate));
        
        $divv = Permit::where('division_id',$request->division_id)
                ->whereYear('created_at', '=', $year)
                ->whereMonth('created_at', '=', $month)
                ->orderBy('id', 'DESC')->first();
        if ($divv)
        {
            $v=$divv->serial_no;
            $v++;
            $serial_no=$v;
        }
        else{
            $serial_no="1"; 
        }
        date_default_timezone_set("Asia/Calcutta"); //India time (GMT+5:30)
        $u_dt =  date('Y-m-d H:i:s'); 
        
        //permit entry
        $permit =  Permit::create([
            'division_id'     =>  $request->division_id,
            'department_id'   =>  $request->department_id,
            'serial_no'       =>  $serial_no,
            'order_no'        =>  $request->order_no,
            'order_validity'  =>  $request->order_validity,
            'start_date'      =>  $request->start_date,
            'end_date'        =>  $request->end_date,
            'job_id'          =>  $request->job_id,
            'welding_gas'     =>  $request->welding_gas ? $request->welding_gas : 'off',
            'riggine'         =>  $request->riggine ? $request->riggine : 'off',
            'working_at_height' =>  $request->working_at_height ? $request->working_at_height : 'off',
            'hydraulic_pneumatic'   =>  $request->hydraulic_pneumatic ? $request->hydraulic_pneumatic : 'off',
            'painting_cleaning'     =>  $request->painting_cleaning ? $request->painting_cleaning : 'off',
            'gas'             =>  $request->gas ? $request->gas : 'off',
            'others'          =>  $request->others ? $request->others : 'off',
            'specify_others'  =>  @$request->specify_others,
            'swp_number'      =>  $job_details->swp_number,
            'high_risk'       =>  $job_details->high_risk,  
            'power_clearance' =>  $job_details->power_clearance,
            'confined_space'  =>  $job_details->confined_space,
            'issuer_id'       =>  $request->issuer_id,
            'post_site_pic'   =>  '',
            'status'          =>  'Requested',
            'entered_by'      =>   Session::get('user_idSession'),
            'job_description' =>   $request->job_description,
            'job_location'    =>   $request->job_location,        
            'permit_req_name' =>   $request->supervisor_name,
            'request_dt'       =>  $u_dt,
            'latlong'                =>    $request->latlong,
            'safe_work'              =>    $request->safe_work,
            'all_person'             =>    $request->all_person,
            'worker_working'         =>    $request->worker_working,
            'all_lifting_tools'      =>    $request->all_lifting_tools,
            'all_safety_requirement' =>    $request->all_safety_requirement,
            'all_person_are_trained'  =>   $request->all_person_are_trained,
            'ensure_the_appplicablle' =>   $request->ensure_the_appplicablle,
        ]);   
        
        //Gate Pass Entry
        $gate_pass_details = array();
        foreach ($request->employee_name as $key => $value) {
            $employee_name   = $request->employee_name[$key];
            $gate_pass_no    = $request->gate_pass_no[$key];
            $designation     = $request->designation[$key];
            $age             = $request->age[$key];
            $expiry_date     = $request->expiry_date[$key];
            $intime         = $request->intime[$key];
            $type           = 'New';
            $temp_array = array(
                'permit_id'     => $permit->id, 
                'employee_name' => $employee_name, 
                'gate_pass_no'  => $gate_pass_no,
                'designation'   => $designation,
                'age'           => $age,
                'expirydate'    => $expiry_date,
                'intime'        => $intime,
                'type'          => $type,
            );
            array_push($gate_pass_details,$temp_array);
        }   
        GatePassDetail::insert($gate_pass_details);

        // 1st
        $dd1=$request->six_directional1;
        if($dd1 !="null")
        {
            if ($request->haz1 == "other1")
            {
                $haz1=$request->other_haz1;
                $pre1=$request->other_pre1;
            }
            else
            {
                $haz1=$request->haz1;
                $pre1=$request->pre1;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional1,
                'hazard'        =>  $haz1,
                'precaution'    =>  $pre1,
                'permit_id'     =>  $permit->id
            ]);          
        }

        // 2
        $dd2=$request->six_directional2;
        if($dd2 !="null")
        {
            if ($request->haz2 == "other2")
            {
                $haz2=$request->other_haz2;
                $pre2=$request->other_pre2;
            }
            else
            {
                $haz2=$request->haz2;
                $pre2=$request->pre2;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional2,
                'hazard'        =>  $haz2,
                'precaution'    =>  $pre2,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 3
        $dd3=$request->six_directional3;
        if($dd3 !="null")
        {
            if ($request->haz3 == "other3")
            {
                $haz3=$request->other_haz3;
                $pre3=$request->other_pre3;
            }
            else
            {
                $haz3=$request->haz3;
                $pre3=$request->pre3;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional3,
                'hazard'        =>  $haz3,
                'precaution'    =>  $pre3,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 4
        $dd4=$request->six_directional4;
        if($dd4 !="null")
        {
            if ($request->haz4 == "other4")
            {
                $haz4=$request->other_haz4;
                $pre4=$request->other_pre4;
            }
            else
            {
                $haz4=$request->haz4;
                $pre4=$request->pre4;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional4,
                'hazard'        =>  $haz4,
                'precaution'    =>  $pre4,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 5
        $dd5=$request->six_directional5;
        if($dd5 !="null")
        {
            if ($request->haz5 == "other5")
            {
                $haz5=$request->other_haz5;
                $pre5=$request->other_pre5;
            }
            else
            {
                $haz5=$request->haz5;
                $pre5=$request->pre5;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional5,
                'hazard'        =>  $haz5,
                'precaution'    =>  $pre5,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 6
        $dd6=$request->six_directional6;
        if($dd6 !="null")
        {
            if ($request->haz6 == "other6")
            {
                $haz6=$request->other_haz6;
                $pre6=$request->other_pre6;
            }
            else
            {
                $haz6=$request->haz6;
                $pre6=$request->pre6;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional6,
                'hazard'        =>  $haz6,
                'precaution'    =>  $pre6,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 7
        $dd7=$request->six_directional7;
        if($dd7 !="null")
        {
            if ($request->haz7 == "other7")
            {
                $haz7=$request->other_haz7;
                $pre7=$request->other_pre7;
            }
            else
            {
                $haz7=$request->haz7;
                $pre7=$request->pre7;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional7,
                'hazard'        =>  $haz7,
                'precaution'    =>  $pre7,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 8
        $dd8=$request->six_directional8;
        if($dd8 !="null")
        {
            if ($request->haz8 == "other8")
            {
                $haz8=$request->other_haz8;
                $pre8=$request->other_pre8;
            }
            else
            {
                $haz8=$request->haz8;
                $pre8=$request->pre8;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional8,
                'hazard'        =>  $haz8,
                'precaution'    =>  $pre8,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 9
        $dd9=$request->six_directional9;
        if($dd9 !="null")
        {
            if ($request->haz9 == "other9")
            {
                $haz9=$request->other_haz9;
                $pre9=$request->other_pre9;
            }
            else
            {
                $haz9=$request->haz9;
                $pre9=$request->pre9;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional9,
                'hazard'        =>  $haz9,
                'precaution'    =>  $pre9,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 10
        $dd10=$request->six_directional10;
        if($dd10 !="null")
        {
            if ($request->haz10 == "other10")
            {
                $haz10=$request->other_haz10;
                $pre10=$request->other_pre10;
            }
            else
            {
                $haz10=$request->haz10;
                $pre10=$request->pre10;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional10,
                'hazard'        =>  $haz10,
                'precaution'    =>  $pre10,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 11
        $dd11=$request->six_directional11;
        if($dd11 !="null")
        {
            if ($request->haz11 == "other11")
            {
                $haz11=$request->other_haz11;
                $pre11=$request->other_pre11;
            }
            else
            {
                $haz11=$request->haz11;
                $pre11=$request->pre11;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional11,
                'hazard'        =>  $haz11,
                'precaution'    =>  $pre11,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 12
        $dd12=$request->six_directional12;
        if($dd12 !="null")
        {
            if ($request->haz12 == "other12")
            {
                $haz12=$request->other_haz12;
                $pre12=$request->other_pre12;
            }
            else
            {
                $haz12=$request->haz12;
                $pre12=$request->pre12;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional12,
                'hazard'        =>  $haz12,
                'precaution'    =>  $pre12,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 13
        $dd13=$request->six_directional13;
        if($dd13 !="null")
        {
            if ($request->haz13 == "other13")
            {
                $haz13=$request->other_haz13;
                $pre13=$request->other_pre13;
            }
            else
            {
                $haz13=$request->haz13;
                $pre13=$request->pre13;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional13,
                'hazard'        =>  $haz13,
                'precaution'    =>  $pre13,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 14
        $dd14=$request->six_directional14;
        if($dd14 !="null")
        {
            if ($request->haz14 == "other14")
            {
                $haz14=$request->other_haz14;
                $pre14=$request->other_pre14;
            }
            else
            {
                $haz14=$request->haz14;
                $pre14=$request->pre14;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional14,
                'hazard'        =>  $haz14,
                'precaution'    =>  $pre14,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 15
        $dd15=$request->six_directional15;
        if($dd15 !="null")
        {
            if ($request->haz15 == "other15")
            {
                $haz15=$request->other_haz15;
                $pre15=$request->other_pre15;
            }
            else
            {
                $haz15=$request->haz15;
                $pre15=$request->pre15;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional15,
                'hazard'        =>  $haz15,
                'precaution'    =>  $pre15,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 16
        $dd16=$request->six_directional16;
        if($dd16 !="null")
        {
            if ($request->haz16 == "other16")
            {
                $haz16=$request->other_haz16;
                $pre16=$request->other_pre16;
            }
            else
            {
                $haz16=$request->haz16;
                $pre16=$request->pre16;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional16,
                'hazard'        =>  $haz16,
                'precaution'    =>  $pre16,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 17
        $dd17=$request->six_directional17;
        if($dd17 !="null")
        {
            if ($request->haz17 == "other17")
            {
                $haz17=$request->other_haz17;
                $pre17=$request->other_pre17;
            }
            else
            {
                $haz17=$request->haz17;
                $pre17=$request->pre17;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional17,
                'hazard'        =>  $haz17,
                'precaution'    =>  $pre17,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 18
        $dd18=$request->six_directional18;
        if($dd18 !="null")
        {
            if ($request->haz18 == "other18")
            {
                $haz18=$request->other_haz18;
                $pre18=$request->other_pre18;
            }
            else
            {
                $haz18=$request->haz18;
                $pre18=$request->pre18;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional18,
                'hazard'        =>  $haz18,
                'precaution'    =>  $pre18,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 19
        $dd19=$request->six_directional19;
        if($dd19 !="null")
        {
            if ($request->haz19 == "other19")
            {
                $haz19=$request->other_haz19;
                $pre19=$request->other_pre19;
            }
            else
            {
                $haz19=$request->haz19;
                $pre19=$request->pre19;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional19,
                'hazard'        =>  $haz19,
                'precaution'    =>  $pre19,
                'permit_id'     =>  $permit->id
            ]);          
        }
        // 20
        $dd20=$request->six_directional20;
        if($dd20 !="null")
        {
            if ($request->haz20 == "other20")
            {
                $haz20=$request->other_haz20;
                $pre20=$request->other_pre20;
            }
            else
            {
                $haz20=$request->haz20;
                $pre20=$request->pre20;  
            }

            $sdh =  Permit_Hazard::create([
                'dir'           =>  $request->six_directional20,
                'hazard'        =>  $haz20,
                'precaution'    =>  $pre20,
                'permit_id'     =>  $permit->id
            ]);          
        }


        $startDate= date('d-m-Y h:i:s A', strtotime($request->start_date));
        $endDate= date('d-m-Y h:i:s A', strtotime($request->end_date));
        $workingname = UserLogin::where('id',Session::get('user_idSession'))->first();
        $permitlatest = Permit::where('id',$permit->id)->first();

        $cc    = $permitlatest->created_at;
        $month = date('m-Y', strtotime($cc));

        $abb = Division::where('id',$permitlatest->division_id)->first();
        $generate_serial = @$abb->abbreviation ."/". @$month ."/". @$permitlatest->serial_no;

        $user = array('email'           => $users->email,
                        'issuer_name'   => @$users->name,
                        'subject'       => "Notification! Permit Pending for Approval",
                        'start'         => @$startDate,
                        'end'           => @$endDate,
                        'ordernumber'   => @$request->order_no,
                        'jobcategory'         => @$job_details->job_title,
                        'working_agency'      => @$workingname->name,
                        'working_vendor_code' => @$workingname->vendor_code,
                        'serial_no'           => @$generate_serial,
                        'condition'           =>  "RequesterSendEmail"
                    );
        if($permit){
            Mail::send('admin.request_permits.notification',['data' => $user],function($message) use ($user){
                $message->to($user['email'])
                        ->subject($user['subject']);
                $message->from('web@jamipol.com');
            });
        
            return back()->with('message','Permit Added Suceessfully/Update Notification send to your Executing Agency');
        }
        else{
            return back()->with('message','Ooops... Error While Adding Permit');
        }
    }

   

    public function show(Permit $permit)
    {
    }


    public function edit(Permit $permit)
    {
    }

    
    public function update(Request $request, Permit $permit)
    {
    }

    

    public function destroy(Permit $permit)
    {
    }


    //get Department Details Ajax call
    public function getDepartment($id){
        $depart = Department::where('division_id',$id)->get();
        return $depart;
    }

    


    public function getSwpNumber(Request $request,$id){
        $toReturn['swp_num'] = Job::where('id',$id)->value('swp_number');

        $toReturn['swp_f']  = Swp_File::where('job_id',$id)->get();
		
		//$binary = $swp['swp_file'];
        //$clean = trim($binary, "public/");
		//$toReturn['swp_f']   = $clean;
		 
        return $toReturn;

    }
    

    public function getSixDirectionalView(Request $request,$id)
    {
        $hazared_all = Job::where('jobs.id',$id)
                        ->leftjoin ('hazardes','jobs.id','=','hazardes.job_id')
                        ->select('jobs.*','hazardes.*')->get();
        $toReturn = "";
        $toReturn .= "<table class='table'>";
        $toReturn .= "<tbody>";
                $toReturn .= "<tr>";
                    $toReturn .= "<th>Direction</th>";
                    $toReturn .= "<th>Hazareds</th>";
                    $toReturn .= "<th>Precaution</th>";
                $toReturn .= "</tr>";

            foreach($hazared_all as $key => $value){
                if($hazared_all[$key]->direction == 'North'){
                    $toReturn .= "<tr>";
                            $toReturn .= "<td>North</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->hazarde."</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->precaution."</td>";
                    $toReturn .= "</tr>";
                }
            }
            foreach($hazared_all as $key => $value){
                if($hazared_all[$key]->direction == 'South'){
                    $toReturn .= "<tr>";
                            $toReturn .= "<td>South</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->hazarde."</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->precaution."</td>";
                    $toReturn .= "</tr>";
                }
            }
            foreach($hazared_all as $key => $value){
                if($hazared_all[$key]->direction == 'East'){
                        $toReturn .= "<tr>";
                            $toReturn .= "<td>East</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->hazarde."</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->precaution."</td>";
                        $toReturn .= "</tr>";
                }
            }
            foreach($hazared_all as $key => $value){
                if($hazared_all[$key]->direction == 'West'){
                        $toReturn .= "<tr>";
                            $toReturn .= "<td>West</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->hazarde."</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->precaution."</td>";
                        $toReturn .= "</tr>";
                }
            }
            foreach($hazared_all as $key => $value){
                if($hazared_all[$key]->direction == 'Top'){
                        $toReturn .= "<tr>";
                            $toReturn .= "<td>Top</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->hazarde."</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->precaution."</td>";
                        $toReturn .= "</tr>";
                }
            }
            foreach($hazared_all as $key => $value){
                if($hazared_all[$key]->direction == 'Buttom'){
                        $toReturn .= "<tr>";
                            $toReturn .= "<td>Buttom</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->hazarde."</td>";
                            $toReturn .= "<td>".$hazared_all[$key]->precaution."</td>";
                        $toReturn .= "</tr>";
                }
            }

        $toReturn .= "</tbody>";
        $toReturn .= "</table>";
        return $toReturn;
    }


    // GET THE USER DEATILS FORM SELECT OF DIVISION
    public function getIssuer(Request $request,$id)
    {
        //$user = UserLogin::where(['user_type' => 1,'department_id' => $id])
		
		
            $user = UserLogin::where(['user_type' => 1,'division_id' => $id])->where('id', '!=' ,Session::get('user_idSession'))->where('active','Yes')->get();
        return $user;
    }



    //get the details of hazareds 
    public function getHazard($job_id,$direction)
    {
        $toReturn = Hazarde::where('job_id',$job_id)->where('direction',$direction)->get();
        return $toReturn;
    }

    //get the details of hazareds 
    public function GetJob($divi,$dept)
    {  
        $joblinking = Job::leftjoin('jobs_linking','jobs_linking.job_id','=','jobs.id')
                ->where('jobs_linking.division_id',$divi)
                ->where('jobs_linking.department_id',$dept)
                ->select('jobs.id','jobs.job_title')->get();
        return $joblinking;

        //$toReturn = Job::where('id',$joblinking[0]->job_id)->get();
        //return $toReturn;
    } 

    /*public function get(Request $request,$id,$entername){
        $toReturn = array();
        // $gate_pass_emp = $request->term;
        if(Session::get('user_typeSession') == 1 && Session::get('user_sub_typeSession') == 3){
            $data = VendorEmployeeDetails::where('userlogins_employee_details.employee', 'like', '%' .  $entername  . '%')
            ->select('userlogins_employee_details.*')->get();
        }
        elseif (Session::get('user_typeSession') == 1 && Session::get('user_sub_typeSession') == 1) {
            $vendors = UserLogin::where('user_type',2)
                        ->where('division_id',Session::get('user_DivID_Session'))->get();
            $vendorArray = array();
            foreach ($vendors as $vendor) {
                $vendorArray[] = $vendor->id;
            }
            $data = VendorEmployeeDetails::where('userlogins_employee_details.employee', 'like', '%' .  $entername  . '%')
            ->whereIn('userlogins_employee_details.userlogins_id', $vendorArray)
            ->select('userlogins_employee_details.*')->get();
        }
        elseif (Session::get('user_typeSession') == 2) {
            $data = VendorEmployeeDetails::where('userlogins_employee_details.employee', 'like', '%' .  $entername  . '%')
            ->where('userlogins_employee_details.userlogins_id',$id)
            ->select('userlogins_employee_details.*')->get();
        }elseif(Session::get('user_typeSession') == 1){
			 $data = VendorEmployeeDetails::where('userlogins_employee_details.employee', 'like', '%' .  $entername  . '%')
            ->select('userlogins_employee_details.*')->get();
		}else{
$data = VendorEmployeeDetails::where('userlogins_employee_details.employee', 'like', '%' .  $entername  . '%')
            ->where('userlogins_employee_details.userlogins_id',$id)
            ->select('userlogins_employee_details.*')->get();
		}
        $toReturn['list'] = $data;
        echo  json_encode($toReturn);
    }*/
	
	
	
	 public function get(Request $request,$id,$entername){
        $toReturn = array();
        // $gate_pass_emp = $request->term;
		$date = date('Y-m-d');
		if(Session::get('user_DivID_Session') == '2' || Session::get('user_sub_typeSession')== '3'){
        if(Session::get('user_typeSession') == 1 && Session::get('user_sub_typeSession') == 3){
            $data = DB::table('Clms_gatepass')->where('name', 'like', '%' .  $entername  . '%')->where('status','Pending_for_security')->where('valid_till', '>' ,$date)->get();
        }
        elseif (Session::get('user_typeSession') == 1 && Session::get('user_sub_typeSession') == 1) {
            $vendors = UserLogin::where('user_type',2)
                        ->where('division_id',Session::get('user_DivID_Session'))->get();
            $vendorArray = array();
            foreach ($vendors as $vendor) {
                $vendorArray[] = $vendor->id;
            }
           
			
			$data = DB::table('Clms_gatepass')->where('name', 'like', '%' .  $entername  . '%')->where('status','Pending_for_security')->where('valid_till', '>' ,$date)->where('created_by',$vendorArray)->get();
        }
        elseif (Session::get('user_typeSession') == 2) {
		$data = DB::table('Clms_gatepass')->where('name', 'like', '%' .  $entername  . '%')->where('status','Pending_for_security')->where('valid_till', '>' ,$date)->where('created_by',$id)->get();
           
        }elseif(Session::get('user_typeSession') == 1){
			 $data = DB::table('Clms_gatepass')->where('name', 'like', '%' .  $entername  . '%')->where('status','Pending_for_security')->where('valid_till', '>' ,$date)->get();
		}else{
$data = DB::table('Clms_gatepass')->where('name', 'like', '%' .  $entername  . '%')->where('status','Pending_for_security')->where('valid_till', '>' ,$date)->where('created_by',$id)->get();
		}
        $toReturn['list'] = $data;
		
		}else{
			if(Session::get('user_typeSession') == 1 && Session::get('user_sub_typeSession') == 3){
            $data = VendorEmployeeDetails::where('userlogins_employee_details.employee', 'like', '%' .  $entername  . '%')
            ->select('userlogins_employee_details.*')->get();
        }
        elseif (Session::get('user_typeSession') == 1 && Session::get('user_sub_typeSession') == 1) {
            $vendors = UserLogin::where('user_type',2)
                        ->where('division_id',Session::get('user_DivID_Session'))->get();
            $vendorArray = array();
            foreach ($vendors as $vendor) {
                $vendorArray[] = $vendor->id;
            }
            $data = VendorEmployeeDetails::where('userlogins_employee_details.employee', 'like', '%' .  $entername  . '%')
            ->whereIn('userlogins_employee_details.userlogins_id', $vendorArray)
            ->select('userlogins_employee_details.*')->get();
        }
        elseif (Session::get('user_typeSession') == 2) {
            $data = VendorEmployeeDetails::where('userlogins_employee_details.employee', 'like', '%' .  $entername  . '%')
            ->where('userlogins_employee_details.userlogins_id',$id)
            ->select('userlogins_employee_details.*')->get();
        }elseif(Session::get('user_typeSession') == 1){
			 $data = VendorEmployeeDetails::where('userlogins_employee_details.employee', 'like', '%' .  $entername  . '%')
            ->select('userlogins_employee_details.*')->get();
		}else{
$data = VendorEmployeeDetails::where('userlogins_employee_details.employee', 'like', '%' .  $entername  . '%')
            ->where('userlogins_employee_details.userlogins_id',$id)
            ->select('userlogins_employee_details.*')->get();
		}
        $toReturn['list'] = $data;
		}
        echo  json_encode($toReturn);
    }
}
