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
class SafetyDataEntryController extends Controller
{

    
public function index(){
	    if(Session::get('user_sub_typeSession') == 3){
            $divisions = Division::get();
           
        }
        else{
            $users       = UserLogin::where('id',Session::get('user_idSession'))->first();
            $divisions   = Division::where('id',@$users->division_id)->get();
          }
        return view('Safety_data_entry',compact('divisions'));
		
        //return view('RequestVGatepass');////
    }
    public function index1(){
         // $divisions = Division::all();
 //$gatepasss = DB::table('visitor_gate_pass')->orderBy('id','desc')->get();
       // return view('admin.gatepass_approvals.approve',compact('gatepasss'));
        $UserLogin = UserLogin::all();
        $workorder = DB::table('work_order');
        if(Session::get('user_sub_typeSession') == 3){
        $gatepasss = DB::table('safety_data_entry')
                              ->orderBy('id', 'DESC')->get();
       }else{
        $gatepasss = DB::table('safety_data_entry')->where('created_by',Session::get('user_idSession'))
                               ->where('division_id',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();
       }

       return view('admin.gatepass_approvals.safety_data_view',compact('gatepasss'));
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
            $report = DB::table('safety_data_entry')->where('division_id',$request->input('divi_id'))
                       
             ->whereBetween('created_datetime',[$start,$end])->get();
            //$report = DB::table('Clms_gatepass')->whereBetween('created_datetime',[$start,$end])->get();
        }
        return view('admin.gatepass_approvals.safety_report',compact('report','divisions'));
        
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


      if($request->input('divi_id')<>'' )
        {    
            $start  = Carbon::parse($request->input('from_month'));
            $end    = Carbon::parse($request->input('to_month'));
            $report = DB::table('safety_data_entry')->where('division_id',$request->input('divi_id'))
          
               ->whereBetween('month',[$start,$end])
              ->get();

          //echo $report;
            //exit;
            return view('admin.gatepass_approvals.safety_report',compact('report','divisions'));
        }else
        {    
            $start  = Carbon::parse($request->input('from_month'))->firstOfMonth();
            $end    = Carbon::parse($request->input('to_month'));
            $report = DB::table('safety_data_entry')
               ->whereBetween('month',[$start,$end])
              ->get();

          //echo $report;
            //exit;
            return view('admin.gatepass_approvals.safety_report',compact('report','divisions'));
        }
  }
 //->whereBetween('financial_year',[$start,$end])
 //->where('financial_year',$request->input('financial_year'))
public function edit($vgpid)
    {
        $id = \Crypt::decrypt($vgpid);
        //Unique Record
        //$gatepass = DB::table('visitor_gate_pass');
        $gatepass  =DB::table('safety_data_entry')->where('id',$id)->get();
        $divisions   = Division::all();
        $department   = Department::all();
        
        return view('admin.gatepass_approvals.edit_safety_data',compact('id'));
    }
    public function edit_draft($vgpid)
    {
        $id = \Crypt::decrypt($vgpid);
        //Unique Record
        //$gatepass = DB::table('visitor_gate_pass');

        if(Session::get('user_sub_typeSession') == 3){
            $divisions = Division::get();
           
        }
        else{
            $users       = UserLogin::where('id',Session::get('user_idSession'))->first();
            $divisions   = Division::where('id',@$users->division_id)->get();
          }
        $gatepass  =DB::table('safety_data_entry')->where('id',$id)->get();
        return view('admin.gatepass_approvals.edit_safety_data_draft',compact('id','divisions'));
    }
public function printg($pgid)
    {
        $id = \Crypt::decrypt($pgid);
        //Unique Record
        //$gatepass = DB::table('visitor_gate_pass');
       $gatepass  =DB::table('Clms_gatepass')->where('id',$id)->get();
        $divisions   = Division::all();
        $department   = Department::all();
        
        return view('admin.gatepass_approvals.printg_clms',compact('id','gatepass','divisions','department'));
    }
public function update1(Request $request)
    {
		date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        $date =  date('Y-m-d H:i:s');
        $transdate = date('Y-m-d');
         $transdate1 = date('m-d');
          $month = date('m', strtotime($transdate));
          $year  = date('Y', strtotime($transdate));
          
        if($request->q1 > 0 && $request->q1_upload_q1==''){
	
	 $request->validate([
            'q1_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
	
}
if($request->q3 > 0 && $request->q3_upload_q3==''){
	$request->validate([
            'q3_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
	
}

if($request->q5 > 0 && $request->q5_upload_q5==''){
	$request->validate([
            'q5_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}

if($request->q10 > 0 && $request->q10_upload_q10==''){
	$request->validate([
            'q10_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}

if($request->q11 > 0 && $request->q11_upload_q11==''){
	$request->validate([
            'q11_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}

if($request->q12 > 0 && $request->q12_upload_q12==''){
	$request->validate([
            'q12_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}

if($request->q13 > 0 && $request->q13_upload_q13==''){
	$request->validate([
            'q13_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}
if($request->q14 > 0 && $request->q14_upload_q14==''){
	$request->validate([
            'q14_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}

if($request->q15 > 0 && $request->q15_upload_q15==''){
	$request->validate([
            'q15_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}
if($request->q16 > 0 && $request->q16_upload_q16==''){
	$request->validate([
            'q16_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}
if($request->q17 > 0 && $request->q17_upload_q17==''){
	$request->validate([
            'q17_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}
if($request->q18 > 0 && $request->q18_upload_q18==''){
	$request->validate([
            'q18_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}
  
  
   if($request->hasFile('q1_upload')){
              $location = 'documents/clm_pics/';
              $extension = '.'.$request->q1_upload->getClientOriginalExtension();
              $name = basename($request->q1_upload->getClientOriginalName(),$extension).time();
              //$name = $name.$extension;
			  $uid = Session::get('user_idSession');
			$datetime = date('Ymd_H_i_s');
			$randn = rand(0000,9999);
			$name = $uid.'_'.$datetime.'_'.$randn.$extension;
              $path = $request->q1_upload->move($location,$name);
             // $name = $location.$name;
              $name=$name;
            
          }else{
              $name=$request->q1_upload_q1;
          }
  if($request->hasFile('q2_upload')){
              $location1 = 'documents/clm_pics/';
              $extension1 = '.'.$request->q2_upload->getClientOriginalExtension();
              $name1 = basename($request->q2_upload->getClientOriginalName(),$extension1).time();
              //$name1 = $name1.$extension1;
			  $uid1 = Session::get('user_idSession');
			$datetime1 = date('Ymd_H_i_s');
			$randn1 = rand(0000,9999);
			$name1 = $uid1.'_'.$datetime1.'_'.$randn1.$extension1;
              $path1 = $request->q2_upload->move($location1,$name1);
              $name1 = $name1;
             }else{
              $name1='';
             }
          if($request->hasFile('q3_upload')){
              $location2 = 'documents/clm_pics/';
              $extension2 = '.'.$request->q3_upload->getClientOriginalExtension();
              $name2 = basename($request->q3_upload->getClientOriginalName(),$extension2).time();
             // $name2 = $name2.$extension2;
			  $uid2 = Session::get('user_idSession');
			$datetime2 = date('Ymd_H_i_s');
			$randn2 = rand(0000,9999);
			$name2 = $uid2.'_'.$datetime2.'_'.$randn2.$extension2;
              $path2 = $request->q3_upload->move($location2,$name2);
              $name2 = $name2;
             // $old_name = Permit::where('id',$id)->get();
              //if(file_exists($old_name[0]->upload_unique_id)){
                 // unlink($old_name[0]->upload_unique_id);
             // }
          }else{
              $name2=$request->q3_upload_q3;
          }
  if($request->hasFile('q4_upload')){
              $location3 = 'documents/clm_pics/';
              $extension3 = '.'.$request->q4_upload->getClientOriginalExtension();
              $name3 = basename($request->q4_upload->getClientOriginalName(),$extension3).time();
              //$name3 = $name3.$extension3;
			  $uid3 = Session::get('user_idSession');
			$datetime3 = date('Ymd_H_i_s');
			$randn3 = rand(0000,9999);
			$name3 = $uid3.'_'.$datetime3.'_'.$randn3.$extension3;
              $path3 = $request->q4_upload->move($location3,$name3);
              $name3 = $name3;
            
          }else{
              $name3='';
          }
  
          if($request->hasFile('q5_upload')){
              $location4 = 'documents/clm_pics/';
              $extension4 = '.'.$request->q5_upload->getClientOriginalExtension();
              $name4 = basename($request->q5_upload->getClientOriginalName(),$extension4).time();
              //$name4 = $name4.$extension4;
			  $uid4 = Session::get('user_idSession');
			$datetime4 = date('Ymd_H_i_s');
			$randn4 = rand(0000,9999);
			$name4 = $uid4.'_'.$datetime4.'_'.$randn4.$extension4;
              $path4 = $request->q5_upload->move($location4,$name4);
              $name4 = $name4;
           
          }else{
              $name4=$request->q5_upload_q5;
          }
  if($request->hasFile('q6_upload')){
              $location5 = 'documents/clm_pics/';
              $extension5 = '.'.$request->q6_upload->getClientOriginalExtension();
              $name5 = basename($request->q6_upload->getClientOriginalName(),$extension5).time();
              //$name5 = $name5.$extension5;
			  $uid5 = Session::get('user_idSession');
			$datetime5 = date('Ymd_H_i_s');
			$randn5 = rand(0000,9999);
			$name5 = $uid5.'_'.$datetime5.'_'.$randn5.$extension5;
              $path5 = $request->q6_upload->move($location5,$name5);
              $name5 = $name5;
           
          }else{
              $name5=$request->q6_upload_q6;
          }
  if($request->hasFile('q7_upload')){
              $location6 = 'documents/clm_pics/';
              $extension6 = '.'.$request->q7_upload->getClientOriginalExtension();
              $name6 = basename($request->q7_upload->getClientOriginalName(),$extension6).time();
              //$name6 = $name6.$extension6;
			  $uid6 = Session::get('user_idSession');
			$datetime6 = date('Ymd_H_i_s');
			$randn6 = rand(0000,9999);
			$name6 = $uid6.'_'.$datetime6.'_'.$randn6.$extension6;
              $path6 = $request->q7_upload->move($location6,$name6);
              $name6 = $name6;
           
          }else{
              $name6=$request->q7_upload_q7;
          }
  if($request->hasFile('q8_upload')){
              $location7 = 'documents/clm_pics/';
              $extension7 = '.'.$request->q8_upload->getClientOriginalExtension();
              $name7 = basename($request->q8_upload->getClientOriginalName(),$extension7).time();
              //$name7 = $name7.$extension7;
			  $uid7 = Session::get('user_idSession');
			$datetime7 = date('Ymd_H_i_s');
			$randn7 = rand(0000,9999);
			$name7 = $uid7.'_'.$datetime7.'_'.$randn7.$extension7;
              $path7 = $request->q8_upload->move($location7,$name7);
              $name7 = $name7;
           
          }else{
              $name7=$request->q8_upload_q8;
          }
          if($request->hasFile('q9_upload')){
              $location8 = 'documents/clm_pics/';
              $extension8 = '.'.$request->q9_upload->getClientOriginalExtension();
              $name8 = basename($request->q9_upload->getClientOriginalName(),$extension8).time();
              //$name8 = $name8.$extension8;
			  $uid8 = Session::get('user_idSession');
			$datetime8 = date('Ymd_H_i_s');
			$randn8 = rand(0000,9999);
			$name8 = $uid8.'_'.$datetime8.'_'.$randn8.$extension8;
              $path8 = $request->q9_upload->move($location8,$name8);
              $name8 = $name8;
           
          }else{
              $name8=$request->q9_upload_q9;
          }
          if($request->hasFile('q10_upload')){
              $location9 = 'documents/clm_pics/';
              $extension9 = '.'.$request->q10_upload->getClientOriginalExtension();
              $name9 = basename($request->q10_upload->getClientOriginalName(),$extension9).time();
              //$name9 = $name9.$extension9;
			  $uid9 = Session::get('user_idSession');
			$datetime9 = date('Ymd_H_i_s');
			$randn9 = rand(0000,9999);
			$name9 = $uid9.'_'.$datetime9.'_'.$randn9.$extension9;
              $path9 = $request->q10_upload->move($location9,$name9);
              $name9 = $name9;
           
          }else{
              $name9=$request->q10_upload_q10;
          }
          if($request->hasFile('q11_upload')){
              $location10 = 'documents/clm_pics/';
              $extension10 = '.'.$request->q11_upload->getClientOriginalExtension();
              $name10 = basename($request->q11_upload->getClientOriginalName(),$extension10).time();
              //$name10 = $name10.$extension10;
			  $uid10 = Session::get('user_idSession');
			$datetime10 = date('Ymd_H_i_s');
			$randn10 = rand(0000,9999);
			$name10 = $uid10.'_'.$datetime10.'_'.$randn10.$extension10;
              $path10 = $request->q11_upload->move($location10,$name10);
              $name10 = $name10;
           
          }else{
              $name10=$request->q11_upload_q11;
          }
          if($request->hasFile('q12_upload')){
              $location11 = 'documents/clm_pics/';
              $extension11 = '.'.$request->q12_upload->getClientOriginalExtension();
              $name11 = basename($request->q12_upload->getClientOriginalName(),$extension11).time();
              //$name11 = $name11.$extension11;
			  $uid11 = Session::get('user_idSession');
			$datetime11 = date('Ymd_H_i_s');
			$randn11 = rand(0000,9999);
			$name11 = $uid11.'_'.$datetime11.'_'.$randn11.$extension11;
              $path11 = $request->q12_upload->move($location11,$name11);
              $name11 = $name11;
           
          }else{
              $name11=$request->q12_upload_q12;
          }
          if($request->hasFile('q13_upload')){
              $location12 = 'documents/clm_pics/';
              $extension12 = '.'.$request->q13_upload->getClientOriginalExtension();
              $name12 = basename($request->q13_upload->getClientOriginalName(),$extension12).time();
              //$name12 = $name12.$extension12;
			  $uid12 = Session::get('user_idSession');
			$datetime12 = date('Ymd_H_i_s');
			$randn12 = rand(0000,9999);
			$name12 = $uid12.'_'.$datetime12.'_'.$randn12.$extension12;
              $path12 = $request->q13_upload->move($location12,$name12);
              $name12 = $name12;
           
          }else{
              $name12=$request->q13_upload_q13;
          }
          if($request->hasFile('q14_upload')){
              $location13 = 'documents/clm_pics/';
              $extension13 = '.'.$request->q14_upload->getClientOriginalExtension();
              $name13 = basename($request->q14_upload->getClientOriginalName(),$extension13).time();
              //$name13 = $name13.$extension13;
			  $uid13 = Session::get('user_idSession');
			$datetime13 = date('Ymd_H_i_s');
			$randn13 = rand(0000,9999);
			$name13 = $uid13.'_'.$datetime13.'_'.$randn13.$extension13;
              $path13 = $request->q14_upload->move($location13,$name13);
              $name13 = $name13;
           
          }else{
              $name13=$request->q14_upload_q14;
          }
  if($request->hasFile('q15_upload')){
              $location14 = 'documents/clm_pics/';
              $extension14 = '.'.$request->q15_upload->getClientOriginalExtension();
              $name14 = basename($request->q15_upload->getClientOriginalName(),$extension14).time();
              //$name14 = $name14.$extension14;
			  $uid14 = Session::get('user_idSession');
			$datetime14 = date('Ymd_H_i_s');
			$randn14 = rand(0000,9999);
			$name14 = $uid14.'_'.$datetime14.'_'.$randn14.$extension14;
              $path14 = $request->q15_upload->move($location14,$name14);
              $name14 = $name14;
           
          }else{
              $name14=$request->q15_upload_q15;
          }
          if($request->hasFile('q16_upload')){
              $location15 = 'documents/clm_pics/';
              $extension15 = '.'.$request->q16_upload->getClientOriginalExtension();
              $name15 = basename($request->q16_upload->getClientOriginalName(),$extension15).time();
              //$name15 = $name15.$extension15;
			  $uid15 = Session::get('user_idSession');
			$datetime15 = date('Ymd_H_i_s');
			$randn15 = rand(0000,9999);
			$name15 = $uid15.'_'.$datetime15.'_'.$randn15.$extension15;
              $path15 = $request->q16_upload->move($location15,$name15);
              $name15 = $name15;
           
          }else{
              $name15=$request->q16_upload_q16;
          }
           if($request->hasFile('q17_upload')){
              $location16 = 'public/documents/clm_pics/';
              $extension16 = '.'.$request->q17_upload->getClientOriginalExtension();
              $name16 = basename($request->q17_upload->getClientOriginalName(),$extension16).time();
              //$name16 = $name16.$extension16;
			  $uid16 = Session::get('user_idSession');
			$datetime16 = date('Ymd_H_i_s');
			$randn16 = rand(0000,9999);
			$name16 = $uid16.'_'.$datetime16.'_'.$randn16.$extension16;
              $path16 = $request->q17_upload->move($location16,$name16);
              $name16 = $name16;
           
          }else{
              $name16=$request->q17_upload_q17;
          }
          if($request->hasFile('q18_upload')){
              $location17 = 'documents/clm_pics/';
              $extension17 = '.'.$request->q18_upload->getClientOriginalExtension();
              $name17 = basename($request->q18_upload->getClientOriginalName(),$extension17).time();
              //$name17 = $name17.$extension17;
			  $uid17 = Session::get('user_idSession');
			$datetime17 = date('Ymd_H_i_s');
			$randn17 = rand(0000,9999);
			$name17 = $uid17.'_'.$datetime17.'_'.$randn17.$extension17;
              $path17 = $request->q18_upload->move($location17,$name17);
              $name17 = $name17;
           
          }else{
              $name17=$request->q18_upload_q18;
          }
  
 
          if($request->submit == 'full_submit'){
            $draft='No';
        }elseif($request->submit == 'draft'){
            $draft='Yes';
        }
  
       
          $safety=  DB::table('safety_data_entry')->where('id',$request->id)->update([
             // 'sl' => $serial_no,
              //'full_sl'   =>$full_sl,
              'financial_year'=>$request->month,
              'month' => $request->month,
              'division_id' => $request->division,
              'q1' =>$request->q1,
              'q1_upload' =>$name,
              'q2' => $request->q2,
              'q2_upload' =>$name1,
              'q3' =>$request->q3,
             'q3_upload' =>$name2,
              'q4' =>$request->q4,
             'q4_upload' =>$name3,
              'q5' =>$request->q5,
              'q5_upload' =>$name4,
              'q6' =>$request->q6,
             'q6_upload' =>$name5,
              'q7' =>$request->q7,
             'q7_upload' =>$name6,
              'q8' =>$request->q8,
              'q8_upload' =>$name7,
              'q9' =>$request->q9,
             'q9_upload' =>$name8,
              'q10' =>$request->q10,
              'q10_upload' =>$name9,
              'q11' =>$request->q11,
              'q11_upload' =>$name10,
              'q12' =>$request->q12,
              'q12_upload' =>$name11,
              'q13' =>$request->q13,
              'q13_upload' =>$name12,
              'q14' =>$request->q14,
              'q14_upload' =>$name13,
              'q15' =>$request->q15,
              'q15_upload' =>$name14,
              'q16' =>$request->q16,
              'q16_upload' =>$name15,
              'q17' =>$request->q17,
              'q17_upload' =>$name16,
              'q18' =>$request->q18,
              'q18_upload' =>$name17,
              'T1' =>$request->T1,
              'T2' =>$request->T2,
              'T3' =>$request->T3,
              'T4' =>$request->T4,
              'T5' =>$request->T5,
              'T6' =>$request->T6,
              'T7' =>$request->T7,
              'T8' =>$request->T8,
              'T9' =>$request->T9,
              'T10' =>$request->T10,
              'remarks' =>$request->remarks,
              'created_by'=>Session::get('user_idSession'),
              'created_datetime' =>$date,
              'draft'=>$draft
  
              ]);
            
        if($safety){

            return redirect()->action([SafetyDataEntryController::class, 'index1']);
            return back()->with('message','Save Successfully!');
        }else{
            return back()->with('message','Error While Approve');

        }
    }

	public function store(Request $request)
    {
		$date =  date('Y-m-d H:i:s');
      $transdate = date('Y-m-d');
       $transdate1 = date('m-d');
        $month = date('m', strtotime($transdate));
        $year  = date('Y', strtotime($transdate));
      $draft_till_date = date('Y-m-07', strtotime($request->month . ' + ' . '1' . ' months'));
if($request->q1 > 0){
	
	 $request->validate([
    'q1_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
], [
    'q1_upload.required' => 'The file upload is required.',
    'q1_upload.file' => 'The uploaded content must be a file.',
    'q1_upload.mimes' => 'The file must be a type of: pdf, ppt, docx, doc, pptx, xls, xlsx, jpeg, png, jpg, zip, rar, csv.',
    'q1_upload.max' => 'The file size must not exceed 5 MB.',
]);
		
		
	
}
if($request->q3 > 0){
	$request->validate([
            'q3_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
	
}

if($request->q5 > 0){
	$request->validate([
            'q5_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}

if($request->q10 > 0){
	$request->validate([
            'q10_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}

if($request->q11 > 0){
	$request->validate([
            'q11_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}

if($request->q12 > 0){
	$request->validate([
            'q12_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}

if($request->q13 > 0){
	$request->validate([
            'q13_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}
if($request->q14 > 0){
	$request->validate([
            'q14_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}

if($request->q15 > 0){
	$request->validate([
            'q15_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}
if($request->q16 > 0){
	$request->validate([
            'q16_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}
if($request->q17 > 0){
	$request->validate([
            'q17_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
        ]);
}
if($request->q18 > 0){
	$request->validate([
    'q18_upload' => 'required|file|mimes:pdf,ppt,docx,doc,pptx,xls,xlsx,jpeg,png,jpg,zip,rar,csv|max:5120'
], [
    'q18_upload.required' => 'The file upload is required.',
    'q18_upload.file' => 'The uploaded content must be a file.',
    'q18_upload.mimes' => 'The file must be a type of: pdf, ppt, docx, doc, pptx, xls, xlsx, jpeg, png, jpg, zip, rar, csv.',
    'q18_upload.max' => 'The file size must not exceed 5 MB.',
]);

}
 if($request->hasFile('q1_upload')){
              $location = 'documents/clm_pics/';
              $extension = '.'.$request->q1_upload->getClientOriginalExtension();
              $name = basename($request->q1_upload->getClientOriginalName(),$extension).time();
              //$name = $name.$extension;
			  $uid = Session::get('user_idSession');
			$datetime = date('Ymd_H_i_s');
			$randn = rand(0000,9999);
			$name = $uid.'_'.$datetime.'_'.$randn.$extension;
              $path = $request->q1_upload->move($location,$name);
             // $name = $location.$name;
              $name=$name;
            
          }else{
              $name=$request->q1_upload_q1;
          }
  if($request->hasFile('q2_upload')){
              $location1 = 'documents/clm_pics/';
              $extension1 = '.'.$request->q2_upload->getClientOriginalExtension();
              $name1 = basename($request->q2_upload->getClientOriginalName(),$extension1).time();
              //$name1 = $name1.$extension1;
			  $uid1 = Session::get('user_idSession');
			$datetime1 = date('Ymd_H_i_s');
			$randn1 = rand(0000,9999);
			$name1 = $uid1.'_'.$datetime1.'_'.$randn1.$extension1;
              $path1 = $request->q2_upload->move($location1,$name1);
              $name1 = $name1;
             }else{
              $name1='';
             }
          if($request->hasFile('q3_upload')){
              $location2 = 'documents/clm_pics/';
              $extension2 = '.'.$request->q3_upload->getClientOriginalExtension();
              $name2 = basename($request->q3_upload->getClientOriginalName(),$extension2).time();
             // $name2 = $name2.$extension2;
			  $uid2 = Session::get('user_idSession');
			$datetime2 = date('Ymd_H_i_s');
			$randn2 = rand(0000,9999);
			$name2 = $uid2.'_'.$datetime2.'_'.$randn2.$extension2;
              $path2 = $request->q3_upload->move($location2,$name2);
              $name2 = $name2;
             // $old_name = Permit::where('id',$id)->get();
              //if(file_exists($old_name[0]->upload_unique_id)){
                 // unlink($old_name[0]->upload_unique_id);
             // }
          }else{
              $name2=$request->q3_upload_q3;
          }
  if($request->hasFile('q4_upload')){
              $location3 = 'documents/clm_pics/';
              $extension3 = '.'.$request->q4_upload->getClientOriginalExtension();
              $name3 = basename($request->q4_upload->getClientOriginalName(),$extension3).time();
              //$name3 = $name3.$extension3;
			  $uid3 = Session::get('user_idSession');
			$datetime3 = date('Ymd_H_i_s');
			$randn3 = rand(0000,9999);
			$name3 = $uid3.'_'.$datetime3.'_'.$randn3.$extension3;
              $path3 = $request->q4_upload->move($location3,$name3);
              $name3 = $name3;
            
          }else{
              $name3='';
          }
  
          if($request->hasFile('q5_upload')){
              $location4 = 'documents/clm_pics/';
              $extension4 = '.'.$request->q5_upload->getClientOriginalExtension();
              $name4 = basename($request->q5_upload->getClientOriginalName(),$extension4).time();
              //$name4 = $name4.$extension4;
			  $uid4 = Session::get('user_idSession');
			$datetime4 = date('Ymd_H_i_s');
			$randn4 = rand(0000,9999);
			$name4 = $uid4.'_'.$datetime4.'_'.$randn4.$extension4;
              $path4 = $request->q5_upload->move($location4,$name4);
              $name4 = $name4;
           
          }else{
              $name4=$request->q5_upload_q5;
          }
  if($request->hasFile('q6_upload')){
              $location5 = 'documents/clm_pics/';
              $extension5 = '.'.$request->q6_upload->getClientOriginalExtension();
              $name5 = basename($request->q6_upload->getClientOriginalName(),$extension5).time();
              //$name5 = $name5.$extension5;
			  $uid5 = Session::get('user_idSession');
			$datetime5 = date('Ymd_H_i_s');
			$randn5 = rand(0000,9999);
			$name5 = $uid5.'_'.$datetime5.'_'.$randn5.$extension5;
              $path5 = $request->q6_upload->move($location5,$name5);
              $name5 = $name5;
           
          }else{
              $name5=$request->q6_upload_q6;
          }
  if($request->hasFile('q7_upload')){
              $location6 = 'documents/clm_pics/';
              $extension6 = '.'.$request->q7_upload->getClientOriginalExtension();
              $name6 = basename($request->q7_upload->getClientOriginalName(),$extension6).time();
              //$name6 = $name6.$extension6;
			  $uid6 = Session::get('user_idSession');
			$datetime6 = date('Ymd_H_i_s');
			$randn6 = rand(0000,9999);
			$name6 = $uid6.'_'.$datetime6.'_'.$randn6.$extension6;
              $path6 = $request->q7_upload->move($location6,$name6);
              $name6 = $name6;
           
          }else{
              $name6=$request->q7_upload_q7;
          }
  if($request->hasFile('q8_upload')){
              $location7 = 'documents/clm_pics/';
              $extension7 = '.'.$request->q8_upload->getClientOriginalExtension();
              $name7 = basename($request->q8_upload->getClientOriginalName(),$extension7).time();
              //$name7 = $name7.$extension7;
			  $uid7 = Session::get('user_idSession');
			$datetime7 = date('Ymd_H_i_s');
			$randn7 = rand(0000,9999);
			$name7 = $uid7.'_'.$datetime7.'_'.$randn7.$extension7;
              $path7 = $request->q8_upload->move($location7,$name7);
              $name7 = $name7;
           
          }else{
              $name7=$request->q8_upload_q8;
          }
          if($request->hasFile('q9_upload')){
              $location8 = 'documents/clm_pics/';
              $extension8 = '.'.$request->q9_upload->getClientOriginalExtension();
              $name8 = basename($request->q9_upload->getClientOriginalName(),$extension8).time();
              //$name8 = $name8.$extension8;
			  $uid8 = Session::get('user_idSession');
			$datetime8 = date('Ymd_H_i_s');
			$randn8 = rand(0000,9999);
			$name8 = $uid8.'_'.$datetime8.'_'.$randn8.$extension8;
              $path8 = $request->q9_upload->move($location8,$name8);
              $name8 = $name8;
           
          }else{
              $name8=$request->q9_upload_q9;
          }
          if($request->hasFile('q10_upload')){
              $location9 = 'documents/clm_pics/';
              $extension9 = '.'.$request->q10_upload->getClientOriginalExtension();
              $name9 = basename($request->q10_upload->getClientOriginalName(),$extension9).time();
              //$name9 = $name9.$extension9;
			  $uid9 = Session::get('user_idSession');
			$datetime9 = date('Ymd_H_i_s');
			$randn9 = rand(0000,9999);
			$name9 = $uid9.'_'.$datetime9.'_'.$randn9.$extension9;
              $path9 = $request->q10_upload->move($location9,$name9);
              $name9 = $name9;
           
          }else{
              $name9=$request->q10_upload_q10;
          }
          if($request->hasFile('q11_upload')){
              $location10 = 'documents/clm_pics/';
              $extension10 = '.'.$request->q11_upload->getClientOriginalExtension();
              $name10 = basename($request->q11_upload->getClientOriginalName(),$extension10).time();
              //$name10 = $name10.$extension10;
			  $uid10 = Session::get('user_idSession');
			$datetime10 = date('Ymd_H_i_s');
			$randn10 = rand(0000,9999);
			$name10 = $uid10.'_'.$datetime10.'_'.$randn10.$extension10;
              $path10 = $request->q11_upload->move($location10,$name10);
              $name10 = $name10;
           
          }else{
              $name10=$request->q11_upload_q11;
          }
          if($request->hasFile('q12_upload')){
              $location11 = 'documents/clm_pics/';
              $extension11 = '.'.$request->q12_upload->getClientOriginalExtension();
              $name11 = basename($request->q12_upload->getClientOriginalName(),$extension11).time();
              //$name11 = $name11.$extension11;
			  $uid11 = Session::get('user_idSession');
			$datetime11 = date('Ymd_H_i_s');
			$randn11 = rand(0000,9999);
			$name11 = $uid11.'_'.$datetime11.'_'.$randn11.$extension11;
              $path11 = $request->q12_upload->move($location11,$name11);
              $name11 = $name11;
           
          }else{
              $name11=$request->q12_upload_q12;
          }
          if($request->hasFile('q13_upload')){
              $location12 = 'documents/clm_pics/';
              $extension12 = '.'.$request->q13_upload->getClientOriginalExtension();
              $name12 = basename($request->q13_upload->getClientOriginalName(),$extension12).time();
              //$name12 = $name12.$extension12;
			  $uid12 = Session::get('user_idSession');
			$datetime12 = date('Ymd_H_i_s');
			$randn12 = rand(0000,9999);
			$name12 = $uid12.'_'.$datetime12.'_'.$randn12.$extension12;
              $path12 = $request->q13_upload->move($location12,$name12);
              $name12 = $name12;
           
          }else{
              $name12=$request->q13_upload_q13;
          }
          if($request->hasFile('q14_upload')){
              $location13 = 'documents/clm_pics/';
              $extension13 = '.'.$request->q14_upload->getClientOriginalExtension();
              $name13 = basename($request->q14_upload->getClientOriginalName(),$extension13).time();
              //$name13 = $name13.$extension13;
			  $uid13 = Session::get('user_idSession');
			$datetime13 = date('Ymd_H_i_s');
			$randn13 = rand(0000,9999);
			$name13 = $uid13.'_'.$datetime13.'_'.$randn13.$extension13;
              $path13 = $request->q14_upload->move($location13,$name13);
              $name13 = $name13;
           
          }else{
              $name13=$request->q14_upload_q14;
          }
  if($request->hasFile('q15_upload')){
              $location14 = 'documents/clm_pics/';
              $extension14 = '.'.$request->q15_upload->getClientOriginalExtension();
              $name14 = basename($request->q15_upload->getClientOriginalName(),$extension14).time();
              //$name14 = $name14.$extension14;
			  $uid14 = Session::get('user_idSession');
			$datetime14 = date('Ymd_H_i_s');
			$randn14 = rand(0000,9999);
			$name14 = $uid14.'_'.$datetime14.'_'.$randn14.$extension14;
              $path14 = $request->q15_upload->move($location14,$name14);
              $name14 = $name14;
           
          }else{
              $name14=$request->q15_upload_q15;
          }
          if($request->hasFile('q16_upload')){
              $location15 = 'documents/clm_pics/';
              $extension15 = '.'.$request->q16_upload->getClientOriginalExtension();
              $name15 = basename($request->q16_upload->getClientOriginalName(),$extension15).time();
              //$name15 = $name15.$extension15;
			  $uid15 = Session::get('user_idSession');
			$datetime15 = date('Ymd_H_i_s');
			$randn15 = rand(0000,9999);
			$name15 = $uid15.'_'.$datetime15.'_'.$randn15.$extension15;
              $path15 = $request->q16_upload->move($location15,$name15);
              $name15 = $name15;
           
          }else{
              $name15=$request->q16_upload_q16;
          }
           if($request->hasFile('q17_upload')){
              $location16 = 'public/documents/clm_pics/';
              $extension16 = '.'.$request->q17_upload->getClientOriginalExtension();
              $name16 = basename($request->q17_upload->getClientOriginalName(),$extension16).time();
              //$name16 = $name16.$extension16;
			  $uid16 = Session::get('user_idSession');
			$datetime16 = date('Ymd_H_i_s');
			$randn16 = rand(0000,9999);
			$name16 = $uid16.'_'.$datetime16.'_'.$randn16.$extension16;
              $path16 = $request->q17_upload->move($location16,$name16);
              $name16 = $name16;
           
          }else{
              $name16=$request->q17_upload_q17;
          }
          if($request->hasFile('q18_upload')){
              $location17 = 'documents/clm_pics/';
              $extension17 = '.'.$request->q18_upload->getClientOriginalExtension();
              $name17 = basename($request->q18_upload->getClientOriginalName(),$extension17).time();
              //$name17 = $name17.$extension17;
			  $uid17 = Session::get('user_idSession');
			$datetime17 = date('Ymd_H_i_s');
			$randn17 = rand(0000,9999);
			$name17 = $uid17.'_'.$datetime17.'_'.$randn17.$extension17;
              $path17 = $request->q18_upload->move($location17,$name17);
              $name17 = $name17;
           
          }else{
              $name17=$request->q18_upload_q18;
          }

if($request->submit == 'full_submit'){
    $draft='No';
}elseif($request->submit == 'draft'){
    $draft='Yes';
}

        $safety=  DB::table('safety_data_entry')->insert([
           // 'sl' => $serial_no,
            //'full_sl'   =>$full_sl,
            'financial_year'=>$request->month,
            'month' => $request->month,
			'division_id' => $request->division,
			'q1' =>$request->q1,
			'q1_upload' =>$name,
			'q2' => $request->q2,
			'q2_upload' =>$name1,
            'q3' =>$request->q3,
           'q3_upload' =>$name2,
			'q4' =>$request->q4,
           'q4_upload' =>$name3,
            'q5' =>$request->q5,
            'q5_upload' =>$name4,
            'q6' =>$request->q6,
           'q6_upload' =>$name5,
            'q7' =>$request->q7,
           'q7_upload' =>$name6,
            'q8' =>$request->q8,
            'q8_upload' =>$name7,
            'q9' =>$request->q9,
           'q9_upload' =>$name8,
            'q10' =>$request->q10,
            'q10_upload' =>$name9,
            'q11' =>$request->q11,
            'q11_upload' =>$name10,
            'q12' =>$request->q12,
            'q12_upload' =>$name11,
            'q13' =>$request->q13,
            'q13_upload' =>$name12,
            'q14' =>$request->q14,
            'q14_upload' =>$name13,
            'q15' =>$request->q15,
            'q15_upload' =>$name14,
            'q16' =>$request->q16,
            'q16_upload' =>$name15,
            'q17' =>$request->q17,
            'q17_upload' =>$name16,
            'q18' =>$request->q18,
            'q18_upload' =>$name17,
            'T1' =>$request->T1,
            'T2' =>$request->T2,
            'T3' =>$request->T3,
            'T4' =>$request->T4,
            'T5' =>$request->T5,
            'T6' =>$request->T6,
            'T7' =>$request->T7,
            'T8' =>$request->T8,
            'T9' =>$request->T9,
            'T10' =>$request->T10,
            'remarks' =>$request->remarks,
            'created_by'=>Session::get('user_idSession'),
            'created_datetime' =>$date,
            'draft'=>$draft ,
            'draft_till_date'=>$draft_till_date

			]);
		
        if($safety){
            return back()->with('message','Saved Successfully!');
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
