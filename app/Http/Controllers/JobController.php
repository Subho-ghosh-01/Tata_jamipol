<?php

namespace App\Http\Controllers;

use App\Job;
use App\Hazarde;
use App\Swp_File;
use Illuminate\Http\Request;
use App\Division;
use App\Department;
use App\JobLinking;
use Session;
use App\UserLogin;
use App\Permit;

class JobController extends Controller
{   
    public function index()
    {    
        $divisions = Division::all();
        if(Session::get('user_sub_typeSession') == 3){
            //$divisions = Division::all();
            $jobs = Job::orderBy('id','desc')->get();
        }
        else{
            $jobs = Job::leftjoin('jobs_linking','jobs_linking.job_id','=','jobs.id')
                ->where('jobs_linking.division_id',Session::get('user_DivID_Session'))
                // ->where('jobs_linking.department_id',Session::get('user_DeptID_Session'))
                ->select('jobs.*')->orderBy('id','desc')->get();
        }
        return view('admin.jobs.index',compact('jobs','divisions'));
    }

    
    public function create()
    {
        if(Session::get('user_sub_typeSession') == 3){
            $divisions = Division::all();
        }
        else{
            $divisions = Division::where('id',Session::get('user_DivID_Session'))->get();
        }

        $users     = UserLogin::all();
        return view('admin.jobs.create',compact('divisions','users'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'job_title'  => 'required',
            'swp_number' => 'required',
            'swp_file.*' => 'required|mimes:pdf|max:20480',
            'division_id.*'   => 'numeric',
            'department_id.*' => 'numeric',
            'section_id.*'    => 'numeric'
        ]);

        $job =  Job::create([
            'job_title'  => $request->job_title,
            'swp_number' => $request->swp_number,
            'high_risk'  => $request->high_risk ? $request->high_risk: 'off' ,
            'power_clearance' => $request->power_clearance ? $request->power_clearance: 'off',
            'confined_space'  => $request->confined_space ? $request->confined_space : 'off',
            //'issuer2'  => $request->issuer2 ? $request->issuer2 : 'off',
           // 'division_id'     => $request->division_id,
           // 'department_id'   => $request->department_id,
           // 'section_id'     =>  $request->section_id
        ]);

        // Multiple Uploade file
        $files = $request->file('swp_file');
        if($request->hasFile('swp_file')){
            foreach ($files as $file) {
                $location = '/documents/swp_files/';
                $extension = '.'.$file->getClientOriginalExtension();
                $name = basename($file->getClientOriginalName(),$extension).time();
                $name = $name.$extension;
                $path = $file->move($location,$name);
                $name = $location.$name;

                $swp_f = Swp_File::create([
                    'job_id'   =>$job->id,
                    'swp_file' =>$name,

                ]);
            }
        }

        //North Entry
        $data_hazard_north = array();
        foreach ($request->north_hazarde as $key => $value) {
            
            $north_haz    = $request->north_hazarde[$key];
            $north_pre    = $request->north_precaution[$key];

            $temp_array = array(
                'job_id'    => $job->id, 
                'hazarde'   => $north_haz, 
                'precaution'=> $north_pre,
                'direction' => 'North'
            );
            array_push($data_hazard_north,$temp_array);
        }   
        Hazarde::insert($data_hazard_north);
        
        //South Entry
        $data_hazard_south = array();
        foreach ($request->south_hazarde as $key => $value) {
            
            $north_haz    = $request->south_hazarde[$key];
            $north_pre    = $request->south_precaution[$key];
            // echo "$pre --> $hazarde<br>";

            $temp_array = array(
                'job_id'=> $job->id, 
                'hazarde'=> $north_haz, 
                'precaution'=> $north_pre,
                'direction' => 'South'
            );
            array_push($data_hazard_south,$temp_array);
        }   
        Hazarde::insert($data_hazard_south);

        //East Entry
        $data_hazared_east =array();
        foreach($request->east_hazarde as $key => $value){

            $east_haz = $request->east_hazarde[$key];
            $east_pre = $request->east_precaution[$key];

            $temp_array = array(
                'job_id'    => $job->id,
                'hazarde'   => $east_haz, 
                'precaution'=> $east_pre,
                'direction' => 'East'
            );
            array_push($data_hazared_east,$temp_array);
        }
        Hazarde::insert($data_hazared_east);

        //West Entry
        $data_hazared_west =array();
        foreach($request->west_hazarde as $key => $value){

            $west_haz = $request->west_hazarde[$key];
            $west_pre = $request->west_precaution[$key];

            $temp_array = array(
                'job_id'    => $job->id,
                'hazarde'   => $west_haz, 
                'precaution'=> $west_pre,
                'direction' => 'West'
            );
            array_push($data_hazared_west,$temp_array);
        }
        Hazarde::insert($data_hazared_west);

        
        //Top Entry
        $data_hazared_top =array();
        foreach($request->top_hazarde as $key => $value){

            $top_haz = $request->top_hazarde[$key];
            $top_pre = $request->top_precaution[$key];

            $temp_array = array(
                'job_id'    => $job->id,
                'hazarde'   => $top_haz, 
                'precaution'=> $top_pre,
                'direction' => 'Top'
            );
            array_push($data_hazared_top,$temp_array);
        }
        Hazarde::insert($data_hazared_top);

        //Buttom Entry
        $data_hazared_buttom =array();
        foreach($request->buttom_hazarde as $key => $value){
 
             $buttom_haz = $request->buttom_hazarde[$key];
             $buttom_pre = $request->buttom_precaution[$key];
 
             $temp_array = array(
                 'job_id'    => $job->id,
                 'hazarde'   => $buttom_haz, 
                 'precaution'=> $buttom_pre,
                 'direction' => 'Bottom'
             );
             array_push($data_hazared_buttom,$temp_array);
        }
        Hazarde::insert($data_hazared_buttom);
        
        //Multiple Division,Department,section Entry
        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        $date =  date('Y-m-d H:i:s'); 
        $multiple_division =array();
        foreach($request->division_id as $key => $value){
            
            $div  = $request->division_id[$key];
            $dept = $request->department_id[$key];
 
            $temp_array = array(
                'job_id'        => $job->id,
                'division_id'   => $div, 
                'department_id' => $dept,
                'created_at'    => $date
            );
            array_push($multiple_division,$temp_array);
        }
        JobLinking::insert($multiple_division);
        
        // //Multiple Area Clearence Linking with Job
        // $area_clear =array();
        // foreach($request->area_clearence as $key => $value){
        //     $area_id  = $request->area_clearence[$key];
 
        //     $temp_array = array(
        //         'job_id'        => $job->id,
        //         'area_clearence_id'   => $area_id, 
        //         'created_at'    => $date
        //     );
        //     array_push($area_clear,$temp_array);
        // }
        // JobAreaClearenceLinking::insert($area_clear);
        
        if($job && $swp_f){
            return back()->with('message','Job Added Suceessfully');
        }
        else{
            return back()->with('message','Ooops Error While Adding Job');
        }
    }
    

    public function show(Job $job)
    {
       
    }


    public function edit($jobIDenc)
    {
        $id = \Crypt::decrypt($jobIDenc);
        if($id){
            $job  = Job::where('id',$id)->first();
            if(Session::get('user_sub_typeSession') == 3){
                $divisions = Division::all();
                $departments = Department::all();
                $alldivisions = JobLinking::where('job_id',$id)->get();
            }
            else{
                $divisions = Division::where('id',Session::get('user_DivID_Session'))->get();
                $departments = Department::where('division_id',Session::get('user_DivID_Session'))->get();
                // $sections    = Section::where('id',Session::get('user_SecID_Session'))->get();
                $alldivisions = JobLinking::where('job_id',$id)->where('division_id',Session::get('user_DivID_Session'))->get();
            }
            // $divisions   = Division::all();
            $users        = UserLogin::all();
            $swp_files   = Swp_File::where('job_id',$job->id)->get();
            $hazared_all = Hazarde::where('job_id',$job->id)->get();
            return view('admin.jobs.edit',compact('job','divisions','departments','swp_files','hazared_all','alldivisions','users'));
        }
        else{
            return view('admin.error.404');
        }
    }


    public function update(Request $request, Job $job)
    {
        // dd($request->all());
        $request->validate([
            'job_title' => 'required',
            'swp_number' => 'required',
            // 'division_id.*'   => 'numeric',
            // 'department_id.*' => 'numeric',
            // 'section_id.*'    => 'numeric'
            // 'swp_file'=> 'mimes:pdf|max:5120',
        ]); 
        
        // Multiple Upload file
        $files = $request->file('swp_files');
        if($request->hasFile('swp_files')){
            foreach ($files as $file) {
                $location = 'public/documents/swp_files/';
                $extension = '.'.$file->getClientOriginalExtension();
                $name = basename($file->getClientOriginalName(),$extension).time();
                $name = $name.$extension;
                $path = $file->move($location,$name);
                $name = $location.$name;

                $swp_f = Swp_File::create([
                    'job_id'   =>   $job->id,
                    'swp_file' =>   $name,
                ]);
            }
        }
            
        //North Entry
        foreach($request->north_hazarde as $key => $value) {
            $unique_id   = $request->n_uni_id[$key];
            // print_r($unique_id);
            $temp_hz = Hazarde::where('id',$unique_id)->first();

            if($temp_hz != null){
                Hazarde::where('id',$unique_id)->update([
                    'hazarde'       => $request->north_hazarde[$key],
                    'precaution'    => $request->north_precaution[$key],
                ]);
            } 
            else{
                Hazarde::insert([
                    'job_id'    => $job->id,
                    'hazarde'   => $request->north_hazarde[$key],
                    'precaution'=> $request->north_precaution[$key],
                    'direction' => 'North'
                ]);
            }    
        }

        //Sount Entry
        foreach($request->south_hazarde as $key => $value) {
            $unique_id   = $request->s_uni_id[$key];
            // print_r($unique_id);
            $temp_hz = Hazarde::where('id',$unique_id)->first();

            if($temp_hz != null){
                Hazarde::where('id',$unique_id)->update([
                    'hazarde'       => $request->south_hazarde[$key],
                    'precaution'    => $request->south_precaution[$key],
                ]);
            } 
            else{
                Hazarde::insert([
                    'job_id'    => $job->id,
                    'hazarde'   => $request->south_hazarde[$key],
                    'precaution'=> $request->south_precaution[$key],
                    'direction' => 'South'
                ]);
            }    
        }

        //East Entry
        foreach($request->east_hazarde as $key => $value) {
            $unique_id   = $request->e_uni_id[$key];
            // print_r($unique_id);
            $temp_hz = Hazarde::where('id',$unique_id)->first();

            if($temp_hz != null){
                Hazarde::where('id',$unique_id)->update([
                    'hazarde'       => $request->east_hazarde[$key],
                    'precaution'    => $request->east_precaution[$key],
                ]);
            } 
            else{
                Hazarde::insert([
                    'job_id'    => $job->id,
                    'hazarde'   => $request->east_hazarde[$key],
                    'precaution'=> $request->east_precaution[$key],
                    'direction' => 'East'
                ]);
            }    
        }

        //West Entry
        foreach($request->west_hazarde as $key => $value) {
            $unique_id   = $request->w_uni_id[$key];
            // print_r($unique_id);
            $temp_hz = Hazarde::where('id',$unique_id)->first();

            if($temp_hz != null){
                Hazarde::where('id',$unique_id)->update([
                    'hazarde'       => $request->west_hazarde[$key],
                    'precaution'    => $request->west_precaution[$key],
                ]);
            } 
            else{
                Hazarde::insert([
                    'job_id'    => $job->id,
                    'hazarde'   => $request->west_hazarde[$key],
                    'precaution'=> $request->west_precaution[$key],
                    'direction' => 'West'
                ]);
            }    
        }

        //Top Entry
        foreach($request->top_hazarde as $key => $value) {
            $unique_id   = $request->t_uni_id[$key];
            // print_r($unique_id);
            $temp_hz = Hazarde::where('id',$unique_id)->first();

            if($temp_hz != null){
                Hazarde::where('id',$unique_id)->update([
                    'hazarde'       => $request->top_hazarde[$key],
                    'precaution'    => $request->top_precaution[$key],
                ]);
            } 
            else{
                Hazarde::insert([
                    'job_id'    => $job->id,
                    'hazarde'   => $request->top_hazarde[$key],
                    'precaution'=> $request->top_precaution[$key],
                    'direction' => 'Top'
                ]);
            }    
        }

        //Bottom Entry
        foreach($request->bottom_hazard as $key => $value) {
            $unique_id   = $request->b_uni_id[$key];
            // print_r($unique_id);
            $temp_hz = Hazarde::where('id',$unique_id)->first();

            if($temp_hz != null){
                Hazarde::where('id',$unique_id)->update([
                    'hazarde'       => $request->bottom_hazard[$key],
                    'precaution'    => $request->bottom_precaution[$key],
                ]);
            } 
            else{
                Hazarde::insert([
                    'job_id'    => $job->id,
                    'hazarde'   => $request->bottom_hazard[$key],
                    'precaution'=> $request->bottom_precaution[$key],
                    'direction' => 'Bottom'
                ]);
            }    
        }

        $jobupdate =  Job::where('id',$job->id)->update([
            'job_title'          => $request->job_title,
            'swp_number'           => $request->swp_number,
            'high_risk'    => $request->high_risk ? $request->high_risk : 'off' ,
            'power_clearance'      => $request->power_clearance ? $request->power_clearance : 'off',
            'confined_space'  => $request->confined_space ? $request->confined_space: 'off'
        ]);

        foreach($request->division_id as $key => $value)
        {
            $oldid   =  @$request->old_multipledivision_id[$key];
            $div  = $request->division_id[$key];
            $dept = $request->department_id[$key];

            if($oldid){
                JobLinking::where('id',$oldid)->update([
                    'division_id'   => $div,
                    'department_id' => $dept,
                ]);
            }
            else{
                if($div != ""){
                    JobLinking::insert([
                        'job_id'        => $job->id,
                        'division_id'   => $div,
                        'department_id' => $dept,
                    ]);
                }
            } 
        } 

        if($jobupdate){
            
            return back()->with('message','Job Update Suceessfully');
        }
        else{
             return back()->with('message','OOPs... Something Wrong');
        }
    }

   
    public function destroy(Job $job)
    {
        if($job->id != 0)
        {
            $permits = Permit::where('job_id','=',$job->id)->get();          
            if(@$permits[0]->id != ""){
                return redirect('admin/job')->with('message',"Job Can't be Deleted");
            }
            else{
                $job_destroy  = $job->delete();
                $delete_hz    = Hazarde::where('job_id',$job->id)->delete();
                $multiple_division  = JobLinking::where('job_id',$job->id)->delete(); 
                $swp_files    = Swp_File::where('job_id',$job->id)->get();
                foreach($swp_files as $swp_f) {
                    $swp_files  = Swp_File::where('job_id',$job->id)->delete(); 
                    if(file_exists($swp_f->swp_file)){
                        unlink($swp_f->swp_file);
                        return redirect('admin/job')->with('message' ,'Record Successfully Deleted!!');
                    }
                }  
            }
        }
    }

    public function getSixDirectional(Request $request,$id)
    {
        // dd($request->all());
        // return $id;
        if($request->ajax()){
            $hazared_all = Hazarde::where('job_id',$id)->get();
            $toReturn = "";
            $toReturn = "<table class='table'>";
            $toReturn .= "<tbody>";
                    $toReturn .= "<tr>";
                        $toReturn .= "<th>Direction</th>";
                        $toReturn .= "<th>Hazards</th>";
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
                    if($hazared_all[$key]->direction == 'Bottom'){
                            $toReturn .= "<tr>";
                                $toReturn .= "<td>Buttom</td>";
                                $toReturn .= "<td>".$hazared_all[$key]->hazarde."</td>";
                                $toReturn .= "<td>".$hazared_all[$key]->precaution."</td>";
                            $toReturn .= "</tr>";
                    }
                }

            $toReturn .= "</tbody>";
            $toReturn .= "</table>";
            echo $toReturn;
        }
        else{
            echo $toReturn = "Not Found Hazard to View";
        }
    }
    public function JobDepartment($id)
    {
        $toReturn = Department::where('division_id',$id)->get();
        return $toReturn;
    }

    public function DeleteSwpFile($id)
    {
        $delete_link = Swp_File::where('id', $id)->first();

        if(file_exists($delete_link->swp_file)){
            unlink($delete_link->swp_file);
        }
        $delete = Swp_File::where('id', $id)->delete();    
                      
    }
    public function getListJobs(Request $request){
        // dd($request->all());
        if($request->input('division_id')<>'' && $request->input('department_id')<>'')
        {    
            $divisions = Division::all();
            $jobs = Job::leftjoin('jobs_linking','jobs_linking.job_id','=','jobs.id')
                ->where('jobs_linking.division_id',$request->input('division_id'))
                ->where('jobs_linking.department_id',$request->input('department_id'))
                ->select('jobs.*')->get();
        }
        return view('admin.jobs.index',compact('jobs','divisions'));
    }

    public function Delete($id)
    {
        $toReturn = JobLinking::where('id',$id)->delete();
        return back()->with('message','Deleted');
    }

}
