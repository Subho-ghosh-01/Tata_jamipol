<?php 
use App\Department;
use App\UserLogin;
use App\Division;

$safety_data= DB::table('safety_data_entry')->where('id',$id)->first();
@$division_p = Division::where('id',@$safety_data->division_id)->first();

?>
 
@extends('admin.app')
@section('breadcrumbs')
   <!-- <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.list_permit.index')}}"></a></li>-->
@endsection                        
@section('content')
 
<div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Financial Year<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control rec" name="" value="{{date('Y', strtotime($safety_data->financial_year))}}" readonly>
        </div>

    </div>
  <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Month<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
        	@php
if($safety_data->month==1){
	$month='Janaury';
}elseif($safety_data->month==2){
	$month='February';
}elseif($safety_data->month==3){
	$month='March';
}elseif($safety_data->month==4){
	$month='April';
}elseif($safety_data->month==5){
	$month='May';
}elseif($safety_data->month==6){
	$month='June';
}elseif($safety_data->month==7){
	$month='July';
}elseif($safety_data->month==8){
	$month='August';
}elseif($safety_data->month==9){
	$month='September';
}elseif($safety_data->month==10){
	$month='October';
}elseif($safety_data->month==11){
	$month='November';
}elseif($safety_data->month==12){
	$month='December';
}

        	@endphp
            <input type="text" class="form-control rec" name="" value="{{date('F', strtotime($safety_data->month))}}" readonly>

        </div>
    </div>
     <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Branch<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
             <input type="text" class="form-control rec" name="" value="{{@$division_p->name}}" readonly>
        </div>
    </div>
<center><h4 ><b>Key Performance Indicator</b></h4></center>
   <fieldset class="border p-2">
<legend class="w-auto">Leading Indicator</legend>
     <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">1. No of Safety Training Session For Employee<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q1" value="{{@$safety_data->q1}}" readonly>
        </div>
		 
        <div class="col-sm-4">
             
@if($safety_data->q1_upload !='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q1_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
@else
			
              <button class="btn">No File</button>
@endif
        </div>
    </div>


    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">2. No of Employees  Attended  Safety Training<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q2" value="{{@$safety_data->q2}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q2_upload!='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q2_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>

<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">3. No of Safety Training for Contractor Employees  <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q3" value="{{@$safety_data->q3}}" readonly>
        </div>
	
        <div class="col-sm-4">
			@if($safety_data->q3_upload!='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q3_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">4. No of Contractor Employees  attended the training<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q4" value="{{@$safety_data->q4}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q4_upload !='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q4_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
   <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">5. No of Health Awareness Session <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q5" value="{{@$safety_data->q5}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q5_upload !='')
           <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q5_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
   <!-- <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">6. No of Closed Observation <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q6" value="{{@$safety_data->q6}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q6_upload !='')
            <a href="https://wps.jamipol.com/public/documents/clm_pics/{{$safety_data->q6_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">7. No of Opened Observation <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q7" value="{{@$safety_data->q7}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q7_upload !='')
             <a href="https://wps.jamipol.com/public/documents/clm_pics/{{$safety_data->q7_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">8. No of Total Safety Observation <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q8" value="{{@$safety_data->q8}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q8_upload !='')
             <a href="https://wps.jamipol.com/public/documents/clm_pics/{{$safety_data->q8_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
     <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">9. No of Unsafe Situtions rectified <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q9" value="{{@$safety_data->q9}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q9_upload !='')
            <a href="https://wps.jamipol.com/public/documents/clm_pics/{{$safety_data->q9_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>-->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">6. No of Mass Meeting <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q10" value="{{@$safety_data->q10}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q10_upload !='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q10_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">7. No of AISSC Meeting <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q11" value="{{@$safety_data->q11}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q11_upload !='')
           <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q11_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">8. No of Mock Drill <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q12" value="{{@$safety_data->q12}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q12_upload !='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q12_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">9. No of Job Cycle Check <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q13" value="{{@$safety_data->q13}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q13_upload !='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q13_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">10. No of Safety Kaizen <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q14" value="{{@$safety_data->q14}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q14_upload !='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q14_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
     <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">11. No of Sr.Leader Line Walk <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q15" value="{{@$safety_data->q15}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q15_upload !='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q15_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">12. No of Safety Campaign <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q16" value="{{@$safety_data->q16}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q16_upload !='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q16_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">13. No of MOC <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q17" value="{{@$safety_data->q17}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q17_upload !='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q17_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">14. No of SOP revised <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q18" value="{{@$safety_data->q18}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q18_upload !='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q18_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div>
  <!--  <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">18. No of Nearmiss <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q18" value="{{@$safety_data->q18}}" readonly>
        </div>
        <div class="col-sm-4">
			@if($safety_data->q18_upload !='')
            <a href="https://wps.jamipol.com/public/documents/clm_pics/{{$safety_data->q18_upload}}" target="_blank">
              <button class="btn"><i class="fa fa-download"></i> View File</button> </a>
			@else
			<button class="btn">No File</button>
			@endif
        </div>
    </div> -->
</fieldset>
<br>
<fieldset class="border p-2">
<legend class="w-auto">Lagging Indicator</legend>


<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">1. No of Fatilities <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T1" value="{{@$safety_data->T1}}" readonly>
        </div>
        
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">2. No of Major Fires <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T2" value="{{@$safety_data->T2}}" readonly>
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">3. No of Lost Time Injury <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T3" value="{{@$safety_data->T3}}" readonly>
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">4. No of Restricted Work Case <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T4" value="{{@$safety_data->T4}}" readonly>
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">5.No of Medical Treatment Case<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T5" value="{{@$safety_data->T5}}" readonly>
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">6. No of First Aid Case <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T6" value="{{@$safety_data->T6}}" readonly>
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">7. Total No of Incidents occurred during the Month <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T7" value="{{@$safety_data->T7}}" readonly>
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">8. No of Road Related Incident (inside + outside premises) <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T8" value="{{@$safety_data->T8}}" readonly>
        </div>
        
    </div>
</fieldset>
<br>
<fieldset class="border p-2">
<legend class="w-auto">Other Information</legend>
<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">1. Average number of employees present during the month<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T9" value="{{@$safety_data->T9}}" readonly>
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">2. Average number of contractor employees present during the
month<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T10" value="{{@$safety_data->T10}}" readonly>
        </div>
        
    </div>
</fieldset>
    <br>
   <fieldset class="border p-2">
<legend class="w-auto">Qualitative Information</legend> 
  <input type="text" class="form-control rec" name="remarks" value="{{@$safety_data->remarks}}" readonly>
</fieldset>

<br>

                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
@endsection