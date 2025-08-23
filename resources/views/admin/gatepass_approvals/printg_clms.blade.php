<?php 
use App\Department;
use App\UserLogin;
use App\Division;

$gatepassv = DB::table('Clms_gatepass')->where('id',$id)->first();
//echo $id;
//exit;
@$department_p = Department::where('id',@$gatepassv->department)->first();
@$division_p = Division::where('id',@$gatepassv->division_id)->first();
@$approver = UserLogin::where('id',@$gatepassv->created_by)->first();
@$work = DB::table('work_order')->where('id',@$gatepassv->work_order_no)->first();
                       
                       
?>
 
@extends('admin.app2')
                       
<style>
.p{
   //padding-left:100px;
    padding-bottom:2px;
 font-size:20px;
}
.t{
   
 font-size:20px;
}
.float-container {
    border: 3px solid #fff;
    padding: 20px;
}

.float-child {
    width: 50%;
    float: left;
    padding: 20px;
    //border: 2px solid red;
}  
.solid{
	border-style: double;

border-width: 2px;
width:1000px;
height:1000px;
}
* {
  box-sizing: border-box;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 33.33%;
  padding: 10px;
  //height: 300px; /* Should be removed. Only for demonstration */
}

</style> 
<div class="solid"> <br>




<div class="row">
  <div class="column" >
    <img src="{{ URL::to('images/logo-cin1.png') }}"  style="float:left;width:188px;">
  </div>
  <div class="column" >
   <div style="text-align:center;" >  <h1 style="white-space: nowrap">Contractor GatePass </h1> </div>   
  </div>
  <div class="column" style="display: none;">
     <h5 style="text-align:right;">CLGP No: {{ @$gatepassv->full_sl}} <p style="white-space: nowrap">&nbsp;&nbsp;</p></h5>
  </div>
</div>

<div class="float-container">

  <div class="float-child">

    <div class="green">


	<table class="t1">


        <tr style="padding-bottom: 5px;" >
           
       <th style="border-style: solid;"><a href="" ><img  src="https://wps.jamipol.com/documents/clm_pics/{{@$gatepassv->upload_photo}}" width="150px" height="100px" ></a>
         
 
          </th>
          <br><b style="background-color: white;text-align: center;border-style: solid; display: none;" >{{@$gatepassv->job_role}}</b>
 </tr>

<tr >
<th class="p" >Name :</th>
<td class="t"> {{ @$gatepassv->name}}</td>
</tr>
<tr >
<th class="p" >GP No:</th>
<td class="t"><h5 >{{ @$gatepassv->full_sl}} </h5></td>
</tr>
<tr >
<th class="p" >Valid From :</th>
<td class="t"> {{ @$gatepassv->valid_to}}</td>
</tr>
<tr >
<th class="p" >Upto :</th>
<td class="t"> {{ @$gatepassv->valid_till}}</td>
</tr>

<tr>
<th class="p">DOB :</th>
<td class="t"> {{ date('d-F-Y', strtotime(@$gatepassv->date_of_birth))}}</td>
</tr>
<tr >
<th class="p">Gender : </th>
<td class="t">{{ ucfirst(@$gatepassv->gender)}}</td>
</tr>
<tr >
<th class="p">Identity Proof : </th>
<td class="t">{{ ucfirst(@$gatepassv->identity_proof)}}</td>
</tr>
<tr>
<th class="p" >Vendor Name:</th>
<td class="t">{{ @$approver->name}}</td>
</tr>
 <tr >
<th class="p">Work Order :</th>
 <td class="t">{{ @$gatepassv->work_order_no}}</td>
</tr>
<tr >
<th class="p"> Order Validity :</th>
 <td class="t">{{ date('d-m-Y',strtotime(@$gatepassv->work_order_validity))}}</td>
</tr>
</table>

</div>
</div>
  </div>
  
  <div class="float-child">
    <div class="blue"><div ><b><h3>Instruction :</h3></b>
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
</p>
</div></div>
<br>
<br>
<br>
<style>
    
    #block_container {
        display: flex;

    }
    #bloc2{
    	padding-left:180px;
        padding-bottom:2px;
    }

</style>
<div id="block_container">
  <div id="bloc1">Authorised Signatory</div>
  &nbsp;&nbsp;&nbsp;&nbsp;
  <div id="bloc2">Visitor's Signature</div>
</div>


  </div>
  
</div>

</div>




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
               
        
