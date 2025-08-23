<?php 
use App\Department;
use App\UserLogin;
use App\Division;

$gatepassv = DB::table('Clms_gatepass')->where('id',$id)->first();
//echo $id;
//exit;
@$department_p = Department::where('id',@$gatepassv->department)->first();
@$division_p = Division::where('id',@$gatepassv->division)->first();
@$approver = UserLogin::where('id',@$gatepassv->created_by)->first();
@$work = DB::table('work_order')->where('id',@$gatepassv->work_order_no)->first();
                       
                       
?>
@extends('admin.app2')
<style>
table, th, td {
  border: 1px solid black;
}
</style>

<table style="width:100%;height:10%;">
  <tr>
    <th colspan="10"><center><img src="{{ URL::to('images/logo-cin1.png') }}"  style="width:188px;"></center></th>
  </tr>
  
  <tr>
    <th colspan="10" style="height:50px;"><b style="font-size:25px;">Pass For: {{@$gatepassv->job_role}} </b><span style="float:right;">Works: {{@$division_p->abbreviation}}&nbsp;&nbsp;</span></th>
           
  </tr>
  
  <tr >
    <td style="width:200px;">
    <img  src="https://wps.jamipol.com/documents/clm_pics/{{ @$gatepassv->upload_photo}}" width="200px" height="200px" >

     

</td>
    <td  style="margin: 0 25px 25px 25px; border-collapse: collapse"><h4><b>Name : {{ @$gatepassv->name}}</b> 
    </h4>
         <b> BG:   &nbsp; 0+   </b> &nbsp;&nbsp;             <b>  Gender:&nbsp;{{ ucfirst(@$gatepassv->gender)}}     </b> &nbsp;&nbsp; &nbsp;&nbsp;<b> DOB: {{ date('d-F-Y', strtotime(@$gatepassv->date_of_birth))}}  </b> &nbsp;  <br>

         <b> GP Number: &nbsp; {{@$gatepassv->full_sl}} </b><br>

         <b> GP Valid From:  &nbsp; {{ date('d-m-Y', strtotime(@$gatepassv->valid_to))}} to {{ date('d-m-Y', strtotime(@$gatepassv->valid_till))}} </b>&nbsp;&nbsp;    &nbsp;&nbsp;    &nbsp;&nbsp; &nbsp;&nbsp;    &nbsp;<br>
        <b>  UAN No:  {{@$gatepassv->uan_no}}        </b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                             <b>  ESIC No:   {{@$gatepassv->esic}}      </b><br>

  <!--<b> Department: {{@$department_p->department_name}}           </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                       <b>    Identification Mark:          </b>-->
                                                         
     
  </tr>
  <tr >
    <br>
      <th colspan="10">
          
          Vendor Name: &nbsp;&nbsp; {{ @$approver->name}}   <br>
Work Order:     &nbsp;&nbsp;    {{ @$gatepassv->work_order_no}}        <br>
Work Order Validity: &nbsp;&nbsp; {{ date('d-m-Y',strtotime(@$gatepassv->work_order_validity))}}      
      </th>

  </tr>
    
</table>
<br>
<br>
<br>
<!---------     INSTRUCTION      --------->

<table style="width:100%;height:10%;">
  <tr>
    <th colspan="10">
<center> <img src="{{ URL::to('/images/logo-cin1.png') }}"  style="width:188px;"></center></th>
  </tr>
  
  <tr>
    <th colspan="10" style="height:50px;">
        <center>  <span ><h4><b>Instructions:
</span> </b> </h4></center>
        <br>
      

        <p style="font-size:20px;">1.This pass is non-transferable
.<br>
2.To be shown to the Company’s authorities on demand.
<br>
3.Contractor’s Officers/Supervisors/Labours are to be allowed for works only through main gate 
.<br>
4.Valid only for the location of the job.<br>
5.Gate Pass to be surrendered to the security section within 07 days on job termination /expiry of the validity of the gate pass ( whichever is earlier) falling which penalty @Rs.50/- each to be charged per day thereafter
.<br>
6.Using mobile phone on shop floor and works is strictly prohibited
.<br>
7.Safety Induction is mandatory for the gate pass holders
.<br>
8.Security Office No: Tel-  +91 657 234 5428
<br>
9.Loss/Damage of gate pass to be reported to the issuing authority immediately
.<br>
10.Loss/Damage charge of this gate pass is Rs.100/-.
</p></th>
           
  </tr>
  
  
  
    
</table>


        <!-- <div class="column" >
    <img src="{{ URL::to('public/images/logo-cin1.png') }}"  style="float:left;width:188px;">
  </div>  
<tr>
    <td>3</td>
    <td>Andy Roberts</td>
    <td>15</td>
    <td>Fail</td>
  </tr>

-->             

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
    $(document).ready(function(e){
        $(".hide").each(function(e){
            $(this).closest('tr').remove();

        });
        setTimeout(function(){
            window.print();
        },200);
    })
</script> 
    
        
