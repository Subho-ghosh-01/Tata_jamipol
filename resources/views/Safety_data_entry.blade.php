<?php 
use App\Division;
use App\UserLogin;

@$division = Division::where('id',Session::get('user_DivID_Session'))->first();


?>

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="">Safety Data Entry</a></li>
@endsection
@section('content')
@extends('admin.app')


                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success text-center">
                            {{ session('message')}}
                        </div>
                    @endif
					
					@if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
                    <!--<form method="POST" action="{{route('RequestVGatepassPost')}}">-->
                    <form action="{{route('admin.safety_data_entry.store')}}" method="POST"  autocomplete="off" enctype="multipart/form-data" >
                        @csrf
                       

<div class="form-group row" style="display: none;">
        <label for="form-control-label" class="col-sm-2 col-form-label">Financial Year<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select name="" class="form-control rec">
               
            </select>
        </div>

    </div>

<div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Financial Year And Month<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
           <input type="date" name="month" class="form-control rec" placeholder="Select Month" min="2023-01"
    max="2025-12" id="txtDate" required> 
        </div>

    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!--<script>
    

    $(function(){

var date = new Date(), y = date.getFullYear(), m = date.getMonth();
var firstDay = new Date(y, m, 1);
var dayy =firstDay.getDate() + 6;

var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var month1 = firstDay.getMonth() + 2;
   
    var day = dtToday.getDate();

    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
   if(month1 < 10)
      month1 = '0' + month1.toString();
  if(dayy < 10)
     dayy = '0' + dayy.toString();
    var minDate= year + '-' + month + '-' + day;
    var maxDate= year + '-' + month1 + '-' + dayy;
   
    $('#txtDate').attr('min', minDate);
    $('#txtDate').attr('max', maxDate);
    

});
</script>-->
<script>
$(function() {
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var firstDay = new Date(y, m, 1); // First day of the current month

    // Get the last day of the current month
    var lastDay = new Date(y, m + 1, 0).getDate(); // Last day of the current month

    var dtToday = new Date();
    var currentDay = dtToday.getDate();

    // Initialize min and max date
    var minDate, maxDate;

    // If the current date is between the 1st and 7th, show days from the previous month
    if (currentDay <= 7) {
        // Set min date to last week of the previous month
        var previousMonth = new Date(y, m, 0); // Last day of the previous month
        var lastDayPrevMonth = previousMonth.getDate(); // Get the last day of the previous month
        var startPrevMonth = new Date(y, m - 1, lastDayPrevMonth - (6 - currentDay)); // 7 days before
        minDate = formatDate(startPrevMonth);
    } else {
        // After the 7th, show today's date as min
        minDate = formatDate(firstDay); // First day of the current month
    }

    // Set max date to the last day of the current month
    maxDate = formatDate(new Date(y, m, lastDay));

    $('#txtDate').attr('min', minDate);
    $('#txtDate').attr('max', maxDate);

    // Helper function to format dates as YYYY-MM-DD
    function formatDate(date) {
        var d = date.getDate();
        var m = date.getMonth() + 1; // Months are zero-indexed
        var y = date.getFullYear();

        if (m < 10) m = '0' + m;
        if (d < 10) d = '0' + d;

        return y + '-' + m + '-' + d;
    }
});



</script>
  <div class="form-group row" style="display:none;">
        <label for="form-control-label" class="col-sm-2 col-form-label">Month<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
           <select name="" class="form-control rec">
     <option value="">--Select Month--</option>
    <option  value="1">Janaury</option>
    <option value="2">February</option>
    <option value="3">March</option>
    <option value="4">April</option>
    <option value="5">May</option>
    <option value="6">June</option>
    <option value="7">July</option>
    <option value="8">August</option>
    <option value="9">September</option>
    <option value="10">October</option>
    <option value="11">November</option>
    <option value="12">December</option>
            </select>
        </div>
    </div>


     <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Branch<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
         
           
                         <select class="form-control" name="division">
                <option value="">--Select--</option>
                @if($divisions->count() > 0)
                    @foreach($divisions as $division)
                        <option value="{{$division->id}}">{{$division->name}}</option>
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
             <input type="text" class="form-control " id="q1" name="q1"  >
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q1" name="q1_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>
<script type="text/javascript">

function calculateAmount(th) {
    var q1 = $("#q1").val();
    var fileInput = document.querySelector('.q1'); // Get the file input element
    const maxSizeInBytes = 5 * 1024 * 1024; // 5 MB in bytes

    // Check if q1 has a positive value, then set .q1 as required accordingly
    if (q1 > 0) {
        $('.q1').prop('required', true); // Make file input required

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

    // Hide the modal loader
    $('#modal-loader').modal('hide');
}



</script>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">2. No of Employees  Attended  Safety Training</label>
        <div class="col-sm-4">
             <input type="text" class="form-control " name="q2" id="q2" >
        </div>
        <div class="col-sm-4">
            <input type="file" class="form-control " hidden name="q2_upload">
			
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq2(th) {
   
       var q2=$("#q2").val();
       // alert(q2);

 if(q2 > 0){
     $('.q2').prop('required', true);
 }else{
    $('.q2').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>


<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">3. No of Safety Training for Contractor Employees <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q3" id="q3" >
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q3" name="q3_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq3(th) {
   
       var q3=$("#q3").val();
      //  alert(q2);

 if(q3 > 0){
     $('.q3').prop('required', true);
 }else{
    $('.q3').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>

<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">4. No of Contractor Employees  attended the training</label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q4" name="q4" >
        </div>
        <div class="col-sm-4">
             <input type="file" hidden class="form-control" name="q4_upload">
			 
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq4(th) {
   
       var q4=$("#q4").val();
      //  alert(q2);

 if(q4 > 0){
     $('.q4').prop('required', true);
 }else{
     $('.q4').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>

   <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">5. No of Health Awareness Session <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q5" id="q5">
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q5" name="q5_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq5(th) {
   
       var q5=$("#q5").val();
      //  alert(q2);

 if(q5 > 0){
     $('.q5').prop('required', true);
 }else{
    $('.q5').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>

   <!-- <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">6. No of Closed Observation <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control q6" id="q6" name="q6" onkeyup="calculateAmountq6(this)" >
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control " name="q6_upload">
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq6(th) {
   
       var q6=$("#q6").val();
      //  alert(q2);

 if(q6 > 0){
     $('.q6').prop('required', true);
 }else{
     $('.q6').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">7. No of Opened Observation <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control q7" id="q7" name="q7"  onkeyup="calculateAmountq7(this)" >
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control " name="q7_upload">
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq7(th) {
   
       var q7=$("#q7").val();
      //  alert(q2);

 if(q7 > 0){
     $('.q7').prop('required', true);
 }else{
    $('.q7').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>





    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">8. No of Total Safety Observation <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q8" name="q8"  onkeyup="calculateAmountq8(this)" >
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control " name="q8_upload">
        </div>
    </div>


    <script type="text/javascript">

function calculateAmountq8(th) {
   
       var q8=$("#q8").val();
      //  alert(q2);

 if(q8 > 0){
     $('.q8').prop('required', true);
 }else{
    $('.q8').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>
     <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">9. No of Unsafe Situtions rectified <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q9" name="q9" onkeyup="calculateAmountq9(this)" >
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control " name="q9_upload">
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq9(th) {
   
       var q9=$("#q9").val();
      //  alert(q2);

 if(q9 > 0){
     $('.q9').prop('required', true);
 }else{
    $('.q9').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>-->

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">6. No of Mass Meeting <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" name="q10" id="q10">
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q10" name="q10_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq10(th) {
   
       var q10=$("#q10").val();
      //  alert(q2);

 if(q10 > 0){
     $('.q10').prop('required', true);
 }else{
    $('.q10').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>


    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">7. No of AISSC Meeting <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q11" name="q11" >
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q11" name="q11_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>



<script type="text/javascript">

function calculateAmountq11(th) {
   
       var q11=$("#q11").val();
      //  alert(q2);

 if(q11 > 0){
     $('.q11').prop('required', true);
 }else{
    $('.q11').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">8. No of Mock Drill <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q12" name="q12">
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q12" name="q12_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>


<script type="text/javascript">

function calculateAmountq12(th) {
   
       var q12=$("#q12").val();
      //  alert(q2);

 if(q12 > 0){
     $('.q12').prop('required', true);
 }else{
    $('.q12').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">9. No of Job Cycle Check <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q13" name="q13">
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q13" name="q13_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>


<script type="text/javascript">

function calculateAmountq13(th) {
   
       var q13=$("#q13").val();
      //  alert(q2);

 if(q13 > 0){
     $('.q13').prop('required', true);
 }else{
    $('.q13').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">10. No of Safety Kaizen <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q14" name="q14">
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q14" name="q14_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq14(th) {
   
       var q14=$("#q14").val();
      //  alert(q2);

 if(q14 > 0){
     $('.q14').prop('required', true);
 }else{
    $('.q14').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>


     <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">11. No of Sr.Leader Line Walk <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q15" name="q15" >
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q15" name="q15_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq15(th) {
   
       var q15=$("#q15").val();
      //  alert(q2);

 if(q15 > 0){
     $('.q15').prop('required', true);
 }else{
     $('.q15').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">12. No of Safety Campaign <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q16" name="q16" >
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q16" name="q16_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq16(th) {
   
       var q16=$("#q16").val();
      //  alert(q2);

 if(q16 > 0){
     $('.q16').prop('required', true);
 }else{
     $('.q16').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">13. No of MOC <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q17" name="q17"  >
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q17" name="q17_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>
<script type="text/javascript">

function calculateAmountq17(th) {
   
       var q17=$("#q17").val();
      //  alert(q2);

 if(q17 > 0){
     $('.q17').prop('required', true);
 }else{
    $('.q17').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>
<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">14. No of SOP revised <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-4">
             <input type="text" class="form-control rec" id="q18" name="q18" >
        </div>
        <div class="col-sm-4">
             <input type="file" class="form-control q18" name="q18_upload">
			 <span style="color:blue;font-size: 15px;">Max Size : 5mb</span>
        </div>
    </div>

<script type="text/javascript">

function calculateAmountq18(th) {
   
       var q18=$("#q18").val();
      //  alert(q2);

 if(q18 > 0){
     $('.q18').prop('required', true);
 }else{
     $('.q18').prop('required', false);
 }
 $('#modal-loader').modal('hide');
    }
</script>



</fieldset>
<br>
<fieldset class="border p-2">
<legend class="w-auto">Lagging Indicator</legend>


<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">1. No of Fatilities <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T1" id="T1">
        </div>
        
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">2. No of Major Fires <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T2" id="T2">
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">3. No of Lost Time Injury <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T3" id="T3">
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">4. No of Restricted Work Case<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T4" id="T4">
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">5. No of Medical Treatment Case <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T5" id="T5">
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">6. No of First Aid Case <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T6" id="T6">
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">7.Total No of Incidents occurred during the Month<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T7" id="T7">
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">8. No of Road Related Incident (inside + outside premises) <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T8" id="T8">
        </div>
        
    </div>
</fieldset>
<br>
<fieldset class="border p-2">
<legend class="w-auto">Other Information</legend>
<div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">1. Average number of employees present during the month<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T9" id="T9">
        </div>
        
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-4 col-form-label">2. Average number of contractor employees present during the
month<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-8">
             <input type="text" class="form-control rec" name="T10" id="T10">
        </div>
        
    </div>
</fieldset>
    <br>
   <fieldset class="border p-2">
<legend class="w-auto">Qualitative Information</legend> 
 <textarea rows="2" cols="50" class="form-control " name="remarks" > </textarea>
</fieldset>

<br>
<div class="form-group row mb-0">
                            <div class="col-md-12">
                                <center>
                                <button type="submit" name="submit"  value="full_submit" class="btn btn-primary" onclick="total_sub(this)">
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

 var q3=$("#q3").val();
      //  alert(q2);

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

$(document).ready(function() {
 var current_year = new Date().getFullYear()
 var amount_of_years = 10

  for (var i = 0; i < amount_of_years+1; i++) {
    var year = (current_year+i).toString();
    var element = '<option value="' + year + '">' + year + '</option>';
    $('select[name="financial_year"]').append(element)
  }
})
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@endsection