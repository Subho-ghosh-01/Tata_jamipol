<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\UserLogin;
use App\Permit;
use Session;
use App\GatePassDetail;
use App\PowerClearence;
use App\ConfinedSpace;
use File;
use App\PermitCancel;
use App\Swp_File;
use App\Permit_Hazard;
use App\Hazarde;
use App\Division;
use App\Job;
use App\VendorSupervisor;
use Mail;
use App\Department;
use DB;
use Carbon\Carbon;
use App\ShutdownChild;
use App\Exports\BulkExport;
use App\VendorEmployeeDetails;
use App\OtherIsolation;
use App\WorkOrder;
use App\RenewPermit;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportWorkOrder;
use App\Imports\ImportGatePass;
use App\Exports\ExportGatePass;
use App\PowerCutting;
use App\PowerGetting;



class ListPermitController extends Controller
{
    public function index()
    {
        $my_pending_permits = Permit::where('entered_by',Session::get('user_idSession'))
                                ->orderBy('id', 'DESC')->get();

        $issuer_datas   = Permit::where('issuer_id',Session::get('user_idSession'))
                                ->where('status','Requested')
                                ->orWhere('area_clearence_id',Session::get('user_idSession'))
                                ->where('status','Parea')
                                ->orderBy('id', 'DESC')->get();

		  $issued_permits   = Permit::where('issuer_id',Session::get('user_idSession'))
                        ->where('status','Issued')
                        ->orWhere('area_clearence_id',Session::get('user_idSession'))
                        ->where('status','Issued')->get();
		
		
		
		
     
        $pending_for_returns = Permit::where('issuer_id',Session::get('user_idSession'))
                                ->where('status','Issued')
                                ->where('return_status','Pending')
                                ->orwhere('return_status','Power_Getting')
                                ->where('ppg_userid',Session::get('user_idSession'))
                                ->orwhere('return_status','Pending_area')
                                ->where('area_clearence_id',Session::get('user_idSession'))
                                ->orderBy('id','DESC')->get(); 


        $renew_lists =  RenewPermit::where('issuer_id',Session::get('user_idSession'))
                            ->where('status','Pending_Renew_Issuer')
                            ->orWhere('area_id',Session::get('user_idSession'))
                            ->where('status','Pending_Renew_Area')
                            ->orderBy('id', 'DESC')->get();
        // echo $renew_lists;
        // exit;


        $expiryPermits = Permit::where('issuer_id',Session::get('user_idSession'))
                        ->where('status','Issued')
                        ->where('end_date',"<",date('Y-m-d H:i:s'))
                        ->orderBy('id', 'DESC')
                        ->get();

        $powerCuttings = Permit::Where('ppc_userid',Session::get('user_idSession'))
                        ->where('status','PPc')->orderBy('id', 'DESC')->get();

        $powerGettings = Permit::select('permits.*')
                        ->where('ppg_userid',Session::get('user_idSession'))
                        ->where('return_status','PPg')
                        ->orderBy('id', 'DESC')->get();
                    


        return view('admin.list_permits.index',compact('my_pending_permits','issuer_datas',
                    'issued_permits','pending_for_returns',
                    'renew_lists','expiryPermits','powerCuttings','powerGettings'));
    }

    

    public function create()
    {
    }

    


    public function store(Request $request)
    {
        dd($request->all());
    }

    


    public function show($id)
    {}

    public function edit($id)
    {  
        $id = \Crypt::decrypt($id);
         //return $id;
        //get Permit Data According To division
        $permit_division_datas = Permit::where('permits.id',$id)
                ->leftjoin('divisions','permits.division_id','=','divisions.id')
                ->select('divisions.id as divisionId','divisions.name as divisionName',
                'permits.order_no as permitOrder','permits.order_validity as permitOrderValidity',
                'permits.start_date as startDate','permits.end_date as endDate',
                'permits.issuer_id as permitIssuerID','permits.power_clearance as PermitPowerClearance',
                'permits.confined_space as PermitConfinedSpace', 'permits.high_risk as PermitHighRisk',
                'permits.latlong as PermitlatLong','permits.safe_work as PermitSafeWork',
                'permits.all_person as PermitAll_person','permits.worker_working as permitWorkerWorking',
                'permits.all_lifting_tools as PermitAll_lifting_tools',
                'permits.all_safety_requirement as permitAll_safety_requirement',
                'permits.all_person_are_trained as PermitAll_person_are_trained',
                'permits.ensure_the_appplicablle as permitEnsure_the_appplicablle',
                'permits.power_clearance_number as PermitPower_clearance_number',
                'permits.area_clearence_required as PermitArea_clearence',
                'permits.area_clearence_id as PermitArea_clearenceId',
                'permits.status as PermitStatus','permits.post_site_pic as PermitSitePic',
                'permits.job_description as JobDescription','permits.job_location as JobLocation',
                'permits.permit_req_name as permitRequestname','permits.s_instruction as s_instruction',
                'permits.department_id as department_id','permits.vlevel as vlevel',
                'permits.issuer_power as issuer_power','permits.rec_power as rec_power',
                'permits.electrical_license_issuer',
                'permits.validity_date_issuer','permits.electrical_license_rec',
                'permits.validity_date_rec','permits.issuer_id','permits.job_id',
                'permits.welding_gas','permits.riggine','permits.working_at_height','permits.hydraulic_pneumatic',
                'permits.painting_cleaning','permits.gas','permits.others','permits.specify_others','permits.other_isolation',
                'permits.pc_id','permits.ppc_userid','permits.executing_lock','permits.working_lock'
            )->get();

       
        $job_datas   = Permit::where('permits.id',$id)
                ->leftjoin('jobs','permits.job_id','=','jobs.id')
                ->select('jobs.id as jobId','jobs.job_title as jobTitle',
                'jobs.swp_number as jobSwpNumber')->get();
            //return $job_datas;

        $permit_hazards = Permit_Hazard::where('permit_id',$id)->get();

        $swp_files   = Permit::where('permits.id',$id)
                        ->leftjoin('swp__files','permits.job_id','=','swp__files.job_id')
                        ->select('swp__files.swp_file')->get();


        $gate_pass_details = GatePassDetail::where('permit_id',$id)->get();
		//return $gate_pass_details;
        $users             = UserLogin::where('user_type', 1)->get();
        $power_clearances  = PowerClearence::where('permit_id',$id)->get();
        $confined_spaces   = ConfinedSpace::where('permit_id',$id)->get();
        $otherisolation    = OtherIsolation::where('permit_id',$id)->get();
        
        $forAreaClearence  = UserLogin::where('division_id',@$permit_division_datas[0]->divisionId)->select('id','name')->get();
        $powerCuttingUsers = UserLogin::where(['division_id' => @$permit_division_datas[0]->divisionId,'user_type' => '1','power_cutting' => "Yes"])->select('id','name')->get();

    
        $P = Permit::select('id','vlevel','issuer_power',
            'electrical_license_issuer','validity_date_issuer','rec_power',
            'electrical_license_rec','validity_date_rec','created_at','division_id',
            'serial_no','power_cutting_remarks')
            ->where('permits.id',$id)->first();
        // echo $P;
        // exit;
        
        return view('admin.list_permits.edit',compact('permit_division_datas',
        'job_datas','gate_pass_details','id','users','power_clearances',
        'confined_spaces','swp_files','permit_hazards','forAreaClearence',
        'otherisolation','powerCuttingUsers','P'));
    
    }

    
    public function update(Request $request, $id)
    {             
        // dd($request->all());
        $permitdata =  Permit::where('id',$id)->first();
        date_default_timezone_set("Asia/Calcutta");
        $u_dt =  date('Y-m-d H:i:s'); 
       
        ($request->area_clearance_req == "on") ? $area_req = "on" : $area_req="off";
       
        //1st  Phase
        if($permitdata->status == 'Requested' && $permitdata->power_clearance == 'on' && $permitdata->power_clearance_number == ''){
            echo "1";
            $status       ='PPc';
            $colname      ='issuer_dt';
            $other_Isolation = $request->other_Isolation;
        }

        elseif($permitdata->status == 'Requested' && $area_req =='off' && $permitdata->power_clearance == 'off'){
            echo "2";
            $status       ='Issued';
            $colname      ='issuer_dt';
            $other_Isolation = $request->other_Isolation;
        }

        elseif($permitdata->status == 'Requested' && $area_req =='on' && $permitdata->power_clearance == 'off'){
            echo "3";
            $status       ='Parea';
            $colname      ='issuer_dt';
            $other_Isolation = $request->other_Isolation;
        }

        //2nd Phase
        elseif($permitdata->status == 'Requested' && $area_req =='off'  && $permitdata->power_clearance == 'on' && $permitdata->power_clearance_number != ''){
            echo "4";
            $status       ='Issued';
            $colname      ='issuer_dt2';
            $other_Isolation = $request->other_Isolation;
        }
        elseif($permitdata->status == 'Requested' &&  $area_req =='on'  && $permitdata->power_clearance == 'on' && $permitdata->power_clearance_number != ''){
            echo "5";
            $status       ='Parea';
            $colname      ='issuer_dt2';
            $other_Isolation = $request->other_Isolation;
        }
        elseif($permitdata->status == 'Parea'){
           echo "6";
            $status       = 'Issued';
            $colname      = 'area_clearence_dt';
            $other_Isolation = $permitdata->other_isolation;
        }

        if($request->hasFile('post_site_pic')){
            $location = 'public/documents/site_pics/';
            $extension = '.'.$request->post_site_pic->getClientOriginalExtension();
            $name = basename($request->post_site_pic->getClientOriginalName(),$extension).time();
            $name = $name.$extension;
            $path = $request->post_site_pic->move($location,$name);
            $name = $location.$name;
            $old_name = Permit::where('id',$id)->get();
            if(file_exists($old_name[0]->post_site_pic)){
                unlink($old_name[0]->post_site_pic);
            }
        }
        else{
            $old_name = Permit::where('id',$id)->get();
            $name = $old_name[0]->post_site_pic;
        }
    
        if($request->status == "Prcv"){
            $getPermitData = array(
                'status'                 =>    $status,
                $colname                 =>    $u_dt,
            );   
        }
        else{
            $getPermitData = array(
                'area_clearence_required' =>   @$area_req,
                'area_clearence_id'      =>    @$request->area_clearence_id,
                'ppc_userid'             =>    @$request->power_cutting_userid,
                'status'                 =>    $status,
                'post_site_pic'          =>    $name,
                $colname                 =>    $u_dt,
                's_instruction'          =>    $request->s_instruction,
                'other_isolation'        =>    $other_Isolation,
                'executing_lock'         =>    @$request->excuting_personal_lock,
                'working_lock'           =>    @$request->working_personal_lock
            );
        }


        //update the permit table
        $permitupdate = Permit::where('id',$id)->update($getPermitData);

        //Other clearance
        if($request->other_Isolation == 'yes' && $request->positive_other){
            foreach ($request->positive_other as $key => $value) {
                OtherIsolation::insert([
                    'permit_id'         => $id,
                    'positive_other'    => $request->positive_other[$key],
                    'equipment_other'   => $request->equipment_other[$key],
                    'location_other'    => $request->location_other[$key]
                ]);
            }   
        } 

        //Confined Space Details
        if($permitdata->confined_space == 'on'){
            foreach ($request->clearance_no as $key => $value) {
                $confined_unique_id   = $request->c_id[$key];

                $confined_space_id    = ConfinedSpace::where('id',$confined_unique_id)->first();

                // return $confined_space_id->id;
                if($confined_space_id != null){
                    ConfinedSpace::where('id',$confined_unique_id)->update([
                        'clearance_no'     => $request->clearance_no[$key],
                        'depth'            => $request->depth[$key],
                        'location'         => $request->confined_location[$key],
                    ]);
                }
                else{
                    ConfinedSpace::insert([
                        'permit_id'     => $id,
                        'clearance_no'  => $request->clearance_no[$key],
                        'depth'         => $request->depth[$key],
                        'location'      => $request->confined_location[$key]
                    ]);
                } 
            }  
        } 
   


        if($request->six_directional1 != ''){
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
                    'permit_id'     =>  $id
                ]);          
            }
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
            ]);          
        }


        //-------------------------------  Email Condition  ----------------------------------
        $startDate= date('d-m-Y h:i:s A', strtotime($permitdata->start_date));
        $endDate= date('d-m-Y h:i:s A', strtotime($permitdata->end_date));
        $workingname = UserLogin::where('id',$permitdata->entered_by)->first();
        $job_details = Job::where('id',$permitdata->job_id)->first();

        $cc    = $permitdata->created_at;
        $month = date('m-Y', strtotime($cc));
        $abb = Division::where('id',$permitdata->division_id)->first();
        $generate_serial = @$abb->abbreviation ."/". @$month ."/". @$permitdata->serial_no;

        if($status == 'Issued'){
            @$departmentAdmin = UserLogin::where('division_id',$permitdata->division_id)->where('user_sub_type',1)->get();
            $cc = array();
            foreach($departmentAdmin as $AdminEmail){
                $cc[] = $AdminEmail->email;
            }
            $user = array('email' => @$workingname->email,
                'cc'              => @$cc,
                'issuer_name'     => @$Area->name,
                'subject'         => "Notification! Permit Number " .@$generate_serial. " is Approved.",
                'start'           => @$startDate,
                'end'             => @$endDate,
                'ordernumber'     => @$permitdata->order_no,
                'jobcategory'         => @$job_details->job_title,
                'working_agency'      => @$workingname->name,
                'working_vendor_code' => @$workingname->vendor_code,
                'serial_no'           => @$generate_serial,
                'condition'           => "IssuedSendEmail"
            );
        }
        else if($status == 'Parea'){
            $Area = UserLogin::where('id',$request->area_clearence_id)->first();
            $user = array('email'  => @$Area->email,
                'issuer_name'   => @$Area->name,
                'subject'       => "Notification! Permit Pending for Approval",
                'start'         => @$startDate,
                'end'           => @$endDate,
                'ordernumber'   => @$permitdata->order_no,
                'jobcategory'         => @$job_details->job_title,
                'working_agency'      => @$workingname->name,
                'working_vendor_code' => @$workingname->vendor_code,
                'serial_no'           => @$generate_serial,
                'condition'           =>  "AreaSendEmail"
            );
        }

        if($permitupdate){
            if($status == 'Issued'){
                Mail::send('admin.request_permits.notification',['data' => $user],function($message) use ($user){
                    $message->to($user['email'])
                            ->cc($user['cc'])
                            ->subject($user['subject']);
                    $message->from('web@jamipol.com');
                });
            }
            else if($status == 'Parea'){
                Mail::send('admin.request_permits.notification',['data' => $user],function($message) use ($user){
                    $message->to($user['email'])
                            ->subject($user['subject']);
                    $message->from('web@jamipol.com');
                });
            }

            return redirect('admin/list_permit')->with('message', 'Permit Update Suceessfully');
        }
        else{
            return back()->with('message','Ooops... Error While Update Permit');
        }
    }


    public function destroy($id)
    { }

    public function getvalidity($ordernumber)
    {
        if(Session::get('user_typeSession') == 1){
            $getorder = WorkOrder::where('order_code',$ordernumber)->first();
        }
        else if(Session::get('user_typeSession') == 2){
            $getorder = WorkOrder::where('vendor_code',Session::get('vcode2'))->where('order_code',$ordernumber)->first();            
        }
        // echo date('Y-m-d',strtotime($getorder->order_validity));
        // exit;
        $date['date'] = date('Y-m-d',strtotime($getorder->order_validity));
        return json_encode($date);

    }

  
    public function cancelPermit(Request $request)
    {
        // return $request;
        // dd($request->all());
        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        $cancelDate =  date('Y-m-d H:i:s');

        $permit_id =  Permit::where('id',$request->pid)->first();
        // return $permit_id->id;
        if($request->hasFile('img1')){
            $location = 'public/documents/cancel-permits/';
            $extension = '.'.$request->img1->getClientOriginalExtension();
            $name = basename($request->img1->getClientOriginalName(),$extension).time();
            $name = $name.$extension;
            $path = $request->img1->move($location,$name);
            $img1 = $location.$name;
        }else{
		$img1 ='';
		}
        if($request->hasFile('img2')){
            $location = 'public/documents/cancel-permits/';
            $extension = '.'.$request->img2->getClientOriginalExtension();
            $name = basename($request->img2->getClientOriginalName(),$extension).time();
            $name = $name.$extension;
            $path = $request->img2->move($location,$name);
            $img2 = $location.$name;
        }else{
		$img2 ='';
		}
        if($request->hasFile('img3')){
            $location = 'public/documents/cancel-permits/';
            $extension = '.'.$request->img3->getClientOriginalExtension();
            $name = basename($request->img3->getClientOriginalName(),$extension).time();
            $name = $name.$extension;
            $path = $request->img3->move($location,$name);
            $img3 = $location.$name;
        }else{
		$img3 ='';
		}

        $cancelPermit = PermitCancel::create([
            'permit_id'              => $permit_id->id,
            'date'                   => $cancelDate,
            'violations_details'     => $request->violations_details,
            'img1'                   => $img1,
            'img2'                   => $img2,
            'img3'                   => $img3,
            'cancel_by_id'           => Session::get('user_idSession')
        ]);

        $permit_status = Permit::where('id',$request->pid)->update([
            'status'  => "Cancel" 
        ]);

        if($cancelPermit){
            return back()->with('message','You Permit is cancelled');
        }
        else{
            return back()->with('message','Ooops Error While Cancel Permit');
        }
    }

   
    public function getPermitHazard($job_id,$dir)
    {
        $toReturn = Hazarde::where('job_id',$job_id)->where('direction',$dir)->get();
        return $toReturn;
    }


    public function DeleteHazard($id)
    {
        $toReturn = Permit_Hazard::where('id',$id)->delete();
        return back()->with('message','Six Directional Hazard Deleted');
    }


    public function ShowPrint($text,$id)
    {
        // $id = \Crypt::decrypt($id);
        if($text == "text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla"){
            $id = base64_decode($id);
            return view('admin.list_permits.permit',compact('id'));
        }else{
            echo "Don't Be Smart..";
        }
    }
    

    //Show Permit Page when Return 
    public function PermitReturn($idenc)
    {
        $id = \Crypt::decrypt($idenc);
        // echo "$id";
        $permit_division_datas = Permit::where('permits.id',$id)
                ->leftjoin('divisions','permits.division_id','=','divisions.id')
                ->select('divisions.id as divisionId','divisions.name as divisionName',
                'permits.order_no as permitOrder','permits.order_validity as permitOrderValidity',
                'permits.start_date as startDate','permits.end_date as endDate',
                'permits.issuer_id as permitIssuerID','permits.power_clearance as PermitPowerClearance',
                'permits.confined_space as PermitConfinedSpace', 'permits.high_risk as PermitHighRisk',
                'permits.latlong as PermitlatLong','permits.safe_work as PermitSafeWork',
                'permits.all_person as PermitAll_person','permits.worker_working as permitWorkerWorking',
                'permits.all_lifting_tools as PermitAll_lifting_tools',
                'permits.all_safety_requirement as permitAll_safety_requirement',
                'permits.all_person_are_trained as PermitAll_person_are_trained',
                'permits.ensure_the_appplicablle as permitEnsure_the_appplicablle',
                'permits.power_clearance_number as PermitPower_clearance_number',
                'permits.area_clearence_required as PermitArea_clearence',
                'permits.area_clearence_id as PermitArea_clearenceId',
                'permits.status as PermitStatus','permits.post_site_pic as PermitSitePic',
                'permits.return_status as return_status','permits.complete as complete',
                'permits.requester_remark as requester_remark',
                'permits.issuer_remark as issuer_remark','permits.area_return_remark as area_return_remark',
                'permits.department_id as department_id','permits.vlevel as vlevel',
                'permits.issuer_power as issuer_power','permits.rec_power as rec_power',
                'permits.electrical_license_issuer','permits.validity_date_issuer',
                'permits.electrical_license_rec','permits.validity_date_rec',
                'permits.welding_gas','permits.riggine','permits.working_at_height',
                'permits.hydraulic_pneumatic','permits.painting_cleaning','permits.gas',
                'permits.others','permits.specify_others','permits.other_isolation','permits.pc_id',
                'permits.ppg_userid','permits.s_instruction as s_instruction',
                'permits.power_cutting_remarks','permits.pg_ins1','permits.pg_ins2','permits.pg_ins3',
                'permits.pg_number','permits.pg_id','permits.q1','permits.q2',
                'permits.q3','permits.q4','permits.q5_others','permits.executing_lock',
                'permits.working_lock','permits.exe_lock','permits.work_lock',
                'permits.job_description as JobDescription','permits.job_location as JobLocation')->get();

       // return $permit_division_datas;
        $job_datas      = Permit::where('permits.id',$id)
                        ->leftjoin('jobs','permits.job_id','=','jobs.id')
                        ->select('jobs.id as jobId','jobs.job_title as jobTitle',
                                'jobs.swp_number as jobSwpNumber')->get();

        $permit_hazards = Permit_Hazard::where('permit_id',$id)->get();
        // return $permit_hazards;

        $swp_files   = Permit::where('permits.id',$id)
                                ->leftjoin('swp__files','permits.job_id','=','swp__files.job_id')
                                ->select('swp__files.swp_file')->get();

        // return $swp_files;
        $gate_pass_details   = GatePassDetail::where('permit_id',$id)->get();
        // return $gate_pass_details;
        $users  = UserLogin::where('division_id',@$permit_division_datas[0]->divisionId)->get();
        $power_clearances  = PowerClearence::where('permit_id',$id)->get();
        $confined_spaces   = ConfinedSpace::where('permit_id',$id)->get();
        $otherisolation    = OtherIsolation::where('permit_id',$id)->get();
        $powerGettingUsers = UserLogin::where(['division_id' => @$permit_division_datas[0]->divisionId,
                                        'user_type' => '1','power_getting' => "Yes"])
                                        ->select('id','name')->get();
            
        return view('admin.list_permits.return',compact('permit_division_datas',
        'job_datas','gate_pass_details','id','users','power_clearances','confined_spaces',
        'swp_files','permit_hazards','otherisolation','powerGettingUsers'));
    }

    //return Permit
    public function PermitReturnUpdate(Request $request,$id)
    {
        // dd($request->all());
        $getStatus = Permit::where('id',$id)->first();
        date_default_timezone_set("Asia/Calcutta");
        $u_dt =  date('Y-m-d H:i:s');


        ($request->owner_cleck == 'on') ? $OwnerCheck = 'on' : $OwnerCheck = 'off';
        // OK BLOCK
        if($getStatus->power_clearance == 'on'){
            if($getStatus->return_status == '')
            {
                // echo "1";
                $cancel = Permit::where('id',$id)->update([
                    'complete'          => $request->complete1,
                    'requester_remark'  => $request->requester_remark,
                    // 'return_issuer_id'  => $request->return_executing_id,
                    'ppg_userid'        => $request->power_getting_userid,
                    'pg_ins1'           => $request->ins1,
                    'pg_ins2'           => $request->ins2,
                    'pg_ins3'           => $request->ins3,
                    'complete_date'     => $u_dt,
                    'return_status'     => 'PPg'
                ]);
            }
        }
        if($getStatus->power_clearance == 'off')
        {
            if($getStatus->return_status == '')
            {   // echo "2";
                $cancel = Permit::where('id',$id)->update([
                    'complete'          => $request->complete1,
                    'requester_remark'  => $request->requester_remark,
                    // 'return_issuer_id'  => $request->return_executing_id,
                    'pg_ins1'           => $request->ins1,
                    'pg_ins2'           => $request->ins2,
                    'pg_ins3'           => $request->ins3,
                    'complete_date'     => $u_dt,
                    'return_status'     => 'Pending'
                ]);
            }
        }
        if($getStatus->return_status == 'Pending'  &&  $OwnerCheck == 'off')
        {
            // echo "3";
            $cancel = Permit::where('id',$id)->update([
                'issuer_return_date' => $u_dt,
                'issuer_remark'      => $request->issuer_remark,
                'return_status'      => 'Returned',
                'status'             => 'Returned'
            ]);
        }
        elseif($getStatus->return_status == 'Pending'  &&  $OwnerCheck == 'on')
        {
            // echo "4";
            $cancel = Permit::where('id',$id)->update([
                // 'return_area_id'  => $request->return_owner_id,
                'issuer_return_date' => $u_dt,
                'issuer_remark'      => $request->issuer_remark,
                'return_status'      => 'Pending_area',
            ]);
        }

        elseif($getStatus->return_status == 'Pending_area')
        {
            // echo "5";
            $cancel = Permit::where('id',$id)->update([
                'area_return_date'      => $u_dt,
                'area_return_remark'    => $request->area_return_remark,
                'return_status'         => 'Returned',
                'status'                => 'Returned'
            ]);
        }

  $cancel = GatePassDetail::where('permit_id',$id)->update([
             'outtime'    => $request->outtime_time,
        ]); 

        if($cancel){
            return redirect('admin/list_permit')->with('message', 'Permit Returned!');
        }
        else{
            return back()->with('message','Ooops... Error While Cancle Permit');
        }
    }
    
    // get the time to change the requester
    public function PermitEndDate($id)
    {
        $endDate = Permit::where('id',$id)->first(['end_date', 'renew_id_1','division_id']);
        $ExecutingDivision = UserLogin::select('id','name')
                        ->where('division_id',$endDate->division_id)
                        ->where('user_type',1)->get();
        $data=array();
        $data['issuer1']= $ExecutingDivision;
        if(!$endDate->renew_id_1)
        {
            $data['end']=$endDate->end_date;
        }
        else{
            $endDate = RenewPermit::where('permit_id',$id)
                    ->where('status','Approved')->orderBy('id','DESC')->first(['new_time']);
            $data['end']=$endDate->new_time;
        }
        return $data;
    }

    public function ReturnRequester($id)
    {
        $changeStatus = Permit::where('id',$id)->update([
            'status' => "Return_Requester",
        ]);

        if($changeStatus){
            return back()->with('message', 'Return to Working Agency');
        }
        else{
            return back()->with('message','Ooops... Error While Changing Requester');
        }
    }

    public function IssuerChange($id)
    {
        $id        = \Crypt::decrypt($id);
        $permit    = Permit::where('id',$id)->first();
        $divisions = Division::all();
        $jobs      = Job::all();
        $vendor_supervisors   = VendorSupervisor::where('vendor_id',Session::get('user_idSession'))->get();

        $swp_files   = Permit::where('permits.id',$id)
                    ->leftjoin('swp__files','permits.job_id','=','swp__files.job_id')
                    ->select('swp__files.swp_file')->get();
        // return  $swp_files;
        $permit_hazards    = Permit_Hazard::where('permit_id',$id)->get();
        $gate_pass_details = GatePassDetail::where('permit_id',$id)->get();

        // for Edit data  their department
        $users = UserLogin::where('department_id',$permit->department_id)->get();

        // echo  $users;
        // exit;
        return view('admin.list_permits.changeissuer',compact('divisions','jobs','vendor_supervisors',
                    'permit','swp_files','permit_hazards','gate_pass_details','users'));

    }


    public function issuerChangeStore(Request $request,$id)
    {
        //  dd($request->all());
        // exit;
        $startDate = $request->start_date;
        $expectedEndDate=date('Y-m-d H:i:s', strtotime($startDate. ' + 7 days'));
        // return $expectedEndDate;
        $request->validate([
            'division_id'       => 'required|numeric',
            'section_id'        => 'required|numeric',
            'department_id'     => 'required|numeric',
            'order_no'          => 'required|numeric',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|before_or_equal:'.$expectedEndDate,
            'job_id'            => 'numeric',
            'issuer_id'         => 'numeric',
            'supervisor_name'   => 'numeric',
        ]);

        //Get Job detail according to job id
        $job_details = Job::where('id',$request->job_id)->first();
        $users       = UserLogin::where('id',$request->issuer_id)->first();
       
        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        $u_dt =  date('Y-m-d H:i:s'); 
        //permit entry
        $permit =  Permit::where('id',$id)->update([
            'division_id'     =>  $request->division_id,
            'department_id'   =>  $request->department_id,
            'section_id'      =>  $request->section_id,
            'order_no'        =>  $request->order_no,
            'order_validity'  =>  $request->order_validity,
            'start_date'      =>  $request->start_date,
            'end_date'        =>  $request->end_date,
            'job_id'          =>  $request->job_id,
            'swp_number'      =>  $job_details->swp_number,
            'high_risk'       =>  $job_details->high_risk,  
            'power_clearance' =>  $job_details->power_clearance,
            'confined_space'  =>  $job_details->confined_space,
            'issuer_id'       =>  $request->issuer_id,
            'post_site_pic'   =>  'dummy',
            'status'          =>  'Requested',
            'entered_by'      =>   Session::get('user_idSession'),
            'job_description' =>   $request->job_description,
            'job_location'    =>   $request->job_location,        
            'permit_req_name' =>   $request->supervisor_name,
            'request_dt'       =>  $u_dt
        ]);

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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
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
                'permit_id'     =>  $id
            ]);          
        }
        
        foreach ($request->employee_name as $key => $value) {
            $unique_id   = $request->p_id[$key];
            //  return $unique_id;
            $power_cls = GatePassDetail::where('id',$unique_id)->first();
            //return $power_cls->id;
            if($power_cls != null){
                GatePassDetail::where('id',$unique_id)->update([
                    'employee_name'  => $request->employee_name[$key],
                    'gate_pass_no'   => $request->gate_pass_no[$key],
                    'designation'    => $request->designation[$key],
                    'age'            => $request->age[$key],
                ]);
            }
            else{
                GatePassDetail::insert([
                    'permit_id'      => $id,
                    'employee_name'  => $request->employee_name[$key],
                    'gate_pass_no'   => $request->gate_pass_no[$key],
                    'designation'    => $request->designation[$key],
                    'age'            => $request->age[$key],
                ]);
            } 
        }

        if($permit){
            return redirect('admin/list_permit')->with('message', 'Your Executing Agency Update Suceessfully');
        }
        else{
            return back()->with('message','Ooops... Error While Update Permit');
        }
    }

    public function SendNotifyEmailRequester($var)
    {
        $getPermitEnterData = Permit::where('id',$var)->first();
        
        $getUserDetails = UserLogin::where('id',$getPermitEnterData->entered_by)->first();
        
        $user = array('name'        => $getUserDetails->name,
                    'email'         => $getUserDetails->email,
                    'vendor_code'   => $getUserDetails->vendor_code,
                    'subject'       => "Notify for Permit"
                );
        
        Mail::send('admin.list_permits.notify',['data' => $user],function($message) use ($user){
            $message->to($user['email'])
                    ->subject($user['subject']);
            $message->from('automatic_mail@tatasteel.com');
        });
        return back()->with('message','You send the email to requester');       
    }

    public function ReportList(Request $request){
        if(Session::get('user_sub_typeSession') == 3){
            $divisions = Division::get();
        }
        else{
            $users       = UserLogin::where('id',Session::get('user_idSession'))->first();
            $divisions   = Division::where('id',@$users->division_id)->get();
          }
        $permits = "";
        if ($request->input('divi_id')<>''  || $request->input('dept_id')<>'' || $request->input('fromdate')<>'' && $request->input('todate')<>'')
        {   
            $start  = Carbon::parse($request->input('fromdate'))->startOfDay()->toDateTimeString();
            $end    = Carbon::parse($request->input('todate'))->endOfDay()->toDateTimeString();
            $permits = Permit::where('division_id',$request->input('divi_id'))
                        ->where('department_id',$request->input('dept_id'))
                        ->whereBetween('created_at',[$start,$end])->get();
        }
        return view('admin.report.index',compact('permits','divisions'));
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
            $permits = Permit::where('division_id',$request->input('divi_id'))->whereBetween('created_at',[$start,$end])->orderBy('id','desc')->get();
            return view('admin.report.index',compact('permits','divisions'));
        }
        elseif($request->input('divi_id')<>'' && $request->input('dept_id') != 'ALL' && $request->input('fromdate')<>'' && $request->input('todate')<>'')
        {    
            $start  = Carbon::parse($request->input('fromdate'))->startOfDay()->toDateTimeString();
            $end    = Carbon::parse($request->input('todate'))->endOfDay()->toDateTimeString();
            $permits = Permit::where('division_id',$request->input('divi_id'))->where('department_id',$request->input('dept_id'))->whereBetween('created_at',[$start,$end])->orderBy('id','desc')->get();
            return view('admin.report.index',compact('permits','divisions'));
        }
	
        elseif($request->input('fromdate')<>'' && $request->input('todate')<>'')
        {    
            $start  = Carbon::parse($request->input('fromdate'))->startOfDay()->toDateTimeString();
            $end    = Carbon::parse($request->input('todate'))->endOfDay()->toDateTimeString();
            $permits = Permit::whereBetween('created_at',[$start,$end])->orderBy('id','desc')->get();
            return view('admin.report.index',compact('permits','divisions'));
        }
    }
    // public function DownloadReport()
    // {
    //     return Excel::download(new BulkExport, 'report.xlsx');
    // }
    public function ReportView($idenc)
    {
        $id = \Crypt::decrypt($idenc);
        $permit_division_datas = Permit::where('permits.id',$id)
                ->leftjoin('divisions','permits.division_id','=','divisions.id')
                ->select('divisions.id as divisionId','divisions.name as divisionName',
                'permits.order_no as permitOrder','permits.order_validity as permitOrderValidity',
                'permits.start_date as startDate','permits.end_date as endDate',
                'permits.issuer_id as permitIssuerID','permits.power_clearance as PermitPowerClearance',
                'permits.confined_space as PermitConfinedSpace', 'permits.high_risk as PermitHighRisk',
                'permits.latlong as PermitlatLong','permits.safe_work as PermitSafeWork',
                'permits.all_person as PermitAll_person','permits.worker_working as permitWorkerWorking',
                'permits.all_lifting_tools as PermitAll_lifting_tools','permits.all_safety_requirement as permitAll_safety_requirement',
                'permits.all_person_are_trained as PermitAll_person_are_trained','permits.ensure_the_appplicablle as permitEnsure_the_appplicablle',
                'permits.power_clearance_number as PermitPower_clearance_number',
                'permits.area_clearence_required as PermitArea_clearence',
                'permits.area_clearence_id as PermitArea_clearenceId',
                'permits.status as PermitStatus','permits.post_site_pic as PermitSitePic',
                'permits.job_description as JobDescription','permits.job_location as JobLocation',
                'permits.permit_req_name as permitRequestname','permits.s_instruction as s_instruction',
                'permits.department_id as department_id','permits.vlevel as vlevel',
                'permits.issuer_power as issuer_power','permits.rec_power as rec_power',
                'permits.electrical_license_issuer',
                'permits.validity_date_issuer','permits.electrical_license_rec',
                'permits.validity_date_rec','permits.issuer_id','permits.job_id',
                'permits.welding_gas','permits.riggine','permits.working_at_height','permits.hydraulic_pneumatic',
                'permits.painting_cleaning','permits.gas','permits.others','permits.specify_others','permits.other_isolation'
            )->get();

        $job_datas   = Permit::where('permits.id',$id)
                ->leftjoin('jobs','permits.job_id','=','jobs.id')
                ->select('jobs.id as jobId','jobs.job_title as jobTitle',
                'jobs.swp_number as jobSwpNumber','jobs.issuer2')->get();

        $permit_hazards = Permit_Hazard::where('permit_id',$id)->get();

        $swp_files   = Permit::where('permits.id',$id)
                                ->leftjoin('swp__files','permits.job_id','=','swp__files.job_id')
                                ->select('swp__files.swp_file')->get();

        $gate_pass_details   = GatePassDetail::where('permit_id',$id)->get();
        $users  = UserLogin::where('user_type', 1)->get();
        $power_clearances  = PowerClearence::where('permit_id',$id)->get();
        $confined_spaces   = ConfinedSpace::where('permit_id',$id)->get();
        $otherisolation   = OtherIsolation::where('permit_id',$id)->get();
    
        $forAreaClearence  = UserLogin::where('division_id',@$permit_division_datas[0]->divisionId)->select('id','name')->get();
        return view('admin.report.viewreport',compact('permit_division_datas',
        'job_datas','gate_pass_details','id','users','power_clearances','confined_spaces','swp_files','permit_hazards','forAreaClearence','otherisolation'));


    }
    
    public function ShowValidity_LicenseISS($id)
    {
        $data = ShutdownChild::where('id',$id)->get();
        return $data; 
    }
    public function ShowValidity_LicenseREC($id)
    {
        $data = ShutdownChild::where('id',$id)->get();
        return $data; 
    }
    // get Power Clearance Issuer Name
    public function getvoltageIssuer($vlevel)
    {
        if($vlevel == '.132KV')
        {
            $data = UserLogin::leftjoin('shutdownchilds','shutdownchilds.userlogins_id','=','userlogins.id')
                                    ->where('userlogins.division_id',Session::get('user_DivID_Session'))
                                    ->where('shutdownchilds.132KV','yes')
                                    ->where('shutdownchilds.33KV','yes')
                                    ->where('shutdownchilds.11KV','yes')
                                    ->where('shutdownchilds.LT','yes')
                                    ->where('shutdownchilds.issue_power','yes')
                                    ->select('shutdownchilds.*','userlogins.id as userid','userlogins.name')
                                    ->get(); 
        }
        elseif($vlevel == '.33KV')
        {
            $data = UserLogin::leftjoin('shutdownchilds','shutdownchilds.userlogins_id','=','userlogins.id')
                                    ->where('userlogins.division_id',Session::get('user_DivID_Session'))
                                    ->where('shutdownchilds.33KV','yes')
                                    ->where('shutdownchilds.11KV','yes')
                                    ->where('shutdownchilds.LT','yes')
                                    ->where('shutdownchilds.issue_power','yes')
                                    ->select('shutdownchilds.*','userlogins.id as userid','userlogins.name')
                                    ->get(); 
        }
        elseif($vlevel == '.11KV')
        {
            $data = UserLogin::leftjoin('shutdownchilds','shutdownchilds.userlogins_id','=','userlogins.id')
                                ->where('userlogins.division_id',Session::get('user_DivID_Session'))
                                ->where('shutdownchilds.11KV','yes')
                                ->where('shutdownchilds.LT','yes')
                                ->where('shutdownchilds.issue_power','yes')
                                ->select('shutdownchilds.*','userlogins.id as userid','userlogins.name')
                                ->get(); 
        }
        elseif($vlevel == '.LT')
        {
            $data = UserLogin::leftjoin('shutdownchilds','shutdownchilds.userlogins_id','=','userlogins.id')
                                ->where('userlogins.division_id',Session::get('user_DivID_Session'))
                                ->where('shutdownchilds.LT','yes')
                                ->where('shutdownchilds.issue_power','yes')
                                ->select('shutdownchilds.*','userlogins.id as userid','userlogins.name')
                                ->get(); 
        }
        return $data; 

    }   
    public function Issuer_electrical_license($id)
    {
        $data = ShutdownChild::where('userlogins_id',$id)
                        ->select('userlogins_id','electrical_license','validity_date')
                        ->get();
        return $data;
    }
    public function getvoltageReceiver($vlevel)
    {
        if($vlevel == '.132KV'){
            $data = UserLogin::leftjoin('shutdownchilds','shutdownchilds.userlogins_id','=','userlogins.id')
                            ->where('userlogins.division_id',Session::get('user_DivID_Session'))
                            ->where('shutdownchilds.132KV','yes')
                            ->where('shutdownchilds.33KV','yes')
                            ->where('shutdownchilds.11KV','yes')
                            ->where('shutdownchilds.LT','yes')
                            ->where('shutdownchilds.receive_power','yes')
                            ->select('shutdownchilds.*','userlogins.id as userid','userlogins.name')
                            ->get(); 
        }
        elseif($vlevel == '.33KV'){
            $data = UserLogin::leftjoin('shutdownchilds','shutdownchilds.userlogins_id','=','userlogins.id')
                            ->where('userlogins.division_id',Session::get('user_DivID_Session'))
                            ->where('shutdownchilds.33KV','yes')
                            ->where('shutdownchilds.11KV','yes')
                            ->where('shutdownchilds.LT','yes')
                            ->where('shutdownchilds.receive_power','yes')
                            ->select('shutdownchilds.*','userlogins.id as userid','userlogins.name')
                            ->get(); 
        }
        elseif($vlevel == '.11KV'){
            $data = UserLogin::leftjoin('shutdownchilds','shutdownchilds.userlogins_id','=','userlogins.id')
                            ->where('userlogins.division_id',Session::get('user_DivID_Session'))
                            ->where('shutdownchilds.11KV','yes')
                            ->where('shutdownchilds.LT','yes')
                            ->where('shutdownchilds.receive_power','yes')
                            ->select('shutdownchilds.*','userlogins.id as userid','userlogins.name')
                            ->get(); 
        }
        elseif($vlevel == '.LT'){
            $data = UserLogin::leftjoin('shutdownchilds','shutdownchilds.userlogins_id','=','userlogins.id')
                            ->where('userlogins.division_id',Session::get('user_DivID_Session'))
                            ->where('shutdownchilds.LT','yes')
                            ->where('shutdownchilds.receive_power','yes')
                            ->select('shutdownchilds.*','userlogins.id as userid','userlogins.name')
                            ->get();
        }
        return $data;


    }
    public function Receiver_electrical_license($id){
        $data = ShutdownChild::where('userlogins_id',$id)
        ->select('userlogins_id','electrical_license','validity_date')->get();
        return $data;
    }


    public function DownloadExpiredGatePass(Request $request)
    {        
         
        return Excel::download(new ExportGatePass, 'expired-gatepass.xlsx');
        
        // date_default_timezone_set("Asia/Calcutta"); 
        // $date = date('Y-m-d');
        // echo $date;   
        // $fileName = 'expired-gatepass.xls';
        // $gatepass =  UserLogin::leftjoin('userlogins_employee_details','userlogins_employee_details.userlogins_id','=','userlogins.id')
        //                 ->where('userlogins.user_type',2)
        //                 ->where('userlogins.division_id',Session::get('user_DivID_Session'))
        //                 ->where('userlogins_employee_details.expiry',"<",$date)
        //                 ->select('userlogins_employee_details.*','userlogins.vendor_name_code')->get();
        // $headers = array(
        //     "Content-type"        => "text/csv",
        //     "Content-Disposition" => "attachment; filename=$fileName",
        //     "Pragma"              => "no-cache",
        //     "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        //     "Expires"             => "0"
        // );
        // $headers = array(
        //     "Content-type"        => "text/csv",
        //     "Content-Disposition" => "attachment; filename=$fileName",
        //     "Pragma"              => "no-cache",
        //     "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        //     "Expires"             => "0"
        // );

        // $columns = array('Sl No', 'Vendor Code', 'Gatepass No','Employee Name',
        //                 'Age','Designation','Expiry Date','ID','New Gatepass No','New Expiry Date');


        // $callback = function() use($gatepass, $columns) {
        //     $file = fopen('php://output', 'w');
        //     fputcsv($file, $columns);

        //     $count= 1;
        //     foreach ($gatepass as $gate) {
        //         $row['sl']           = $count++;
        //         $row['vendor_code']  = $gate->vendor_code;
        //         $row['gatepass']     = $gate->gatepass;
        //         $row['employee']     = $gate->employee;
        //         $row['age']          = $gate->age;
        //         $row['designation']  = $gate->designation;
        //         $row['expiry']       = $gate->expiry;
        //         $row['id']           = $gate->id;

        //         fputcsv($file, array($row['sl'], $row['vendor_code'], $row['gatepass']
        //             ,$row['employee'],$row['age'],$row['designation'],$row['expiry'],$row['id']));
        //     }

        //     fclose($file);
        // };
        // return Response()->stream($callback, 200, $headers);
    
    }
    // DB::Raw('IFNULL( `downloads`.`is_download` , 0 )

   /*public function Renew_Permit(Request $request)
    {
        // dd($request->all());
        $old = date('Y-m-d ',strtotime($request->old_time)); 
        $new_time = date('Y-m-d H:i', strtotime($request->req_new_time));
        

        if ($new_time > $request->old_time)
        {
            $permit = Permit::where('id',$request->permitID)->first();
            $start = strtotime($permit['start_date']);
            $end   = strtotime($permit['end_date']);

            $endDate = Permit::where('id',$request->permitID)->first(['end_date', 'renew_id_1']);
            if(!$endDate->renew_id_1)
            {
                $end2=$endDate->end_date;
            }
            else{
                $endDate = RenewPermit::where('permit_id',$request->permitID)
                        ->where('status','Approved')->orderBy('id','DESC')->first(['new_time']);
                $end2=$endDate->new_time;
            }

            $diffhrs=round((abs($end - $start) / 60)/60,2);
            $diffhrs2=round((abs(strtotime($end2) - $start) / 60)/60,2);

            $allowed=12-$diffhrs2;
            $allowedtillrenewal = strtotime("+".$allowed." hours", strtotime($end2));
            $allowedtillrenewal = date('Y-m-d H:i', $allowedtillrenewal);

            $renewalcount = RenewPermit::where('permit_id',$request->permitID)->where('status','Approved')->count();
         //   echo  $renewalcount;
           // exit;

            if ($diffhrs < 12 && $renewalcount < 2 && $new_time <= $allowedtillrenewal)
            {
              //  dd($request->all()); 
                date_default_timezone_set("Asia/Calcutta");
                $CurrentDT =  date('Y-m-d H:i:s');
                RenewPermit::insert([
                    'permit_id'       => $request->permitID,
                    'datetime_apply'  => $CurrentDT,
                    'old_time'        => $request->old_time,
                    'new_time'        => $new_time,
                    'issuer_id'       => $request->executingAgency,
                    'status'          => 'Pending_Renew_Issuer'
                ]);  
                return redirect('admin/list_permit')->with('message', 'Applied For Renewal!');
            }
            else
            {
                return redirect('admin/list_permit')->with('message', 'Cannot Be Renewed');
            }
        }
        else
        {
            return redirect('admin/list_permit')->with('message', 'End Time Should Be Greater Than Start Time');
        }
    }*/
	public function Renew_Permit(Request $request)
    {
        // dd($request->all());
         $old = date('Y-m-d ',strtotime($request->old_time)); 
      $new_time = date('Y-m-d H:i', strtotime($request->req_new_time));
    


        if ($new_time > $request->old_time)
        {
            $permit = Permit::where('id',$request->permitID)->first();
            $start = strtotime($permit['start_date']);
            $end   = strtotime($permit['end_date']);

            $endDate = Permit::where('id',$request->permitID)->first(['end_date', 'renew_id_1']);

            if(!$endDate->renew_id_1)
            {
                $end2=$endDate->end_date;
            }
            else{
                $endDate = RenewPermit::where('permit_id',$request->permitID)
                        ->where('status','Approved')->orderBy('id','DESC')->first(['new_time']);
                $end2=$endDate->new_time;
            }

            $diffhrs=round((abs($end - $start) / 60)/60,2);

            $diffhrs2=round((abs(strtotime($end2) - $start) / 60)/60,2);

             $allowed=12-$diffhrs2;

            $allowedtillrenewal = strtotime("+".$allowed." hours", strtotime($end2));
            $allowedtillrenewal = date('Y-m-d H:i', $allowedtillrenewal);

            $renewalcount = RenewPermit::where('permit_id',$request->permitID)->where('status','Approved')->count();
            //echo  $renewalcount;
            //exit;
             date_default_timezone_set('Asia/Calcutta'); 
             $outtime = date('H:i:s');

            if ($diffhrs < 12 && $renewalcount < 2 && $new_time <= $allowedtillrenewal)
            {
              //  dd($request->all()); 
                date_default_timezone_set("Asia/Calcutta");
                $CurrentDT =  date('Y-m-d H:i:s');
                RenewPermit::insert([
                    'permit_id'       => $request->permitID,
                    'datetime_apply'  => $CurrentDT,
                    'old_time'        => $request->old_time,
                    'new_time'        => $new_time,
                    'issuer_id'       => $request->executingAgency,
                    'status'          => 'Pending_Renew_Issuer'
                ]);  

// Gate Pass Detail 

                if($request->manpower_yes_no=='Yes'){
           $gate_pass_details = array();
           foreach ($request->employee_name as $key => $value) {
            $employee_name   = $request->employee_name[$key];
             $gate_pass_no    = $request->gate_pass_no[$key];
            $designation     = $request->designation[$key];
            $age             = $request->age[$key];
            $expiry_date     = $request->expiry_date[$key];
            $intime         = $request->intime[$key];
            $type           = 'Renew';
            $temp_array = array(
                'permit_id'     => $request->permitID, 
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
   }
//Gate Pass Outime Update
if(!$endDate->renew_id_1)
            {
       GatePassDetail::where('permit_id',$request->permitID)->where('type','New')->
                 update([
                'outtime'  => $outtime,
             ]);
       }else{
    GatePassDetail::where('permit_id',$request->permitID)->where('type','Renew')->
                 update([
                'outtime'  => $outtime,
             ]);

       }


                return redirect('admin/list_permit')->with('message', 'Applied For Renewal!');
            }
            else
            {
           
      return redirect('admin/list_permit')->with('message', 'Cannot Be Renewed');
            }
        }
        else
        {
            return redirect('admin/list_permit')->with('message', 'End Time Should Be Greater Than Start Time');
        }
    }
	
    public function RenewView($renewid)
    {
        $decode = \Crypt::decrypt($renewid);
        // echo $decode;
        // exit;
        $renewid = RenewPermit::where('id',$decode)->first();
        $id = Permit::where('id',$renewid->permit_id)->value('id');

        $permit_division_datas = Permit::where('permits.id',$id)
                ->leftjoin('divisions','permits.division_id','=','divisions.id')
                ->select('divisions.id as divisionId','divisions.name as divisionName',
                'permits.order_no as permitOrder','permits.order_validity as permitOrderValidity',
                'permits.start_date as startDate','permits.end_date as endDate',
                'permits.issuer_id as permitIssuerID','permits.power_clearance as PermitPowerClearance',
                'permits.confined_space as PermitConfinedSpace','permits.high_risk as PermitHighRisk',
                'permits.latlong as PermitlatLong','permits.safe_work as PermitSafeWork',
                'permits.all_person as PermitAll_person','permits.worker_working as permitWorkerWorking',
                'permits.all_lifting_tools as PermitAll_lifting_tools','permits.all_safety_requirement as permitAll_safety_requirement',
                'permits.all_person_are_trained as PermitAll_person_are_trained','permits.ensure_the_appplicablle as permitEnsure_the_appplicablle',
                'permits.power_clearance_number as PermitPower_clearance_number',
                'permits.area_clearence_required as PermitArea_clearence',
                'permits.area_clearence_id as PermitArea_clearenceId',
                'permits.status as PermitStatus','permits.post_site_pic as PermitSitePic',
                'permits.job_description as JobDescription','permits.job_location as JobLocation',
                'permits.permit_req_name as permitRequestname','permits.s_instruction as s_instruction',
                'permits.department_id as department_id','permits.vlevel as vlevel',
                'permits.issuer_power as issuer_power','permits.rec_power as rec_power',
                'permits.electrical_license_issuer',
                'permits.validity_date_issuer','permits.electrical_license_rec',
                'permits.validity_date_rec','permits.issuer_id','permits.job_id',
                'permits.welding_gas','permits.riggine','permits.working_at_height','permits.hydraulic_pneumatic',
                'permits.painting_cleaning','permits.gas','permits.others','permits.specify_others','permits.other_isolation'
            )->get();

        $job_datas   = Permit::where('permits.id',$id)
                ->leftjoin('jobs','permits.job_id','=','jobs.id')
                ->select('jobs.id as jobId','jobs.job_title as jobTitle',
                'jobs.swp_number as jobSwpNumber','jobs.issuer2')->get();
            //return $job_datas;

        $permit_hazards = Permit_Hazard::where('permit_id',$id)->get();

        $swp_files   = Permit::where('permits.id',$id)
                                ->leftjoin('swp__files','permits.job_id','=','swp__files.job_id')
                                ->select('swp__files.swp_file')->get();

        $gate_pass_details   = GatePassDetail::where('permit_id',$id)->get();
        $users  = UserLogin::where('user_type', 1)->get();
        $power_clearances  = PowerClearence::where('permit_id',$id)->get();
        $confined_spaces   = ConfinedSpace::where('permit_id',$id)->get();
        $otherisolation   = OtherIsolation::where('permit_id',$id)->get();

        $forAreaClearence  = UserLogin::where('division_id',@$permit_division_datas[0]->divisionId)
        ->select('id','name')->get();
        return view('admin.list_permits.renew_view',compact('permit_division_datas',
        'job_datas','gate_pass_details','id','users','power_clearances','confined_spaces','swp_files','permit_hazards','forAreaClearence','otherisolation','decode'));
    }

    public function RenewUpdate(Request $request)
    {
       // dd($request->all()); 
        date_default_timezone_set("Asia/Calcutta");
        $CurrentDT =  date('Y-m-d H:i:s');
        $renewStatus = RenewPermit::where('id',$request->renew_id)->first(['status','permit_id']);

        $permitCheck1 = Permit::where('id',$renewStatus->permit_id)->value('renew_id_1');
        if($permitCheck1)
        {
            $val="renew_id_2";
        }
        else
        {
            $val="renew_id_1";
        }

        if($renewStatus->status == 'Pending_Renew_Issuer' && $request->area_clearance_req == 'on'){
             RenewPermit::where('id',$request->renew_id)->update([
                'issuer_confirm_dt'  => $CurrentDT,
                'area_id'       => $request->area_clearence_id,
                'status'        => 'Pending_Renew_Area'
            ]); 

        }
        elseif($renewStatus->status == 'Pending_Renew_Issuer' && $request->area_clearance_req != 'on'){
             RenewPermit::where('id',$request->renew_id)->update([
                'issuer_confirm_dt'  => $CurrentDT,
                'status'          => 'Approved'
            ]); 
         
            Permit::where('id',$renewStatus->permit_id)->update([
                $val  => $request->renew_id,
            ]); 
        }
        elseif($renewStatus->status == 'Pending_Renew_Area'){
            RenewPermit::where('id',$request->renew_id)->update([
                'area_confirm_dt'  => $CurrentDT,
                'status'          => 'Approved'
            ]); 

            Permit::where('id',$renewStatus->permit_id)->update([
                $val  => $request->renew_id,
            ]); 
       }
        return redirect('admin/list_permit')->with('message', 'Approved!');
    }

    public function WorkOrderView()
    {
        return view('admin.list_permits.work_order_view');
    }

    public function WorkOrderimport(Request $request)
    {
        // dd($request->all());
        $delete = WorkOrder::truncate();
        Excel::import(new ImportWorkOrder,request()->file('file_datas'));

        return back()->with('message', 'Data Imported!');
    }
    
    public function GatePassView()
    {
        return view('admin.list_permits.gate_pass_view_import');
    }
     
    public function GatePassImport(Request $request)
    {
        $var = Excel::import(new ImportGatePass,request()->file('file_datas'));
        return back()->with('message', 'Data Imported!');
    }
    public function viewPower($idenc){
        $id = \Crypt::decrypt($idenc);
        $permit_division_datas = Permit::where('permits.id',$id)
                ->leftjoin('divisions','permits.division_id','=','divisions.id')
                ->select('divisions.id as divisionId','divisions.name as divisionName',
                'permits.department_id as department_id',
                'permits.order_no as permitOrder','permits.order_validity as permitOrderValidity',
                'permits.start_date as startDate','permits.end_date as endDate',
                'permits.issuer_id as permitIssuerID','permits.power_clearance as PermitPowerClearance',
                'permits.confined_space as PermitConfinedSpace', 'permits.high_risk as PermitHighRisk',
                'permits.latlong as PermitlatLong','permits.safe_work as PermitSafeWork',
                'permits.all_person as PermitAll_person','permits.worker_working as permitWorkerWorking',
                'permits.all_lifting_tools as PermitAll_lifting_tools','permits.all_safety_requirement as permitAll_safety_requirement',
                'permits.all_person_are_trained as PermitAll_person_are_trained','permits.ensure_the_appplicablle as permitEnsure_the_appplicablle',
                'permits.power_clearance_number as PermitPower_clearance_number',
                'permits.area_clearence_required as PermitArea_clearence',
                'permits.area_clearence_id as PermitArea_clearenceId',
                'permits.status as PermitStatus','permits.post_site_pic as PermitSitePic',
                'permits.job_description as JobDescription','permits.job_location as JobLocation',
                'permits.permit_req_name as permitRequestname','permits.s_instruction as s_instruction',
                'permits.vlevel as vlevel',
                'permits.issuer_power as issuer_power','permits.rec_power as rec_power',
                'permits.electrical_license_issuer',
                'permits.validity_date_issuer','permits.electrical_license_rec',
                'permits.validity_date_rec','permits.issuer_id','permits.job_id',
                'permits.welding_gas','permits.riggine','permits.working_at_height','permits.hydraulic_pneumatic',
                'permits.painting_cleaning','permits.gas','permits.others','permits.specify_others',
                'permits.pc_id','permits.created_at','permits.serial_no')->get();


        $job_datas   = Permit::where('permits.id',$id)
                    ->leftjoin('jobs','permits.job_id','=','jobs.id')
                    ->select('jobs.id as jobId','jobs.job_title as jobTitle',
                    'jobs.swp_number as jobSwpNumber')->first();

        $permit_hazards = Permit_Hazard::where('permit_id',$id)->get();


        $swp_files   = Permit::where('permits.id',$id)
                            ->leftjoin('swp__files','permits.job_id','=','swp__files.job_id')
                            ->select('swp__files.swp_file')->get();

        $gate_pass_details   = GatePassDetail::where('permit_id',$id)->get();
        
        return view('admin.list_permits.view_power_cutting',compact('permit_division_datas',
        'job_datas','permit_hazards','swp_files','gate_pass_details','id'));
          
    }

    public function powerCutting(Request $request)
    {
        // dd($request->all());
        date_default_timezone_set("Asia/Calcutta");   
        $u_dt =  date('Y-m-d H:i:s'); 
        $permitID = Permit::where('id',$request->permit_id)->first();
             
        if($request->existing_power_cutting == 'NEW'){
            //India time (GMT+5:30)
            $transdate = date('Y-m-d');
            $month = date('m', strtotime($transdate));
            $year  = date('Y', strtotime($transdate));

            // Generate serial number
            $divv = PowerCutting::whereYear('created_at', '=', $year)
                ->whereMonth('created_at', '=', $month)
                ->where('division_id',$permitID->division_id)
                ->where('type_of_permit','PC')->max('sl');

            if ($divv){$v=$divv;@$v++;@$serial_no=$v;}else{@$serial_no="1"; }
            // echo $serial_no;
            // exit;

            $pcFirstInsert = PowerCutting::create([
                'status'            => "APP",
                'sl'                => $serial_no,
                'division_id'       => $permitID->division_id,
                'department_id'     => $permitID->department_id,
                'user_id'           => Session::get('user_idSession'),
                'type_of_permit'    => 'PC',
                'getting'           => 'N'
            ]); 


            $transdate2 = date('m-Y');
            $slnumber = Division::where('id',$permitID->division_id)->first();
            $generatesl= 'PC/'.$slnumber->abbreviation.'/'.$transdate2.'/'.@$serial_no;

            $getPermitData = array(
                'status'          =>  'Requested',
                'vlevel'                 =>    $request->vlevel,
                'issuer_power'           =>    $request->issuer_power,
                'electrical_license_issuer' => $request->electrical_license_issuer,
                'validity_date_issuer'     =>  $request->validity_date_issuer,
                'rec_power'                =>  $request->rec_power,
                'electrical_license_rec'   =>  $request->electrical_license_rec,
                'validity_date_rec'        =>  $request->validity_date_rec,
                'power_clearance_number'   =>  $generatesl,
                'pc_id'                    =>  $pcFirstInsert->id,
                'power_cutting_remarks'    =>  $request->comment_power_cutting,
                'power_cutting_user_dt'    =>  $u_dt 
            );
            $permitupdate = Permit::where('id',$permitID->id)->update($getPermitData);
            $CUTTINGID    = $pcFirstInsert->id;
        }
        else{
            $powercutting = PowerCutting::where('id',$request->existing_power_cutting)->first();
            // echo $powercutting->division_id;
            $transdate2 = date('m-Y');
            $slnumber = Division::where('id',$powercutting->division_id)->first();
            $generatesl= 'PC/'.$slnumber->abbreviation.'/'.$transdate2.'/'.@$powercutting->sl;
            // echo $generatesl;
            $getPermitData = array(
                'status'                  =>  'Requested',
                'vlevel'                 =>    $request->vlevel,
                'issuer_power'           =>    $request->issuer_power,
                'electrical_license_issuer' => $request->electrical_license_issuer,
                'validity_date_issuer'     =>  $request->validity_date_issuer,
                'rec_power'              =>    $request->rec_power,
                'electrical_license_rec'   =>  $request->electrical_license_rec,
                'validity_date_rec'        =>  $request->validity_date_rec,
                'power_clearance_number'   =>  $generatesl,
                'pc_id'                    => $request->existing_power_cutting,
                'power_cutting_remarks'    => $request->comment_power_cutting,
                'power_cutting_user_dt'    => $u_dt 
            );
            $permitupdate = Permit::where('id',$permitID->id)->update($getPermitData);
            $CUTTINGID    = $request->existing_power_cutting;
        }

        foreach ($request->equipment_no as $key => $value) 
        {
            PowerClearence::insert([
                'permit_id'              => $permitID->id,
                'power_cutting_id'       => $CUTTINGID,
                'equipment'              => $request->equipment_no[$key],
                'positive_isolation_no'  => $request->equipment_lock_no[$key],
                'location'               => $request->location[$key],
                'box_no'                 => $request->box_no[$key],
                'caution_no'             => $request->caution_no[$key]
            ]);
        }

        if($permitupdate){
            return redirect('admin/list_permit')->with('message', 'Permit Update Suceessfully');
        }
        else{
            return back()->with('message','Ooops... Error While Update Permit');
        }
        
    }

    public function PowerCuttingIssuer(Request $request,$pc_id)
    {
        $P = Permit::select('id','vlevel','issuer_power',
            'electrical_license_issuer','validity_date_issuer','rec_power',
            'electrical_license_rec','validity_date_rec','created_at','division_id',
            'serial_no','power_cutting_remarks')->where('pc_id',$pc_id)->get();
        // echo $P;
        // exit;

        foreach($P as $key  => $value)
        { 
            $month  = date('m-Y', strtotime($P[$key]->created_at));
            $abb = DB::table('divisions')->where('id',$P[$key]->division_id)->first();
            $oldPSerial = @$abb->abbreviation .'/'. $month .'/'.$P[$key]->serial_no;
            // echo $P[$key]->vlevel;
            $toReturn = '';
            $toReturn .= '<fieldset class="border p-2">';
            $toReturn .= '<legend  class="w-auto">'.$oldPSerial.'</legend>';
            $toReturn .='<div class="form-group row">';
            $toReturn .='<label for="form-control-label" class="col-sm-2 col-form-label">Select Voltage Level </label>';
                $toReturn .='<div class="col-sm-10">';
                    $toReturn .='<input type="text" class="form-control" value="'.strtoupper(@$P[$key]->vlevel).'" readonly>';
                $toReturn .='</div>';
            $toReturn .='</div>';
            $toReturn .='<div class="form-group row">';
                $toReturn .='<label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Issuer Name</label>';
                    $toReturn .='<div class="col-sm-4">';
                        $toReturn .='<select class="form-control" readonly>';
                        if($P[$key]->issuer_power)
                        {
                            @$isspower = UserLogin::where('id',$P[$key]->issuer_power)->first();
                            $toReturn .='<option value="'.$isspower->id.'"> '.$isspower->name.'</option>';
                        }
                        $toReturn .='</select>';
                    $toReturn .='</div>';
                    $toReturn .='<div class="col-sm-3">';
                        $toReturn .='<select class="form-control" readonly >';
                        if($P[$key]->electrical_license_issuer)
                        {
                            $toReturn .='<option value="'.$P[$key]->electrical_license_issuer.'"> '.$P[$key]->electrical_license_issuer.'</option>';
                        }
                        $toReturn .='</select>';
                    $toReturn .='</div>';
                    $toReturn .='<div class="col-sm-3">';
                        $toReturn .='<select class="form-control" readonly>';
                        if($P[$key]->validity_date_issuer)
                        {
                            $toReturn .='<option value="'.$P[$key]->validity_date_issuer.'"> '.$P[$key]->validity_date_issuer.'</option>';
                        }
                        $toReturn .='</select>';
                    $toReturn .='</div>';
            $toReturn .='</div>';
            $toReturn .='<div class="form-group row">';
                $toReturn .='<label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Receiver Name</label>';
                    $toReturn .='<div class="col-sm-4">';
                        $toReturn .='<select class="form-control" readonly>';
                        if($P[$key]->rec_power)
                        {
                            @$recpower = UserLogin::where('id',$P[$key]->rec_power)->first();
                            $toReturn .='<option value="'.$recpower->id.'"> '.$recpower->name.'</option>';
                        }
                        $toReturn .='</select>';
                    $toReturn .='</div>';
                    $toReturn .='<div class="col-sm-3">';
                        $toReturn .='<select class="form-control" readonly>';
                        if($P[$key]->electrical_license_rec)
                        {
                            $toReturn .='<option value="'.$P[$key]->electrical_license_rec.'"> '.$P[$key]->electrical_license_rec.'</option>';
                        }
                        $toReturn .='</select>';
                    $toReturn .='</div>';
                    $toReturn .='<div class="col-sm-3">';
                        $toReturn .='<select class="form-control" readonly>';
                        if($P[$key]->validity_date_rec)
                        {
                            $toReturn .='<option value="'.$P[$key]->validity_date_rec.'"> '.$P[$key]->validity_date_rec.'</option>';
                        }
                        $toReturn .='</select>';
                    $toReturn .='</div>';
            $toReturn .='</div>';
            $toReturn .='<div class="form-group row">';
                $toReturn .='<label for="form-control-label" class="col-sm-2 col-form-label">Power Clearence Details<p><b>(if Not Applicable, Please mention the reason/remarks.)</b></p></label>';
                $toReturn .='<div class="col-sm-10">';
                    $toReturn .='<table class="table table-bordered">';
                        $toReturn .='<thead><tr>'; 
                            $toReturn .='<th>Permit Number </th>'; 
                            $toReturn .='<th>Name of the Equipment</th>'; 
                            $toReturn .='<th>Equipment Lock No.</th>'; 
                            $toReturn .='<th>Location</th>'; 
                            $toReturn .='<th>Box No</th>';   
                            $toReturn .='<th>Caution Tag No</th>'; 
                        $toReturn .='</tr></thead>';
                        $powerCLS  = PowerClearence::where('permit_id',$P[$key]->id)->get();
                        if($powerCLS->count() > 0){
                            foreach($powerCLS as $key1 => $value1){
                                $toReturn .='<tbody>';
                                    $toReturn .='<tr class="" id="">';
                                        $toReturn .='<td><input type="text" class="form-control" readonly value="'.$oldPSerial.'"></td>';
                                        $toReturn .='<td><input type="text" class="form-control" readonly value="'. $powerCLS[$key1]->equipment .'"></td>';
                                        $toReturn .='<td><input type="text" class="form-control" readonly value="'. $powerCLS[$key1]->positive_isolation_no .'"></td>';
                                        $toReturn .='<td><input type="text" class="form-control" readonly value="'. $powerCLS[$key1]->location .'"></td>';
                                        $toReturn .='<td><input type="text" class="form-control" readonly value="'. $powerCLS[$key1]->box_no .'"></td>';
                                        $toReturn .='<td><input type="text" class="form-control" readonly value="'. $powerCLS[$key1]->caution_no .'"></td>';

                                    $toReturn .='</tr>';
                                $toReturn .='</tbody>';
                            }
                        }
                    $toReturn .='</table>';
                $toReturn .='</div>';
            $toReturn .='</div>';
            $toReturn .='<div class="form-group row">';
                $toReturn .='<label for="form-control-label" class="col-sm-2 col-form-label">Comment of Power Cutting </label>';
                $toReturn .='<div class="col-sm-10">';
                    $toReturn .='<textarea class="form-control" readonly>'.$P[$key]->power_cutting_remarks.'</textarea>';
                $toReturn .='</div>';
            $toReturn .='</div>';
            $toReturn .='</fieldset>';
        echo $toReturn;
        }
    }

    public function viewGetting(Request $request,$idenc)
    {
        $permitid = \Crypt::decrypt($idenc);
        // echo $permitid;
        // exit;
        $P = Permit::select('id','vlevel','issuer_power',
            'electrical_license_issuer','validity_date_issuer','rec_power',
            'electrical_license_rec','validity_date_rec','created_at','division_id',
            'serial_no','power_cutting_remarks','power_clearance_number',
            'pg_ins1','pg_ins2','pg_ins3','return_status','pc_id')
            ->where('id',$permitid)->get();
        return view('admin.list_permits.view_power_getting',compact('P'));
    }
    
    public function PermitReturnPG(Request $request)
    {
        date_default_timezone_set("Asia/Calcutta");
        $u_dt =  date('Y-m-d H:i:s');

        // FOR SL GETTING FROM POWER GETTING TABLE
        $transdate = date('Y-m-d');
        $month = date('m', strtotime($transdate));
        $year  = date('Y', strtotime($transdate));

  
        foreach ($request->permitID as $key => $value) {
            $divv = PowerGetting::whereYear('created_at', '=', $year)
                    ->whereMonth('created_at', '=', $month)->max('sl');
            if ($divv){ $v=$divv;@$v++;@$serial_no=$v;}
            else{ @$serial_no = "1"; }

            // OK 
            $arrayPowerGetting = PowerGetting::create([
                'permit_id'         => $request->permitID[$key],
                'power_cutting_id'  => $request->PCID,
                'user_id'           => Session::get('user_idSession'),
                'sl'                => @$serial_no,
                'power_cutting_comment'  => $request->comment_power_cutting
            ]);
            // exit;

            $pDiv = Permit::where('id',$request->permitID[$key])->first();
            $transdate2 = date('m-Y');
            $abb   = Division::where('id',$pDiv->division_id)->first();
            $generatesl = 'PG/'.$abb->abbreviation.'/'.$transdate2.'/'.@$serial_no;


            $arrayPermit = Permit::where('id',$request->permitID[$key])->update([ 
                'return_status' => 'Pending',
                'pg_action_dt'  => $u_dt,
                'pg_number'     => $generatesl,
                'pg_id'         => $arrayPowerGetting->id,
                'rec_power'     => $request->rec_power[$key],
                'electrical_license_rec' => $request->electrical_license_rec[$key],
                'validity_date_rec' => $request->validity_date_rec[$key],
                'q1' => @$request->q1[$key] ? @$request->q1[$key] : 'off' ,
                'q2' => @$request->q2[$key] ? @$request->q2[$key] : 'off' ,
                'q3' => @$request->q3[$key] ? @$request->q3[$key] : 'off' ,
                'q4' => @$request->q4[$key] ? @$request->q4[$key] : 'off' ,
                'q5_others'       => @$request->q5_others[$key],
                'exe_lock'        => @$request->exe_lock[$key],
                'work_lock'       => @$request->work_lock[$key]
            ]);
          
        } 

        if($arrayPowerGetting && $arrayPermit){
            return redirect('admin/list_permit')->with('message', 'Permit Returned!');
        }
        else{
            return back()->with('message','Ooops... Error While Cancle Permit');
        }
    }
}
