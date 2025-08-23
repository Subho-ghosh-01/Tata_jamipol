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
@if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
<form action="{{route('admin.gatepass_safety.update1')}}" method="POST"  autocomplete="off" enctype="multipart/form-data" >
                        @csrf
                        <input type="hidden" name="id" class="form-control rec"   required value="{{@$safety_data->id}}">                    
<div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Financial Year And Month<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
           <input type="date" name="month" class="form-control rec"   required value="{{@$safety_data->financial_year}}"> 
        </div>

    </div>
  
 
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Branch<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
         
           
                <select class="form-control" name="division">
                <option value="">--Select--</option>
                @if($divisions->count() > 0)
                    @foreach($divisions as $division)
                        <option value="{{$division->id}}" <?php if($division->id == $safety_data->division_id) echo 'selected'; ?>>{{$division->name}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
<center><h4 ><b>Key Performance Indicator</b></h4></center>
   <fieldset class="border p-2">
<legend class="w-auto">Leading Indicator</legend>
     <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">1. No of Safety Training Session For Employee<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q1" name="q1" value="{{@$safety_data->q1}}" >
        </div>
		 
        <div class="col-sm-1.2">
             
@if($safety_data->q1_upload !='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q1_upload}}" target="_blank" class="btn"><i class="fa fa-download"></i> View File
              </a>
@else
			
              No File
@endif

        </div>
        <div class="col-sm-2">
        <input type="file" class="form-control <?php if($safety_data->q1_upload==''){ echo 'q1'; }else{ echo '';} ?>" name="q1_upload" >
        <input type="hidden" class="form-control" name="q1_upload_q1" value="{{$safety_data->q1_upload}}">
		<span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
        </div>
    </div>


    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">2. No of Employees  Attended  Safety Training</label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec"  name="q2" value="{{@$safety_data->q2}}" >
        </div>
        <div class="col-sm-2">
			@if($safety_data->q2_upload!='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q2_upload}}" target="_blank" class="btn" ><i class="fa fa-download"></i>View File
              </a>
			@else
			No File
			@endif
        </div>
    </div>

<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">3. No of Safety Training for Contractor Employees  <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q3" name="q3" value="{{@$safety_data->q3}}" >
        </div>
	
        <div class="col-sm-1.2">
			@if($safety_data->q3_upload!='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q3_upload}}" target="_blank" class="btn"><i class="fa fa-download"></i> View File
               </a>
			@else
			No File
			@endif
        </div>
        <div class="col-sm-2">
        <input type="file" class="form-control <?php if($safety_data->q3_upload==''){ echo 'q3'; }else{ echo '';} ?>" name="q3_upload">
        <input type="hidden" class="form-control" name="q3_upload_q3" value="{{$safety_data->q3_upload}}">
		<span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
		
        </div>
    </div>
<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">4. No of Contractor Employees  attended the training</label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q4" name="q4" value="{{@$safety_data->q4}}" >
        </div>
        <div class="col-sm-1.2">
			@if($safety_data->q4_upload !='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q4_upload}}" target="_blank" class="btn"><i class="fa fa-download"></i> View File
             </a>
			@else
			No File
			@endif
        </div>
    </div>
   <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">5. No of Health Awareness Session <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q5" name="q5" value="{{@$safety_data->q5}}" >
			 
        </div>
        <div class="col-sm-1.2">
			@if($safety_data->q5_upload !='')
           <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q5_upload}}" target="_blank" class="btn" ><i class="fa fa-download"></i> View File
             </a>
			@else
			No File
			@endif
        </div>
        <div class="col-sm-2">
        <input type="file" class="form-control <?php if($safety_data->q5_upload==''){ echo 'q5'; }else{ echo '';} ?>" name="q5_upload" >
        <input type="hidden" class="form-control" name="q5_upload_q5" value="{{$safety_data->q5_upload}}">
		<span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
        </div>
    </div>
    
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">6. No of Mass Meeting <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q10" name="q10" value="{{@$safety_data->q10}}" >
        </div>
        <div class="col-sm-1.2">
			@if($safety_data->q10_upload !='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q10_upload}}" target="_blank" class="btn"><i class="fa fa-download"></i> View File
             </a>
			@else
			No File
			@endif
            </div>
            <div class="col-sm-2">
            <input type="file" class="form-control <?php if($safety_data->q10_upload==''){ echo 'q10'; }else{ echo '';} ?>" name="q10_upload" >
            <input type="hidden" class="form-control" name="q10_upload_q10" value="{{$safety_data->q10_upload}}">
            <span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">7. No of AISSC Meeting <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q11" name="q11" value="{{@$safety_data->q11}}" >
        </div>
        <div class="col-sm-1.2">
			@if($safety_data->q11_upload !='')
           <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q11_upload}}" target="_blank" class="btn"><i class="fa fa-download"></i> View File
              </a>
			@else
			No File
			@endif
            </div>
            <div class="col-sm-2">
            <input type="file" class="form-control <?php if($safety_data->q11_upload==''){ echo 'q11'; }else{ echo '';} ?>" name="q11_upload" >
            <input type="hidden" class="form-control" name="q11_upload_q11" value="{{$safety_data->q11_upload}}">
			<span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">8. No of Mock Drill <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q12" name="q12" value="{{@$safety_data->q12}}" >
        </div>
        <div class="col-sm-1.2">
			@if($safety_data->q12_upload !='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q12_upload}}" target="_blank" class="btn"><i class="fa fa-download"></i> View File
               </a>
			@else
			No File
			@endif
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control <?php if($safety_data->q12_upload==''){ echo 'q12'; }else{ echo '';} ?>" name="q12_upload" >
            <input type="hidden" class="form-control" name="q12_upload_q12" value="{{$safety_data->q12_upload}}">
			<span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">9. No of Job Cycle Check <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q13" name="q13" value="{{@$safety_data->q13}}" >
        </div>
        <div class="col-sm-1.2">
			@if($safety_data->q13_upload !='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q13_upload}}" target="_blank" class="btn"><i class="fa fa-download"></i> View File
             </a>
			@else
			No File
			@endif
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control <?php if($safety_data->q13_upload==''){ echo 'q13'; }else{ echo '';} ?>" name="q13_upload">
            <input type="hidden" class="form-control" name="q13_upload_q13" value="{{$safety_data->q13_upload}}">
			<span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">10. No of Safety Kaizen <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q14" name="q14" value="{{@$safety_data->q14}}" >
        </div>
        <div class="col-sm-1.2">
			@if($safety_data->q14_upload !='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q14_upload}}" target="_blank" class="btn"><i class="fa fa-download"></i> View File
              </a>
			@else
			No File
			@endif
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control <?php if($safety_data->q14_upload==''){ echo 'q14'; }else{ echo '';} ?>" name="q14_upload" >
            <input type="hidden" class="form-control " name="q14_upload_q14" value="{{$safety_data->q14_upload}}">
			<span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
        </div>
    </div>
     <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">11. No of Sr.Leader Line Walk <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q15" name="q15" value="{{@$safety_data->q15}}" >
        </div>
        <div class="col-sm-1.2">
			@if($safety_data->q15_upload !='')
             <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q15_upload}}" target="_blank" class="btn"><i class="fa fa-download"></i> View File
              </button> </a>
			@else
			No File
			@endif
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control " name="q15_upload" <?php if($safety_data->q15_upload==''){ echo 'q15'; }else{ echo '';} ?>>
            <input type="hidden" class="form-control" name="q15_upload_q15" value="{{$safety_data->q15_upload}}">
			<span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">12. No of Safety Campaign <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q16" name="q16" value="{{@$safety_data->q16}}" >
        </div>
        <div class="col-sm-1.2">
			@if($safety_data->q16_upload !='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q16_upload}}" target="_blank"  class="btn"><i class="fa fa-download"></i> View File
              </a>
			@else
			No File
			@endif
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control <?php if($safety_data->q16_upload==''){ echo 'q16'; }else{ echo '';} ?>" name="q16_upload" >
            <input type="hidden" class="form-control" name="q16_upload_q16" value="{{$safety_data->q16_upload}}">
			<span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">13. No of MOC <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q17" name="q17" value="{{@$safety_data->q17}}" >
        </div>
        <div class="col-sm-1.2">
			@if($safety_data->q17_upload !='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q17_upload}}" target="_blank" class="btn"><i class="fa fa-download"></i> View File
               </a>
			@else
			No File
			@endif
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control <?php if($safety_data->q17_upload==''){ echo 'q17'; }else{ echo '';} ?>" name="q17_upload" >
            <input type="hidden" class="form-control" name="q17_upload_q17" value="{{$safety_data->q17_upload}}">
			<span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">14. No of SOP revised <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q18" name="q18" value="{{@$safety_data->q18}}" >
        </div>
        <div class="col-sm-1.2">
			@if($safety_data->q18_upload !='')
            <a href="https://wps.jamipol.com/documents/clm_pics/{{$safety_data->q18_upload}}" target="_blank" class="btn"><i class="fa fa-download"></i> View File
             </a>
			@else
			No File
			@endif
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control <?php if($safety_data->q18_upload==''){ echo 'q18'; }else{ echo '';} ?>" name="q18_upload">
            <input type="hidden" class="form-control" name="q18_upload_q18" value="{{$safety_data->q18_upload}}">
			<span style="color:blue;font-size: 15px;">(Max Size : 5mb)</span>
        </div>
    </div>



   
</fieldset>
<br>
<fieldset class="border p-2">
<legend class="w-auto">Lagging Indicator</legend>


<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">1. No of Fatilities <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T1" id="T1" value="{{@$safety_data->T1}}" >
        </div>
        
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">2. No of Major Fires <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T2" id="T2" value="{{@$safety_data->T2}}" >
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">3. No of Lost Time Injury <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T3" id="T3" value="{{@$safety_data->T3}}" >
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">4. No of Restricted Work Case <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T4" id="T4" value="{{@$safety_data->T4}}" >
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">5.No of Medical Treatment Case<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T5" id="T5" value="{{@$safety_data->T5}}" >
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">6. No of First Aid Case <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T6" id="T6" value="{{@$safety_data->T6}}" >
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">7. Total No of Incidents occurred during the Month <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T7" id="T7" value="{{@$safety_data->T7}}" >
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">8. No of Road Related Incident (inside + outside premises) <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T8" id="T8" value="{{@$safety_data->T8}}" >
        </div>
        
    </div>
</fieldset>
<br>
<fieldset class="border p-2">
<legend class="w-auto">Other Information</legend>
<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">1. Average number of employees present during the month<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T9" id="T9" value="{{@$safety_data->T9}}" >
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">2. Average number of contractor employees present during the
month<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T10" id="T10" value="{{@$safety_data->T10}}" >
        </div>
        
    </div>
</fieldset>
    <br>
   <fieldset class="border p-2">
<legend class="w-auto">Qualitative Information</legend> 
  <input type="text" class="form-control rec" name="remarks" value="{{@$safety_data->remarks}}" >
</fieldset>

<br>

<div class="form-group row mb-0">
                            <div class="col-md-12">
                                <center>
                                <button type="submit" name="submit" value="full_submit"  class="btn btn-primary" onclick="total_sub(this)">
                                  Submit
                                </button>
                                <button type="submit" name="submit" value="draft" class="btn btn-success" onclick="save_draft(this)">
                                  Save as draft
                                </button>
                            </center>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
@endsection
@section('scripts')
<script>
function total_sub(){
    
    $('#q1').prop('required', true);
    $('#q3').prop('required', true);
    $('#q5').prop('required', true);
    $('#q10').prop('required', true);
    $('#q11').prop('required', true);
    $('#q12').prop('required', true);
    $('#q13').prop('required', true);
    $('#q14').prop('required', true);
    $('#q15').prop('required', true);
    $('#q16').prop('required', true);
    $('#q17').prop('required', true);
    $('#q18').prop('required', true);
    $('#T1').prop('required', true);
    $('#T2').prop('required', true);
    $('#T3').prop('required', true);
    $('#T4').prop('required', true);
    $('#T5').prop('required', true);
    $('#T6').prop('required', true);
    $('#T7').prop('required', true);
    $('#T8').prop('required', true);
    $('#T9').prop('required', true);
    $('#T10').prop('required', true);
   var q1=$("#q1").val();
       

 if (q1 > 0) {
        $('.q1').prop('required', true); // Make file input required
       var fileInput = document.querySelector('.q1'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
    } else {
        $('.q1').prop('required', false); // Remove required attribute if q1 is not positive
    }
if(q3 > 0){
     $('.q3').prop('required', true);
	 
	 var fileInput = document.querySelector('.q3'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
 }else{
    $('.q3').prop('required', false);
 }
 
 var q4=$("#q4").val();
      //  alert(q2);

 if(q4 > 0){
     $('.q4').prop('required', true);
	
 }else{
     $('.q4').prop('required', false);
 }

 var q5=$("#q5").val();
        

 if(q5 > 0){
     $('.q5').prop('required', true); 
	 var fileInput = document.querySelector('.q5'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
 }else{
    $('.q5').prop('required', false);
 }

 var q10=$("#q10").val();
      //  alert(q2);

 if(q10 > 0){
     $('.q10').prop('required', true);
	 
	var fileInput = document.querySelector('.q10'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
 }else{
    $('.q10').prop('required', false);
 }
 var q11=$("#q11").val();
      //  alert(q2);

 if(q11 > 0){
     $('.q11').prop('required', true);
	 var fileInput = document.querySelector('.q11'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
 }else{
    $('.q11').prop('required', false);
 }

 var q12=$("#q12").val();
      //  alert(q2);

 if(q12 > 0){
     $('.q12').prop('required', true);
	 var fileInput = document.querySelector('.q12'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
 }else{
    $('.q12').prop('required', false);
 }

 var q13=$("#q13").val();
      //  alert(q2);

 if(q13 > 0){
     $('.q13').prop('required', true);
	 var fileInput = document.querySelector('.q13'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
 }else{
    $('.q13').prop('required', false);
 }

 var q14=$("#q14").val();
      //  alert(q2);

 if(q14 > 0){
     $('.q14').prop('required', true);
	 var fileInput = document.querySelector('.q14'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
 }else{
    $('.q14').prop('required', false);
 }

 var q15=$("#q15").val();
      //  alert(q2);

 if(q15 > 0){
     $('.q15').prop('required', true);
	 var fileInput = document.querySelector('.q15'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
 }else{
     $('.q15').prop('required', false);
 }
 var q16=$("#q16").val();
      //  alert(q2);

 if(q16 > 0){
     $('.q16').prop('required', true);
	 var fileInput = document.querySelector('.q16'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
 }else{
     $('.q16').prop('required', false);
 }

 var q17=$("#q17").val();
      //  alert(q2);

 if(q17 > 0){
     $('.q17').prop('required', true);
	 var fileInput = document.querySelector('.q17'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
 }else{
    $('.q17').prop('required', false);
 }
 var q18=$("#q18").val();
      //  alert(q2);

 if(q18 > 0){
     $('.q18').prop('required', true);
	 var fileInput = document.querySelector('.q18'); // Get the file input element
      const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes
        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Get the selected file

            // Validate file size
            if (file.size > maxSizeInBytes) {
                alert("File size should not exceed 5 MB."); // Alert user
                fileInput.value = ""; // Clear the file input if file size exceeds the limit
            }
        }
 }else{
     $('.q18').prop('required', false);
 }

}
function save_draft(){
    
    $('#q1').prop('required', false);
    $('.q1').prop('required', false);
    $('#q3').prop('required', false);
    $('.q3').prop('required', false);
    $('#q5').prop('required', false);
    $('.q5').prop('required', false);
    $('#q10').prop('required', false);
    $('.q10').prop('required', false);
    $('#q11').prop('required', false);
    $('.q11').prop('required', false);
    $('#q12').prop('required', false);
    $('.q12').prop('required', false);
    $('#q13').prop('required', false);
    $('.q13').prop('required', false);
    $('#q14').prop('required', false);
    $('.q14').prop('required', false);
    $('#q15').prop('required', false);
    $('.q15').prop('required', false);
    $('#q16').prop('required', false);
    $('.q16').prop('required', false);
    $('#q17').prop('required', false);
    $('.q17').prop('required', false);
    $('#q18').prop('required', false);
    $('.q18').prop('required', false);
    $('#T1').prop('required', false);
    $('#T2').prop('required', false);
    $('#T3').prop('required', false);
    $('#T4').prop('required', false);
    $('#T5').prop('required', false);
    $('#T6').prop('required', false);
    $('#T7').prop('required', false);
    $('#T8').prop('required', false);
    $('#T9').prop('required', false);
    $('#T10').prop('required', false);
}

</script>
@endsection