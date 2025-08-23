<?php
use App\Division;
use App\Department;
use App\UserLogin;

?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
   
    <li class="breadcrumb-item active" aria-current="page">Approve Vendor</li>
@endsection

<!-- start content Section-->
@section('content')

    @csrf
    @method('PUT')
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
    <fieldset class="border p-4">
    <legend class="w-auto">Vendor Information</legend>

   <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" value="{{$user->name}}" readonly>
        </div>
    </div>
   <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Code</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" value="{{$user->vendor_code}}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Company Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" value="{{$user->company_name}}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Mobile Number </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="vendor_code"  value="{{$user->mobile_no}}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Emergency Mobile Number </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="vendor_code"  value="{{$user->emergency_contact_no ?? 'NA'}}" readonly>
        </div>
    </div>
  
     @if($user->landing_no !='')
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Landing No</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" value="{{$user->landing_no}}" readonly>
        </div>
    </div>
    @endif
  
 <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Proprietor/MD name</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" value="{{$user->md_name}}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">GSTN</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" value="{{$user->GSTN}}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">PAN of the Organization</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" value="{{$user->pan_of_the_orgination}}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">ESIC Code</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="email" value="{{$user->esci_code}}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">ESIC Document</label>
        <div class="col-sm-10">
            <a href="https://wps.jamipol.com/public/documents/clm_pics/{{$user->esci_document}}" target="_blank">
                                    <button class="btn"><i class="fa fa-eye"></i> View Document</button> </a>

        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">EPF Code</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" value="{{$user->epf_code}}" readonly>
        </div>
    </div>
     
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Location</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" value="{{$user->location}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Contract Type </label>
        <div class="col-sm-10">
              <input type="text" class="form-control" name="email" value="{{$user->contract_type}}" readonly>
        </div>  
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Nature of Work</label>
        <div class="col-sm-10">
             <input type="text" class="form-control" name="email" value="{{$user->nature_of_work}}" readonly>
        </div>  
    </div>
     <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Lobour Capacity</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="email" value="{{$user->lobour_capacity}}" readonly>
        </div>
    </div>
    @if($user->lobour_license_no !='')
 <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Labour License No</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="email" value="{{$user->lobour_license_no}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Labour License Validity</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" name="email" value="{{$user->labour_license_validity}}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Labour License Document</label>
        <div class="col-sm-10">
            <a href="https://wps.jamipol.com//public/documents/clm_pics/{{$user->lobour_license_document}}" target="_blank">
                                    <button class="btn"><i class="fa fa-eye"></i> View Document</button> </a>

        </div>
    </div>
    @endif
     
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">EC Policy</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="email" value="{{$user->ec_policy}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">EC Policy Document</label>
        <div class="col-sm-10">
            <a href="https://wps.jamipol.com/public/documents/clm_pics/{{$user->ec_document}}" target="_blank">
                                    <button class="btn"><i class="fa fa-eye"></i> View Document</button> </a>

        </div>
    </div>
     <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">PO Number</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="email" value="{{$user->po_number}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">PO Doucument</label>
        <div class="col-sm-10">
            <a href="https://wps.jamipol.com/public/documents/clm_pics/{{$user->po_document}}" target="_blank">
                                    <button class="btn"><i class="fa fa-eye"></i> View Document</button> </a>

        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Workman Compensation No</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="email" value="{{$user->wcp_no}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Workman Compensation Validity</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" name="email" value="{{$user->wcp_validity}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Workman Compensation Doucument</label>
        <div class="col-sm-10">
            <a href="https://wps.jamipol.com/public/documents/clm_pics/{{$user->wcp_doc}}" target="_blank">
                <button class="btn"><i class="fa fa-eye"></i> View Document</button> </a>

        </div>
    </div>
    </fieldset>
    <br>
    
    @if($user->status=='Pending_for_hr' && Session::get('clm_role') =='hr_dept')
    <div class="card card-primary">
                        <div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              
                           <h5 style="text-white">HR DEPT   </h5>
                            
                             </div>
                          </div>
     <div class="card-body">
<form action="{{route('admin.gatepassven.update')}}" method="post"  autocomplete="off">
    @csrf
                             <table class="table table-bordered table-hover table-condensed">
                                
                                          <tr>
                                            <th>
                                            Decision
                                            </th>
                                         <td><input type="radio" class="btn-check button" name="approver_decision" id="" autocomplete="off"  value="approve">
                           <label>Approve</label>
                           <input type="radio" class="btn-check button" name="approver_decision" id="" autocomplete="off" value="reject">
                           <label >Reject</label></td>
                                    </tr>
                                  <tr>
                                    <th class="col-4">
                                               Remarks
                                                </th>
                                                <td>
                                        <input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$user->id}}">
                                        <input type="hidden" class="form-control rec" name="status" autocomplete="off"  value="{{$user->status}}">
                                    <!--    <input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
                                         <textarea name="approver_remarks" rows="4" cols="120" class="form-control rec cb" placeholder="HR Dept Remarks"  value=""  ></textarea>   
                                        </td>
                                         </tr>
                            </table>

    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary" value="Approve" >
        </div>
    </div>
</form>
</div>
</div>
@endif
@if($user->hr_by!='')
<div class="card card-primary">
                        <div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              @php
@$shift = UserLogin::where('id',@$user->hr_by)->first();
 @endphp
                           <h5 style="text-white"> HR Dept ({{ucfirst(@$shift->name)}})  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="">
                                 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                                
                                          <tr>
                                            <th>
                                            Decision
                                            </th>
                                         <td><input type="text" class="form-control" readonly name="" id="" autocomplete="off"  value="{{ucfirst(@$user->hr_decision)}}">
                           </td>
                        
                                    </tr>
                                    <tr>
                                    <th class="col-4">
                                        HR Remarks
                                                </th>
                                                <td>
                                        <input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$user->id}}">
                                    <!--    <input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
                                   
                                            <input type="text" class="form-control rec" value="{{@$user->hr_remarks}}" autocomplete="off"  readonly>

                                        </td>
                                         </tr>

                                         <tr>
                                    <th class="col-4">
                                          HR Remarks Datetime
                                                </th>
                                                <td>
                                        <input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$user->id}}">
                                    <!--    <input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
                                            <input type="text" class="form-control rec" value="{{date('d-F-Y H:i:s A', strtotime(@$user->hr_datetime))}}" autocomplete="off"  readonly>
                                        </td>
                                         </tr>
                            </table>
                           
                
                        </form>
                        </div>
    </div>
@endif
@if($user->status=='pending_for_safety' && Session::get('clm_role') =='Safety_dept')
<div class="card card-primary">
                        <div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              
                           <h5 style="text-white">Safety Dept   </h5>
                            
                             </div>
                          </div>
     <div class="card-body">
<form action="{{route('admin.gatepassven.update')}}" method="post"  autocomplete="off">
    @csrf
                             <table class="table table-bordered table-hover table-condensed">
                                
                                          <tr>
                                            <th>
                                            Decision
                                            </th>
                                         <td><input type="radio" class="btn-check button" name="approver_decision" id="" autocomplete="off"  value="approve">
                           <label>Approve</label>
                           <input type="radio" class="btn-check button" name="approver_decision" id="" autocomplete="off" value="reject">
                           <label >Reject</label></td>
                        
                                    </tr>

                                    

                                    <tr>
                                    <th class="col-4">
                                               Remarks
                                                </th>
                                                <td>
                                        <input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$user->id}}">
                                        <input type="hidden" class="form-control rec" name="status" id="" autocomplete="off"  value="{{$user->status}}">
                                    <!--    <input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
                                         <textarea name="approver_remarks" rows="4" cols="120" class="form-control rec cb" placeholder="Safety Dept Remarks"  value=""  ></textarea>   
                                        </td>
                                         </tr>
                            </table>

    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary" value="Approve">
        </div>
    </div>
</form>
</div>
</div>
@endif
@if($user->safety_by!='')
<div class="card card-primary">
                        <div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              @php
@$shift = UserLogin::where('id',@$user->safety_by)->first();
 @endphp
                           <h5 style="text-white"> Safety Dept({{ucfirst(@$shift->name)}})  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="">
                                 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                                
                                          <tr>
                                            <th>
                                            Decision
                                            </th>
                                         <td><input type="text" class="form-control" readonly name="" id="" autocomplete="off"  value="{{ucfirst(@$user->safety_decision)}}">
                           </td>
                        
                                    </tr>
                                    <tr>
                                    <th class="col-4">
                                       Safety Remarks
                                                </th>
                                                <td>
                                        
                                            <input type="text" class="form-control rec" value="{{@$user->safety_remarks}}" autocomplete="off"  readonly>
                                        </td>
                                         </tr>

                                         <tr>
                                    <th class="col-4">
                                          Safety Remarks Datetime
                                                </th>
                                                <td>
                                            <input type="text" class="form-control rec" value="{{date('d-F-Y H:i:s A', strtotime(@$user->safety_datetime))}}" autocomplete="off"  readonly>
                                        </td>
                                         </tr>
                            </table>
                           
                
                        </form>
                        </div>
    </div>
    @endif
@endsection
<!-- END Content Section -->

@section('scripts')


<script type="text/javascript">

$(".button").on('change',function (){
      var modeval =$(this).val();
        // alert(modeval);
     /* if(modeval == 'Forward'){
            $('#deci').show(); 
       }
      else {
            $('#deci').hide();
       }*/
       
       if(modeval == 'reject'  ){ 
           $('.cb').prop('required', true);

           // $('#R1').css("display", "none");
       }else{
           $('.cb').prop('required', false);

           //$('#R1').css("display", "block");

           
       }
       
    }); 
</script>






<script>
    // get the Department data
    $('#division_id').on('change',function(){
        var division_ID = $(this).val();
        $("#department_id").html('<option value="">--Select--</option>');
        if(division_ID)
        {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'GET',
                url:"{{route('admin.user.department')}}/" + division_ID,
                contentType:'application/json',
                dataType:"json",
                success:function(data){
                    console.log(data);
                    // $("#sectionID").html('<option value="0">--Select--</option>');
                    for(var i=0;i<data.length;i++){
                        $("#department_id").append('<option value="'+data[i].id+'" >'+data[i].department_name+'</option>');
                    }
                }
            });
        }else{
            $('#department_id').html('<option value="null">Select Department</option>');
        }
    });

    
    $("#add_sup").on("click", function (e) {
        var count = $(".remove_tr").length + 1;
        $('#append_sup').append(`<input type="hidden" name="uni_sup[]"><input type="text" class="form-control" name="supervisor[]" id="supervisor_id">&nbsp;`);
    });
    
    //Append code
    $("#btn-add-vendor").on("click", function (e) {
    var incrementjquery = $("#increment").val();
    incrementjquery++;
    $("#increment").val(incrementjquery);
    console.log(incrementjquery);
    var datas='';
        datas +='<tr class="appendrow">';
        datas +='<td><input type="text" class="form-control" name="supervisor_ven[]"> <input type="hidden" name="uni_id[]"></td>';
        datas +='<td><input type="text" class="form-control" name="electrical_license_ven[]"></td>';
        datas +='<td><input type="date" class="form-control" name="license_validity_ven[]"></td>';
        datas +='<td><table style="width: 180px;">';
                    datas +='<tr><td><span>132KV</span></td>';
                        datas +='<td><input type="radio" name="v132kv_ven['+incrementjquery+']" checked value="no">&nbsp; No';
                        datas +='<input type="radio" name="v132kv_ven['+incrementjquery+']" value="yes">&nbsp; Yes';
                        datas +='</td>';
                    datas +='<tr>';
                    datas +='<tr><td><span>33KV</span></td>';
                        datas +='<td><input type="radio" name="v33kv_ven['+incrementjquery+']" checked value="no">&nbsp; No';
                        datas +='<input type="radio" name="v33kv_ven['+incrementjquery+']" value="yes">&nbsp; Yes';
                        datas +='</td>';
                    datas +='<tr><td><span>11KV</span></td>';
                        datas +='<td><input type="radio" name="v11kv_ven['+incrementjquery+']" checked value="no">&nbsp; No';
                        datas +='<input type="radio" name="v11kv_ven['+incrementjquery+']" value="yes">&nbsp; Yes';
                        datas +='</td>';
                    datas +='<tr><td><span>LT</span></td>';
                        datas +='<td><input type="radio" name="vlt_ven['+incrementjquery+']" checked value="no">&nbsp; No';
                        datas +='<input type="radio" name="vlt_ven['+incrementjquery+']" value="yes">&nbsp; Yes';
                        datas +='</td>';
                    datas +='<tr>';
                datas +='</table>';
            datas +='</td>';
            datas +='<td>';
                datas +='<label class="form-check-label">';
                    datas +='<input type="radio" class="" name="issue_power_ven['+incrementjquery+']"  checked value="yes">&nbsp; Yes';
                datas +='</label>';
                datas +='<label class="form-check-label">';
                    datas +='<input type="radio" class=""  name="issue_power_ven['+incrementjquery+']" value="no">&nbsp; No';  
                datas +='</label>';
            datas +='</td>';
            datas +='<td>';
                datas +='<label class="form-check-label">';
                    datas +='<input type="radio" class="" name="rec_power_ven['+incrementjquery+']" checked value="yes">&nbsp; Yes';
                datas +='</label>';
                datas +='<label class="form-check-label">';
                    datas +='<input type="radio" class=""  name="rec_power_ven['+incrementjquery+']" value="no">&nbsp; No '; 
                datas +='</label>';
            datas +='</td><td></td>';
    datas +='</tr>';
    $('#dataview').append(datas);

    });

    //Remove 
    $("#btn-remove-vendor").on("click", function (e) {
        if($('.appendrow').length > 1){
            $(".appendrow:last").remove();
            var incrementjquery = $("#increment").val();
            incrementjquery--;
            $("#increment").val(incrementjquery);
        }
    });


    //gate pass Details to add
    $("#btn-add").on("click", function (e) {
            var count = $(".remove_tr").length + 1;
            // console.log(count);
            $('#append_gatepass').append(`<tr class="gatepass">
                    <td><input type="hidden" name="oldgatepassid[]"><input type="text" class="form-control" name="employee[]"></td>
                    <td><input type="text" class="form-control" name="gatepass[]"></td>
                    <td><input type="text" class="form-control" name="designation[]"></td>
                    <td><input type="text" class="form-control" name="age[]"></td>      
                    <td><input type="date" class="form-control" name="expirydate[]"></td><td></td>       

                </tr>`);
    });

    //Remove Top Click
    $("#btn-remove").on("click", function (e) {
        if($('.gatepass').length > 1){
            $(".gatepass:last").remove();
        }
    });
    function ElectricalSupervisoryEmployee(items){
        if(items!="")
        {
            if(items == 'yes'){
                $("#Electrical_Yes").show();
            }
            else if(items == 'no')   
            {
                $("#Electrical_Yes").hide();
            }
        }
    }
  
</script>
@endsection


