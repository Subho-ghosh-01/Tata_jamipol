<?php 
use App\Department;
use App\UserLogin;
use App\ShutdownChild;
use App\RenewPermit;

?>

@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.list_permit.index')}}">List Permit</a></li>
@endsection                        
@section('content')
<form action="{{ route('admin.renew_issuer') }}" method="post" enctype="multipart/form-data"> 
    @csrf
    <div class="form-group-row">
        <div class="col-sm-12">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    <div class="form-group-row">
        <div class="col-sm-12" style="text-align:center;">
            @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message')}}
            </div>
            @endif
        </div>
    </div>
    <div id="first">
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Divisions</label>
            <div class="col-sm-10">
                <select class="form-control" id="divisionID" readonly="readonly"> 
                        @if($permit_division_datas->count() > 0)
                            @foreach($permit_division_datas as $permit_division_data)
                                <option value="{{$permit_division_data->divisionId}}" {{ 'selected' }}>{{$permit_division_data->divisionName}}</option>
                            @endforeach
                        @endif 
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Department</label>
            <div class="col-sm-10">
            <?php $depart_list = Department::where('id',@$permit_division_datas[0]->department_id)->get(); ?>
                <select class="form-control" id="departmentID" name="department_id" readonly="readonly"> 
                    @if($depart_list->count() > 0)
                        @foreach($depart_list as $depart)
                            <option value="{{$depart->id}}" {{ 'selected' }}>{{$depart->department_name}}</option>
                        @endforeach
                    @endif 
                </select>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Order No</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="" value="{{$permit_division_data->permitOrder}}" readonly><br>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Order Validity</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="" value="{{$permit_division_data->permitOrderValidity}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Start Date</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id=""  autocomplete="off"  value="{{ date('Y-m-d h:i', strtotime($permit_division_data->startDate))  }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">End Date</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="" autocomplete="off" value="{{ date('Y-m-d h:i', strtotime($permit_division_data->endDate))}}" readonly>   
            </div>
        </div>
        <div class="form-group row">
            <label for="job_description" class="col-sm-2 col-form-label">Job Description</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="" value="" readonly>{{$permit_division_data->JobDescription}}</textarea>   
            </div>
        </div>
        <div class="form-group row">
            <label for="job_location" class="col-sm-2 col-form-label">Job Location</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="" id="" value="{{$permit_division_data->JobLocation}}" readonly>   
            </div>
        </div>

        @if(Session::get('user_typeSession') == 2)
        <div class="form-group row">
            <label for="job_location" class="col-sm-2 col-form-label">Permit Requester Name</label>
            <div class="col-sm-10">
            @php  $super_name = DB::table('vendor_supervisors')->where('id',$permit_division_data->permitRequestname)->get(); @endphp 
                <input type="text" class="form-control" name="" value="{{@$super_name[0]->supervisor_name}}" readonly>
            </div>
        </div>
        @endif
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Job</label>
            <div class="col-sm-10">
                <select class="form-control" id="jobID" readonly>
                    @if($job_datas->count() > 0)
                        @foreach($job_datas as $job_data)
                            <option value="{{$job_data->jobId}}"  {{ 'selected' }}>{{$job_data->jobTitle}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">SWP/SOP</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="" value="{{$job_data->jobSwpNumber}}" readonly> 
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">SWP File</label>
            <div class="col-sm-10">
                @if($swp_files->count() > 0)
                    @foreach($swp_files as $s)
                        <a href="../../../{{$swp_files[0]->swp_file}}" target="_blank"><img src="{{ URL::to('public/images/pdf_download.png')}}"></a>
                    @endforeach
                @endif   
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Type of Job</label>
            <div class="col-sm-3">
                <table style="">
                    <tr><td><span>(A) Welding/Gas Cutting</span></td>
                        <td><input type="checkbox" name="welding_gas" disabled="" <?php if($permit_division_data->welding_gas == 'on') echo 'checked' ?> ></td></tr>
                    <tr><td><span>(B) Riggine / Fittings</span></td>
                        <td><input type="checkbox" name="riggine" disabled="" <?php if($permit_division_data->riggine == 'on') echo 'checked' ?> ></td></tr>
                    <tr><td><span>(C) Working at Height</span></td>
                        <td><input type="checkbox" name="working_at_height" disabled="" <?php if($permit_division_data->working_at_height == 'on') echo 'checked' ?> ></td></tr>
                </table>
            </div>
            <div class="col-sm-3">
                 <table style="">
                    <tr><td><span>(D) Hydraulic/Pneumatic</span></td>
                        <td><input type="checkbox" name="hydraulic_pneumatic" disabled="" <?php if($permit_division_data->hydraulic_pneumatic == 'on') echo 'checked' ?> ></td></tr>
                    <tr><td><span>(E)Painting/Cleaning</span></td>
                        <td><input type="checkbox" name="painting_cleaning" disabled="" <?php if($permit_division_data->painting_cleaning == 'on') echo 'checked' ?> ></td></tr>
                    <tr><td><span>(F) Gas</span></td>
                        <td><input type="checkbox" name="gas" disabled="" <?php if($permit_division_data->gas == 'on') echo 'checked' ?> ></td></tr>
                </table>
            </div>
            <div class="col-sm-3">
                 <table style="">
                    <tr><td><span>(G) Others (Specify)</span></td>
                        <td><input type="checkbox" name="others" id="OTHER"  disabled="" <?php if($permit_division_data->others == 'on') echo 'checked' ?> ></td></tr>
                    <tr><td id="show_specify" <?php if($permit_division_data->others == 'on') { ?> style="display: block" <?php } ?> >
                            <textarea class="form-control"  id="specify_others" readonly="" name="specify_others">{{$permit_division_data->specify_others}}</textarea>
                        </td>
                    </tr>
                    
                </table>
            </div>
        </div>

        @if($permit_hazards->count() > 0)
            @foreach($permit_hazards as $key => $value)
            <div class="form-group row">
                @if($key == 0)
                    <label for="form-control-label" class="col-sm-2 col-form-label">Six Directional Hazards</label>
                @else
                    <label for="form-control-label" class="col-sm-2 col-form-label"></label>   
                @endif
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="" value="{{$permit_hazards[$key]->dir}}" readonly><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="" value="{{$permit_hazards[$key]->hazard}}" readonly><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="" value="{{$permit_hazards[$key]->precaution}}" readonly><br>
                    </div>
                    
            </div>
            @endforeach
        @endif
    
     
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Executing Agency</label>
            <div class="col-sm-10">
                <select class="form-control" id="issuer_id" readonly>
                    <?php
                        $get_issuer_id = DB::table('userlogins')->where('id',$permit_division_data->permitIssuerID)->get();
                    ?>
                    <option value="{{@$get_issuer_id[0]->id}}">{{@$get_issuer_id[0]->name}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Gate Pass Details</label>
            <div class="col-sm-10">
                <table class="table table-bordered">
                    <thead> 
                        <tr> 
                            <th>Employee Name</th>
                            <th>Gate Pass No.</th>
                            <th>Designation</th>
                            <th>Age</th>   
                            <th>Expired</th>              
                        </tr>
                    </thead>
                    <tbody>
                        @if($gate_pass_details->count() > 0)
                            @foreach($gate_pass_details as $key => $value)
                                <tr>
                                    <td><input type="text" class="form-control"   value="{{$gate_pass_details[$key]->employee_name}}"  readonly="readonly"></td>
                                    <td><input type="text" class="form-control"   value="{{$gate_pass_details[$key]->gate_pass_no}}"  readonly="readonly"></td>
                                    <td><input type="text" class="form-control"   value="{{$gate_pass_details[$key]->designation}}" readonly="readonly"></td>
                                    <td><input type="text" class="form-control"   value="{{$gate_pass_details[$key]->age}}" readonly="readonly"></td>
                                    <td><input type="text" class="form-control"   value="{{$gate_pass_details[$key]->expirydate}}" readonly="readonly"></td>
                                </tr>
                            @endforeach
                        @endif    
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead> 
                        <tr> 
                            <th>Details</th>
                            <th>Access</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>Safe Work Procedure has been made & approved.</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="safe_work" value="yes"  disabled  @if($permit_division_data->PermitSafeWork == 'yes') {{'checked'}} @endif>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="safe_work" value="no"  disabled  @if($permit_division_data->PermitSafeWork == 'no') {{'checked'}} @endif>&nbsp; No  
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class=""  name="safe_work" value="na"  disabled   @if($permit_division_data->PermitSafeWork == 'na') {{'checked'}} @endif>&nbsp; NA 
                                    </label>
                                </td>                        
                            </div>
                        </tr>      
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>All persons are medically fit and have adequate quality of personal safety appliances. They will use it
                                compulsorily as per requirement of the job. All person will follow safety norms and conditions of Jamipol.</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                <label class="form-check-label">
                                    <input type="radio" class="" name="all_person" value="yes" disabled @if($permit_division_data->PermitAll_person == 'yes') {{'checked'}} @endif>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class=""  name="all_person" value="no" disabled @if($permit_division_data->PermitAll_person == 'no') {{'checked'}} @endif>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class=""  name="all_person" value="na" disabled @if($permit_division_data->PermitAll_person == 'na') {{'checked'}} @endif>&nbsp; NA 
                                </label>
                                </td>                        
                            </div>  
                        </tr>      
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>Worker working on height must have height pass from NTTF/ Competent authority.</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                <label class="form-check-label">
                                    <input type="radio" class="" name="worker_working" value="yes" disabled @if($permit_division_data->permitWorkerWorking == 'yes') {{'checked'}} @endif>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class="" name="worker_working" value="no" disabled  @if($permit_division_data->permitWorkerWorking == 'no') {{'checked'}} @endif>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class="" name="worker_working" value="na" disabled @if($permit_division_data->permitWorkerWorking == 'na') {{'checked'}} @endif>&nbsp; NA 
                                </label>
                                </td>                        
                            </div> 
                        </tr>      
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>All lifting tools/Load bearing tools, tackles & safety appliances are in good condition with valid test certificate</strong> </td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                <label class="form-check-label">
                                    <input type="radio" class="" name="all_lifting_tools" value="yes" disabled  @if($permit_division_data->PermitAll_lifting_tools == 'yes') {{'checked'}} @endif>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class=""  name="all_lifting_tools" value="no" disabled  @if($permit_division_data->PermitAll_lifting_tools == 'no') {{'checked'}} @endif>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class=""  name="all_lifting_tools" value="na" disabled  @if($permit_division_data->PermitAll_lifting_tools == 'na') {{'checked'}} @endif>&nbsp; NA 
                                </label>
                                </td>                        
                            </div>
                        </tr>      
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>All safety requirement for working at height will be arranged I made and checked before use as per job
                                requirement ~ Access ladder, rest platform, Extended platform, scaffolding, hand rail, fencing of down area /
                                ground area, Use of full body harness etc.</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                <label class="form-check-label">
                                    <input type="radio" class="" name="all_safety_requirement" value="yes"  disabled  @if($permit_division_data->permitAll_safety_requirement == 'yes') {{'checked'}} @endif>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class=""  name="all_safety_requirement" value="no"  disabled  @if($permit_division_data->permitAll_safety_requirement == 'no') {{'checked'}} @endif>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class=""  name="all_safety_requirement" value="na"  disabled  @if($permit_division_data->permitAll_safety_requirement == 'na') {{'checked'}} @endif>&nbsp; NA 
                                </label>
                                </td>                        
                            </div>
                        </tr>      
                        <tr>
                            <div class="col-sm-8">
                                <td> <strong>All persons are trained on Safe Working Procedure (SWP)</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                <label class="form-check-label">
                                    <input type="radio" class="" name="all_person_are_trained" value="yes" disabled  @if($permit_division_data->PermitAll_person_are_trained == 'yes') {{'checked'}} @endif>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class=""  name="all_person_are_trained" value="no" disabled @if($permit_division_data->PermitAll_person_are_trained == 'no') {{'checked'}} @endif>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class=""  name="all_person_are_trained" value="na" disabled  @if($permit_division_data->PermitAll_person_are_trained == 'na') {{'checked'}} @endif>&nbsp; NA 
                                </label>
                                </td>                        
                            </div>
                        </tr>      
                        <tr>
                            <div class="col-sm-8">
                                <td> <strong>Ensure the applicable activity check list, signed by permit requester, receiver and verified by permit issuer.
                                Checklist shall be attached with permit to work system</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                <label class="form-check-label">
                                    <input type="radio" class="" name="ensure_the_appplicablle" value="yes"  disabled @if($permit_division_data->permitEnsure_the_appplicablle == 'yes') {{'checked'}} @endif>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class=""  name="ensure_the_appplicablle" value="no" disabled  @if($permit_division_data->permitEnsure_the_appplicablle == 'no') {{'checked'}} @endif>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class=""  name="ensure_the_appplicablle" value="na" disabled  @if($permit_division_data->permitEnsure_the_appplicablle == 'na') {{'checked'}} @endif>&nbsp; NA 
                                </label>
                                </td>                        
                            </div>
                        </tr>      
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                @if($permit_division_data->PermitStatus != 'Requested')
                <input class="checkbox-inline" type="checkbox" disabled name="agree" id="agree" checked>
                @else 
                <input class="checkbox-inline" type="checkbox" name="agree" id="agree"> 
                @endif &nbsp;
                <b><i>Training imparted by
               
                @if($permit_division_data->PermitStatus != 'Requested') 
                <?php $issuer = DB::table('userlogins')->where('id',$permit_division_datas[0]->permitIssuerID)->get();
                    echo @$issuer[0]->name;
                ?>         
                @else
                me
                @endif
                to all involved in the job and all of their queries have been clarified. Also Risks involved in the job
                and its mitigation plan have also been discussed.I have visited the site and checked. Hence this clearance is being given from site and not from office.</i></b>
            </div>
        </div>

        @if($permit_division_data->PermitStatus == 'Requested')
        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <a herf=""> <input type="button" id="next" name="button" class="btn btn-primary" value="Next" onclick="showdown();"> </a> 
            </div>
        </div>
        @endif
    </div>

<!---------------------------------------------------------------------------------------------------------------- -->
    <a name="anchor"></a>  @if($permit_division_data->PermitStatus != 'Requested') <div id="second" style="display:block"> @else <div id="second" style="display:none"> @endif
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Post Site Picture </label>
                <div class="col-sm-3">
                    <input type="file" class="form-control-file" disabled=""    name="post_site_pic" id="">    
                    <div class="img-thumbnail">
                    <img src="@if(isset($permit_division_data))
                                {{url('')}}/{{$permit_division_data->PermitSitePic}}
                            @endif"
                        class="img-fluid" alt=""  width="100" height="100"/>
                    </div> 
                </div>
            </div>
        

            <!-- Power Clearance Details  for fill -->
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Shut Down Required? </label>
                <div class="col-sm-10">
                    <p class="form-control">
                        @if($permit_division_data->PermitPowerClearance == 'on')
                                {{"Yes"}}
                        @else
                                {{"No"}}
                        @endif
                    </p>     
                </div>
            </div>
        <div <?php if(@$permit_division_data->PermitPowerClearance == 'off') { ?> style="display: none"; <?php } ?>>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Select Voltage Level </label>
                <div class="col-sm-10">
                    <select class="form-control" id="voltagelevel" name="vlevel" @if($permit_division_data->PermitPowerClearance == 'on') required @endif  disabled>
                        <option value="">Select Voltage </option>
                        <option value=".132KV" <?php if($permit_division_data->vlevel === ".132KV") echo 'selected'; ?> >132KV</option>
                        <option value=".33KV" <?php if($permit_division_data->vlevel === ".33KV") echo 'selected'; ?> >33KV</option>
                        <option value=".11KV" <?php if($permit_division_data->vlevel === ".11KV") echo 'selected'; ?> >11KV</option>
                        <option value=".LT" <?php if($permit_division_data->vlevel === ".LT") echo 'selected'; ?> >LT</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Issuer Name</label>
                <div class="col-sm-4">
                    <select class="form-control" id="isspower" name="issuer_power" @if($permit_division_data->PermitPowerClearance == 'on')  required @endif  disabled>
                        @if($permit_division_datas[0]->issuer_power)
                            @php @$isspower = UserLogin::where('id',$permit_division_datas[0]->issuer_power)->first();  @endphp
                            <option value="{{@$isspower->id}}">{{@$isspower->name}}</option>
                        @else
                            <option value="">Power Clearance Issuer</option>
                        @endif
                    </select>
                </div>
                <div class="col-sm-3">
                    <select class="form-control" id="license_numberISS" name="electrical_license_issuer" disabled >
                        @if(@$permit_division_data->electrical_license_issuer)
                            <option value="{{@$permit_division_data->electrical_license_issuer}}">{{@$permit_division_data->electrical_license_issuer}} </option>
                        @else
                            <option value="">Electrical license</option>
                        @endif 
                    </select>
                </div>
                <div class="col-sm-3">
                    <select class="form-control" id="validity_dateISS" name="validity_date_issuer" disabled >
                        @if(@$permit_division_data->electrical_license_issuer)
                            <option value="{{@$permit_division_data->validity_date_issuer}}">{{@$permit_division_data->validity_date_issuer}} </option>
                        @else
                            <option value="">Validity Date</option>
                        @endif 
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Receiver Name</label>
                <div class="col-sm-4">
                    <select class="form-control" id="receiverpower" name="rec_power" @if($permit_division_data->PermitPowerClearance == 'on')  required  @endif disabled>
                        @if($permit_division_datas[0]->rec_power)
                            @php @$recpower = UserLogin::where('id',$permit_division_datas[0]->rec_power)->first();  @endphp
                            <option value="{{@$recpower->id}}">{{@$recpower->name}}</option>
                        @else
                        <option value="">Power Clearance Receiver</option>
                        @endif 
                    </select>
                </div>
                <div class="col-sm-3">
                    <select class="form-control" id="license_numberREC" name="electrical_license_rec" disabled>
                        @if(@$permit_division_data->electrical_license_rec)
                            <option value="{{@$permit_division_data->electrical_license_rec}}">{{@$permit_division_data->electrical_license_rec}} </option>
                        @else
                            <option value="">Electrical license</option>
                        @endif 
                    </select>
                </div>
                <div class="col-sm-3">
                    <select class="form-control" id="validity_dateREC" name="validity_date_rec" disabled>
                        @if(@$permit_division_data->validity_date_rec)
                            <option value="{{@$permit_division_data->validity_date_rec}}">{{@$permit_division_data->validity_date_rec}} </option>
                        @else
                            <option value="">Validity Date</option>
                        @endif 
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Number </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="power_clearance_number" @if($permit_division_data->PermitPowerClearance == 'on') required @endif disabled value="{{$permit_division_data->PermitPower_clearance_number}}">         
                </div>
            </div>
                       
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearence Details<p><b>(if Not Applicable, Please mention the reason/remarks.)</b></p></label>
                <div class="col-sm-9">
                    <table class="table table-bordered">
                        <thead> 
                            <tr> 
                                <th>Positive Isolation Lock Number </th>
                                <th>Equipment</th>
                                <th>Location</th>      
                            </tr>
                        </thead>
                        @if($power_clearances->count() > 0)
                            @foreach($power_clearances as $key => $value)
                                <tbody id="append_power_clearence">
                                    <tr class="remove_tr_power_cls" id="remove_tr_power_cls">
                                        <input type="hidden" class="form-control" name="p_id[]" value="{{$power_clearances[$key]->id}}" id="">
                                        <td><input type="text" class="form-control" name="positive_isolation[]" value="{{$power_clearances[$key]->positive_isolation_no}}" readonly></td>
                                        <td><input type="text" class="form-control" name="equipment[]" value="{{$power_clearances[$key]->equipment}}"  readonly></td>
                                        <td><input type="text" class="form-control" name="power_location[]" value="{{$power_clearances[$key]->location}}"  readonly></td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
            <!-- End Power Clearance Details  for fill -->
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Any Other Type Isolation</label>
                <div class="col-sm-10">
                    <select class="form-control" name="other_Isolation" onchange="OtherIsolation(this.value)" disabled>
                        <option value="">Select </option>
                        <option value="yes" <?php if($permit_division_data->other_isolation == 'yes') echo 'selected' ?> >Yes</option>
                        <option value="no" <?php if($permit_division_data->other_isolation == 'no') echo 'selected' ?> >No</option>
                    </select>
                </div>
            </div>
            <div  style="display: none"; id="ShowotherIsolation">
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Fill Other Details</label>
                    <div class="col-sm-9">
                        <table class="table table-bordered">
                                <thead> 
                                    <tr> 
                                        <th>Positive Isolation Lock Number </th>
                                        <th>Equipment</th>
                                        <th>Location</th>      
                                    </tr>
                                </thead> 
                                <tbody id="append_power_clearence_other">
                                    <tr class="remove_tr_power_cls_other">
                                        <td><input type="text" class="form-control" name="positive_other[]"></td>
                                        <td><input type="text" class="form-control" name="equipment_other[]"></td>
                                        <td><input type="text" class="form-control" name="location_other[]"></td>
                                    </tr>
                                </tbody> 
                        </table>
                    </div>
                
                </div>
            </div>
            @if($permit_division_data->other_isolation == 'yes')
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Fill Other Details</label>
                    <div class="col-sm-9">
                        <table class="table table-bordered">
                                <thead> 
                                    <tr> 
                                        <th>Positive Isolation Lock Number </th>
                                        <th>Equipment</th>
                                        <th>Location</th>      
                                    </tr>
                                </thead> 
                                @if($otherisolation->count() > 0)
                                    @foreach($otherisolation as $key => $value)
                                    <tbody id="">
                                        <tr class="">
                                            <td><input type="text" class="form-control" name="positive_other[]" value="{{$otherisolation[$key]->positive_other}}" disabled></td>
                                            <td><input type="text" class="form-control" name="equipment_other[]" value="{{$otherisolation[$key]->equipment_other}}" disabled></td>
                                            <td><input type="text" class="form-control" name="location_other[]" value="{{$otherisolation[$key]->location_other}}" disabled></td>
                                        </tr>
                                    </tbody> 
                                    @endforeach
                                @endif
                        </table>
                    </div>
                </div>
            @endif

            <!-- Confined Space  Details  for fill -->
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Confined Space?</label>
                <div class="col-sm-10">
                    <p class="form-control">
                        @if($permit_division_data->PermitConfinedSpace == 'on')
                                {{"Yes"}}
                        @else
                                {{"No"}}
                        @endif
                    </p>      
                </div>
            </div>
        <div <?php if(@$permit_division_data->PermitConfinedSpace == 'off') { ?> style="display: none"; <?php } ?>>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Confined Space Details<p><b>(if Not Applicable, Please mention the reason/remarks.)</b></p></label>
                <div class="col-sm-9">
                    <table class="table table-bordered">
                        <thead> 
                            <tr> 
                                <th>Clearance No</th>
                                <th>Depth </th>
                                <th>Location</th>      
                            </tr>
                        </thead>
            
                        @if($confined_spaces->count() > 0)
                            @foreach($confined_spaces as $key => $value) 
                            <tbody id="append_confined_deatils">
                                <tr class="remove_confined" id="remove_confined">
                                    <input type="hidden" class="form-control" name="c_id[]" value="{{$confined_spaces[$key]->id}}" id="">
                                    <td><input type="text" class="form-control" name="clearance_no[]" value="{{$confined_spaces[$key]->clearance_no}}"   readonly></td>
                                    <td><input type="text" class="form-control" name="depth[]"  value="{{$confined_spaces[$key]->depth}}"   readonly></td>
                                    <td><input type="text" class="form-control" name="confined_location[]" value="{{$confined_spaces[$key]->location}}"   readonly></td>
                                </tr>
                            </tbody>
                           @endforeach
                        @endif 
                    </table>
                </div>
            </div>
        </div>
            <!-- End Power Clearance Details  for fill -->
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">High Risk Job? </label>
            <div class="col-sm-10">
                <p class="form-control">
                    @if($permit_division_data->PermitHighRisk == 'on')
                            {{"Yes"}}
                    @else
                            {{"No"}}
                    @endif
                </p>       
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Area Clearance Required </label>
            <div class="col-sm-10">
                <input type="checkbox" class="form-control-check" name="area_clearance_req" disabled="" id="area_cleck" checked>         
            </div>
        </div>
        <div class="form-group row" id="ownerAgency">
            <label for="form-control-label" class="col-sm-2 col-form-label">Owner Agency Name </label>
            <div class="col-sm-10">
                <select class="form-control" name="area_clearence_id" id="emp_id" disabled="">
                    <?php $users = UserLogin::where('id',@$permit_division_data->PermitArea_clearenceId)->first(); ?>
                    <option>{{@$users->name}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Special Instruction</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="s_instruction" readonly="">{{$permit_division_data->s_instruction}}</textarea>
            </div>
        </div>
         <div class="form-group row">
            <div class="col-sm-12 text-center">
                <a class="btn btn-info btn-sm" href="{{ URL('admin/permit/text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla/'.base64_encode($id)) }}">Preview</a>
				
            </div>
        </div>



    </div>
</form>
@endsection
@section('scripts')
<script>
    //click to next page 
    $(document).ready(function(){
        $('#prcvsubmit').prop('disabled', true);
        $('#next').prop('disabled', true);
        $('#agree').click(function(){
            if($(this).is(':checked'))
            {
                $('#next').prop('disabled', false);
            }
            else
            {
                $('#next').prop('disabled', true);
            }
        });

    });

    function scrollToAnchor(aid){
        var aTag = $("a[name='"+ aid +"']");
        $('html,body').animate({scrollTop: aTag.offset().top},'slow');
    }

    function showdown()
    {         
        $("#second").css({'display':'block'});
        scrollToAnchor('anchor');
    }
    
    $('#area_cleck').click(function(){
        // if($(this).is(':checked'))
        // {
        //     $('#ownerAgency').show();
        //     $('#emp_id').prop('required', true);
        // }
        // else
        // {
        //     $('#emp_id').prop('required', false);
        //     $('#ownerAgency').hide();
        // }
    });
  
    function OtherIsolation(otheriso) {
        if(otheriso == 'yes'){
             $('#ShowotherIsolation').show();
        }
        else if(otheriso == 'no'){
            $('#ShowotherIsolation').hide();


        }
    }
</script>
@endsection