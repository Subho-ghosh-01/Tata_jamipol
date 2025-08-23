<?php
use App\Permit;
use App\Division;
use App\Job;
use App\UserLogin;
use App\Permit_Hazard;
use App\Department;
use App\PowerClearence;
use App\ConfinedSpace;
use App\GatePassDetail;
use App\PowerShutdown;
use App\VendorSupervisor;
use App\RenewPermit;
use App\PowerCutting;
use App\PowerGetting;


$permit_p = Permit::where('id',$id)->first();
// echo $id;
// exit;
// get start date
$st_date = @$permit_p->start_date;
$st_time = date('h:i A', strtotime($st_date));
$start_date = date('d-m-Y', strtotime($st_date));

//get end date
$ed_date = @$permit_p->end_date;
// echo $ed_date;
$ed_time = date('h:i A', strtotime($ed_date));
$end_date = date('d-m-Y', strtotime($ed_date));

//department data
@$department_p = Department::where('id',@$permit_p->department_id)->first();

//division data generate serialo number
$abb = Division::where('id',@$permit_p->division_id)->first();
$created_date = @$permit_p->created_at;
$serial_no    = @$permit_p->serial_no;
$month = date('m-Y', strtotime($created_date));

//Job Details
$job_p = Job::where('id',@$permit_p->job_id)->first();

//User Details 	Permit Requested by
$user_p          	= UserLogin::where('id',@$permit_p->entered_by)->first();
$department_nameReq = Department::where('id',@$user_p->department_id)->first();

//User Details Issued By
$issuer_id 				= UserLogin::where('id',@$permit_p->issuer_id)->first();
$department_nameIss 	= Department::where('id',@$issuer_id->department_id)->first();

// get Permit Hazard 
$permit_haz 			= Permit_Hazard::where('permit_id',$id)->get();
// get power Clearence 
$power_clearances  		= PowerClearence::where('permit_id',$id)->get();
// get confined Space details 
$confined_spaces   		= ConfinedSpace::where('permit_id',$id)->get();
// area Clearence name
$area_clearence_name = UserLogin::where('id',@$permit_p->area_clearence_id)->first();
$areaDepartment    = Department::where('id',@$area_clearence_name->department_id)->first();


$gate_pass_details   = GatePassDetail::where('permit_id',$id)->get();
//old time & new time
if ($permit_p->renew_id_1)
{
$renewal1 = RenewPermit::where('id',@$permit_p->renew_id_1)->first();
}
if ($permit_p->renew_id_2)
{
$renewal2 = RenewPermit::where('id',@$permit_p->renew_id_2)->first();
}
////////////////////// For supervisor name  /////////////////////////////////
$vendorSupervisorName   = VendorSupervisor::where('vendor_id',$permit_p->entered_by)->get();
$PC = PowerCutting::where('id',@$permit_p->pc_id)->first();
$PG = PowerGetting::where('id',@$permit_p->pg_id)->first();

?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1252">
<title>Permit</title>
<style>
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
</style>
</head>

<body>
<?php
// $url=$_SERVER['HTTP_REFERER'];
// $explode=explode("/",$url);
// $o=sizeof($explode);
// $o=$o-2;
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="33%" align="center">
		<p align="left"><font face="Arial" size="2">Form # JAM/IMS/SAFETY/25<br>
		Effective Date : 01.07.18</font></td>
		<td width="36%" align="center" colspan="2"><font face="Arial" size="2">
		REV. # 05<br>
		</font><font face="Arial" size="4"><b>JAMIPOL LIMITED</b></font></td>
		<td width="30%" align="center">
		<p align="right"><font face="Arial" size="2">Sl. No. 
		<strong> {{@$abb->abbreviation}}/{{$month}}/{{$serial_no}} </strong> 
		<p style="text-align:right;"> Status : <strong> {{ @$permit_p->status}} </strong><br>
		Date : <strong> {{date('d-m-Y h:i:s A', strtotime(@$permit_p->start_date)) }} </strong> </font></td>
	</tr>
	<tr>
		<td colspan="4" align="center"><font face="Arial" size="2"><b>Reference 
		Safety Standard 1. JAM/SS/31 (Work Permit System)</b><br>
		PERMIT TO WORK FOR INDIVIDUAL AGENCY</font></td>
	</tr>
	<tr>
		<td colspan="4" align="center">
		<p align="left">&nbsp;</p>
		<table border="1" width="62%" cellspacing="0" cellpadding="0" bordercolor="#000000">
			<tr>
				<td>
				<p align="center"><font face="Arial" size="2"><b>Permit to work 
				format is valid for only one job in the specific area</b></font></p>
				<p align="left"><font face="Arial" size="2">(a) For routine 
				jobs. Shut down jobs and break down jobs (&lt;48 hrs jobs) permit 
				will be renewed after one shift<br>
				(A shift, B shift, C shift &amp; General Shift)<br>
				(b) For MSD (&gt;48 Hrs jobs) permit will be renewed after 24 Hrs.</font></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
		<p align="left"><font face="Arial" size="2">
		Department <strong> {{@$department_p->department_name}}  </strong>  </font></td>
		<td colspan="2" align="center">
		<p align="right"><font face="Arial" size="2"></font></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font face="Arial" size="2">Original 
		permit Valid</font></td>
		<td align="center" width="29%"><font face="Arial" size="2">From (Time &amp; 
		Date ) <b>{{$start_date}} {{$st_time}}</b></font></td>
		<td align="center"><font face="Arial" size="2">To (Time &amp; Date ) 
		<b>{{$end_date}} {{$ed_time}}</b></font></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font face="Arial" size="2">1st renewal 
		valid</font></td>
		<td align="center" width="29%"><font face="Arial" size="2">From (Time &amp; 
		Date ) <font face="Arial" size="2"> <b>@if ($permit_p->renew_id_1){{ date('d-m-Y h:i:s A', strtotime(@$renewal1->old_time)) }} @endif</font></td>
		<td align="center"><font face="Arial" size="2">To (Time &amp; Date ) 
		<font face="Arial" size="2"><b>@if ($permit_p->renew_id_1){{ date('d-m-Y h:i:s A', strtotime(@$renewal1->new_time)) }} @endif</font></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font face="Arial" size="2">2nd renewal 
		valid</font></td>
	<td align="center" width="29%"><font face="Arial" size="2">From (Time &amp; 
		Date ) <font face="Arial" size="2"> <b>@if ($permit_p->renew_id_2){{date('d-m-Y h:i:s A', strtotime(@$renewal2->old_time)) }} @endif</font></td>
		<td align="center"><font face="Arial" size="2">To (Time &amp; Date ) 
		<font face="Arial" size="2"><b>@if ($permit_p->renew_id_2){{date('d-m-Y h:i:s A', strtotime(@$renewal2->new_time)) }} @endif</font></font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><font face="Arial" size="2">1. Name / 
		Description of Job :</font></td>
		<td align="center" colspan="2"><font face="Arial" size="2"><strong>{{@$job_p->job_title}}/{{@$permit_p->job_description}}</font></strong></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><font face="Arial" size="2">2. Name of 
		Working Agency and Work Order No.</font></td>
		<td align="center" colspan="2"><font face="Arial" size="2"><strong>{{$user_p->name}}/ Work Order No - {{@$permit_p->order_no}}</strong></font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><font face="Arial" size="2">3. Work 
		Permitted only for the area / Equipment (Name / No.) </td>
		<td align="center" colspan="2"><font face="Arial" size="2"><strong>{{@$permit_p->job_location}}</strong></font></td>
	</tr>
	<tr>
		<td colspan="4" align="left"><font face="Arial" size="2">4. TYPE OF JOB 
		(To be filled by Working Agency and verified by Executing Agency)</font></td>
	</tr>
	<tr>
		<td colspan="4" align="left">
		<div align="center">
			<table border="0" width="66%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="167"><font face="Arial" size="2">a) Welding/Gas 
					Cutting</font></td>
					<td width="85"><input type="checkbox" @if($permit_p->welding_gas =='on') checked @endif disabled></td>
					<td width="166"><font face="Arial" size="2">b) Riggine / 
					Fittings</font></td>
					<td width="96"><input type="checkbox" @if($permit_p->riggine =='on') checked @endif disabled></td>
					<td width="178"><font face="Arial" size="2">c) Working at 
					Height</font></td>
					<td><input type="checkbox" @if($permit_p->working_at_height =='on') checked @endif disabled></td>
				</tr>
				<tr>
					<td width="167"><font face="Arial" size="2">d) 
					Hydraulic/Pneumatic</font></td>
					<td width="85"><input type="checkbox" @if($permit_p->hydraulic_pneumatic =='on') checked @endif disabled></td>
					<td width="166"><font face="Arial" size="2">e) 
					Painting/Cleaning</font></td>
					<td width="96"><input type="checkbox" @if($permit_p->painting_cleaning =='on') checked @endif disabled></td>
					<td width="178"><font face="Arial" size="2">f) Gas</font></td>
					<td><input type="checkbox" @if($permit_p->gas =='on') checked @endif disabled></td>
				</tr>
				<tr>
					<td width="167"><font face="Arial" size="2">g) Others 
					(Specify)</font></td>
					<td width="85"><input type="checkbox" @if($permit_p->others =='on') checked @endif disabled></td>
					<td colspan="4"><font face="Arial" size="2">@if($permit_p->others =='on') {{$permit_p->specify_others}} @endif</font></td>
				</tr>
			</table>
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="4" align="left"><font face="Arial" size="2">NOTE : For 
		instruction/Checklist on above job see overleaf. Working Agency must 
		verify all requirement by himself before applying Work Permit and 
		Executing Agency must verify before issue of Work Permit.</font></td>
	</tr>
	<tr>
		<td colspan="4" align="left"><font face="Arial" size="2">5. NECESSARY 
		PRECAUTION / CONTROLS TAKEN FOR ABOVE JOB</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><font face="Arial" size="2">a) Confined 
		Space permit taken (JAM/IMS/SAFETY/26)</font></td>
		<td colspan="2" align="left"><font face="Arial" size="2">Permit no.
		@if($confined_spaces->count() > 0)
            @foreach($confined_spaces as $key => $value)
				<u>{{$confined_spaces[$key]->clearance_no}}</u><br> 
			@endforeach
		@endif
		</font></td>
	</tr>
	<tr> 
		<td colspan="2" align="left"><font face="Arial" size="2">c) Electrical Power Cutting done (JAM/IMS/SAFETY/27)</font></td>
		<td colspan="2" align="left"><font face="Arial" size="2"><br>
			@if($permit_p->power_clearance_number !="")
				Permit no.: <strong><u>{{ @$permit_p->power_clearance_number }} </u></strong><br> 
				Power Cutting Remarks: <strong><u> {{@$permit_p->power_cutting_remarks}}</u></strong><br>
			    Power Cutting User:  <strong><u> @php $pcuser  = UserLogin::where('id',@$permit_p->ppc_userid)->first(); @endphp {{@$pcuser->name}}</u></strong><br>
			    Time: <strong><u>  @if(@$permit_p->power_cutting_user_dt) {{date('d-m-Y H:i',@strtotime(@$permit_p->power_cutting_user_dt))}}  @endif </u></strong><br>
		    	Executing Personal Lock Number: <strong><u> {{ @$permit_p->executing_lock }} </u></strong><br>
		    	Working Personal Lock Number: <strong><u> {{ @$permit_p->working_lock }} </u></strong><br>
		    	@if($power_clearances->count() > 0)

	                @foreach($power_clearances as $key => $value)
	                    ({{$key+1}}) Equipment Lock No.:<strong> <u> {{$power_clearances[$key]->positive_isolation_no}} </u></strong>,
	                    Box No.: <strong><u> {{$power_clearances[$key]->box_no}} </u></strong><br> 
	                @endforeach
				@endif
		    @endif

		    </font>
		</td>
			
	</tr>
	<tr>
		<td colspan="2" align="left"><font face="Arial" size="2"><br>c) Electrical Power getting (JAM/IMS/SAFETY/28)</font></td>
		<td colspan="2" align="left"><font face="Arial" size="2"><br>
			@if($permit_p->pg_number !="")
				Permit no. <strong><u>{{$permit_p->pg_number}}</u></strong><br>
				@php $pg_comment  = PowerGetting::where('id', $permit_p->pg_id)->first(); @endphp
				Power Getting Remarks: <strong><u> {{@$pg_comment->power_cutting_comment }}</u> </strong><br>
				Power Getting User:  <strong><u> @php $pguser  = UserLogin::where('id',@$permit_p->ppg_userid)->first(); @endphp {{@$pguser->name}}</u></strong><br>
			    Time: <strong><u> @if(@$permit_p->pg_action_dt) {{date('d-m-Y H:i',@strtotime(@$permit_p->pg_action_dt))}}  @endif </u></strong>
			@endif
		</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><font face="Arial" size="2">d) Positive isolation done (Energy Isolated/released) <br>
		(Positive isolation Lock No., if applicable)</font></td>
		<td colspan="2" align="left"><font face="Arial" size="2">Permit no. 
            {{--@if($power_clearances->count() > 0)
                @foreach($power_clearances as $key => $value)
                  <u>{{$power_clearances[$key]->positive_isolation_no}}</u><br>
                @endforeach
			@endif --}}</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><font face="Arial" size="2">6. Special 
		instruction, if any :</font></td>
		<td colspan="2" align="left">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" align="left"><font face="Arial" size="2">7. I understand 
		the hazard involved and have taken all necessary precautions for the 
		job.</font></td>
	</tr>
	<tr>
		<td colspan="4" align="left">
		<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000">
			<tr>
				<td align="center">&nbsp;</td>
				<td align="center">&nbsp;</td>
				<td colspan="2" align="center"><b><font face="Arial" size="2">Permit Original</font></b></td>
				<td align="center" width="24%" colspan="2"><b>
				<font face="Arial" size="2">1st Permit Renewal</font></b></td>
				<td align="center" width="21%" colspan="2"><b>
				<font face="Arial" size="2">2nd Permit Renewal</font></b></td>
			</tr>
			<tr>
				<td align="center" width="9%">&nbsp;</td>
				<td align="center" width="12%"><b><font face="Arial" size="2">Permit Requested by (Working Agency)</font></b></td>
				<td align="center" width="16%"><b><font face="Arial" size="2">Issued By <br>(Owner Agency)</font></b></td>
				<td align="center" width="10%"><b><font face="Arial" size="2">Taken By <br>(Working Agency)</font></b></td>
				<td align="center" width="16%"><b><font face="Arial" size="2">Issued By <br>(Owner Agency)</font></b></td>
				<td align="center" width="9%"><b><font face="Arial" size="2">Taken By <br>(Working Agency)</font></b></td>
				<td align="center" width="10%"><b><font face="Arial" size="2">Issued By <br>(Owner Agency)</font></b></td>
				<td align="center" width="9%"><b><font face="Arial" size="2">Taken By <br>(Working Agency)</font></b></td>
			</tr>
			<tr>
				<td align="center" width="9%"><b><font face="Arial" size="2">Name<br>P.NO.</font></b></td>
				<td align="center" width="12%"><font face="Arial" size="2">{{@$user_p->name}}<br>{{@$user_p->vendor_code}}</font></td>
				<td align="center" width="16%"><font face="Arial" size="2">{{@$issuer_id->name}}<br>{{@$issuer_id->vendor_code}}</font></td>
				<td align="center" width="10%"><font face="Arial" size="2">{{@$user_p->name}}<br>{{@$user_p->vendor_code}}</font></td>
				<?php  @$renewal1i = UserLogin::where('id',$renewal1->issuer_id)->first(); ?>
				<?php  @$renewal1a = UserLogin::where('id',$renewal1->area_id)->first(); ?>
				<?php  @$renewal1di = Department::where('id',$renewal1i->department_id)->first(); ?>
				<?php  @$renewal1da = Department::where('id',$renewal1a->department_id)->first(); ?>

				<?php  @$renewal2i = UserLogin::where('id',$renewal2->issuer_id)->first(); ?>
				<?php  @$renewal2a = UserLogin::where('id',$renewal2->area_id)->first(); ?>
				<?php  @$renewal2di = Department::where('id',$renewal2i->department_id)->first(); ?>
				<?php  @$renewal2da = Department::where('id',$renewal2a->department_id)->first(); ?>

				<td align="center" width="16%"><font face="Arial" size="2">{{@$renewal1i->name}}<br>{{@$renewal1i->vendor_code}}</font></td>
				<td align="center" width="9%"><font face="Arial" size="2">{{@$renewal1a->name}}<br>{{@$renewal1a->vendor_code}}</font></td>
				<td align="center" width="10%"><font face="Arial" size="2">{{@$renewal2i->name}}<br>{{@$renewal2i->vendor_code}}</font></td>
				<td align="center" width="9%"><font face="Arial" size="2">{{@$renewal2a->name}}<br>{{@$renewal2a->vendor_code}}</font></td>
				</tr>
			<tr>
				<td align="center" width="9%"><b><font face="Arial" size="2">Designation/Section</font></b></td>
				<td align="center" width="12%"><font face="Arial" size="2">{{ @$department_nameReq->department_name }}</font></td>
				<td align="center" width="16%"><font face="Arial" size="2">{{ @$department_nameIss->department_name }}</font></td>
				<td align="center" width="10%"><font face="Arial" size="2">{{ @$department_nameReq->department_name }}</font></td>
				<td align="center" width="16%"><font face="Arial" size="2">{{ @$renewal1di->department_name }}</font></td>
				<td align="center" width="9%"><font face="Arial" size="2">{{ @$renewal1da->department_name }}</font></td>
				<td align="center" width="10%"><font face="Arial" size="2">{{ @$renewal2di->department_name }}</font></td>
				<td align="center" width="9%"><font face="Arial" size="2">{{ @$renewal2da->department_name }}</font></td>
			</tr>
			<tr>
				<td align="center" width="9%"><b><font face="Arial" size="2">Signature</font></b></td>
				<td align="center" width="12%"><font face="Arial" size="2"></font></td>
				<td align="center" width="16%"><font face="Arial" size="2"></font></td>
				<td align="center" width="10%"><font face="Arial" size="2"></font></td>
				<td align="center" width="16%">&nbsp;</td>
				<td align="center" width="9%">&nbsp;</td>
				<td align="center" width="10%">&nbsp;</td>
				<td align="center" width="9%">&nbsp;</td>
			</tr>
			<tr>
				<td align="center" width="9%"><b><font face="Arial" size="2">Date &amp; Time</font></b></td>
				<td align="center" width="12%"><font face="Arial" size="2">@if(@$permit_p->request_dt){{ date('d-m-Y H:i:s A', strtotime(@$permit_p->request_dt)) }} @endif</font></td>
				<td align="center" width="16%"><font face="Arial" size="2">@if(@$permit_p->issuer_dt) {{ date('d-m-Y H:i:s A', strtotime(@$permit_p->issuer_dt)) }} @endif</font></td>
				<td align="center" width="10%"><font face="Arial" size="2">@if(@$permit_p->area_clearence_dt){{ date('d-m-Y H:i:s A', strtotime(@$permit_p->area_clearence_dt)) }} @else {{ date('d-m-Y H:i:s A', strtotime(@$permit_p->issuer_dt)) }} @endif</font></td>
				<td align="center" width="16%"><font face="Arial" size="2">@if(@$renewal1->issuer_confirm_dt){{date('d-m-Y h:i:s A', strtotime(@$renewal1->issuer_confirm_dt)) }} @endif</font></td>
				<td align="center" width="9%"><font face="Arial" size="2">@if(@$renewal1->area_confirm_dt){{date('d-m-Y h:i:s A', strtotime(@$renewal1->area_confirm_dt)) }} @endif</font></td>
				<td align="center" width="10%"><font face="Arial" size="2">@if(@$renewal2->issuer_confirm_dt){{date('d-m-Y h:i:s A', strtotime(@$renewal2->issuer_confirm_dt)) }} @endif</font></td>
				<td align="center" width="9%"><font face="Arial" size="2">@if(@$renewal2->area_confirm_dt){{date('d-m-Y h:i:s A', strtotime(@$renewal2->area_confirm_dt)) }} @endif</font></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="4" align="left"><font face="Arial" size="2">8. Return of 
		Permit : All men/material removed after job completion. Now, area is 
		safe for operation</font></td>
	</tr>
	<tr>
		<td colspan="4" align="left">
		<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000">
			<tr>
				<td align="center" width="9%">&nbsp;</td>
				<td align="center" width="32%"><b><font face="Arial" size="2">
				Returned by (Working Agency or taken by)</font></b></td>
				<td align="center" width="27%"><b><font face="Arial" size="2">
				Received (Executing Agency or requester)</font></b></td>
				<td align="center" width="29%"><b><font face="Arial" size="2">
				Received (Owner Agency or Issuer)</font></b></td>
			</tr>
			<tr>
				<td align="center" width="9%" height="44"><b>
				<font face="Arial" size="2">Name<br>
				P.NO.</font></b></td>
				<td align="center" width="32%" height="44"><font face="Arial" size="2">{{@$user_p->name}}<br>{{@$user_p->vendor_code}}</font></td>
				<td align="center" width="27%" height="44"><font face="Arial" size="2">{{@$issuer_id->name}}<br>{{@$issuer_id->vendor_code}}</font></td>
				<td align="center" width="29%" height="44"><font face="Arial" size="2">@if(@$permit_p->area_clearence_required =='on') {{@$area_clearence_name->name}}<br>{{@$area_clearence_name->vendor_code}} @endif</font></td>
			</tr>
			<tr>
				<td align="center" width="9%"><b><font face="Arial" size="2">
				Designation/Section</font></b></td>
				<td align="center" width="32%"><font face="Arial" size="2">{{ @$department_nameReq->department_name }}</font></td>
				<td align="center" width="27%"><font face="Arial" size="2">{{ @$department_nameIss->department_name}}</font>&nbsp;</td>
				<td align="center" width="29%"><font face="Arial" size="2">@if(@$permit_p->area_clearence_required =='on'){{ @$areaDepartment->department_name }} @endif </font></td>
			</tr>
			<tr>
				<td align="center" width="9%"><b><font face="Arial" size="2">
				Signature</font></b></td>
				<td align="center" width="32%">&nbsp;</td>
				<td align="center" width="27%">&nbsp;</td>
				<td align="center" width="29%">&nbsp;</td>
			</tr>
			<tr>
				<td align="center" width="9%"><b><font face="Arial" size="2">
				Date &amp; Time</font></b></td>
				<td align="center" width="32%"><font face="Arial" size="2">@if(@$permit_p->complete_date){{ date('d-m-Y H:i:s A', strtotime(@$permit_p->complete_date)) }} @endif</font></td>
				<td align="center" width="27%"><font face="Arial" size="2">@if(@$permit_p->issuer_return_date){{ date('d-m-Y H:i:s A', strtotime(@$permit_p->issuer_return_date)) }} @endif</font></td>
				<td align="center" width="29%"><font face="Arial" size="2">@if(@$permit_p->area_clearence_required =='on') @if(@$permit_p->area_return_date) {{ date('d-m-Y H:i:s A', strtotime(@$permit_p->area_return_date)) }} @endif  @endif </font></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="4" align="left"><font face="Arial" size="2">1. Permit to be 
		signed by Shift Incharge/Person Incharge of the Job/Supervisor/Officer. 
		other person authorised by Head of the Department.<br>
		2. Contractor should provide one Site Safety Supervisor for 20 contract 
		employee.<br>
		3. Proximity/6 directrion hazards should be assessed and filled by 
		Executing Agency before starting of job and communicate to Working 
		Agency.<br>
		4. Job safety analysis to be done by execusing agency in consultation 
		with safety department.</font></td>
	</tr>
	<tr>
		<td colspan="4" align="left">
		<p align="center"><font face="Arial" size="2"><b>	<br>
		SIX DIRECTIONAL HAZARD 
		FORM (To be filled by executing agency)</b></font></td>
	</tr>
	<tr>
		<td colspan="4" align="left"><table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000">
			<tr>
				<td width="50"><font face="Arial" size="2">Direction</font></td>
				<td width="172"><font face="Arial" size="2">Hazard</font></td>
				<td width="237"><font face="Arial" size="2">Precaution</font></td>
			</tr>
            @if($permit_haz->count() > 0)
                @foreach($permit_haz as $key => $value)
                    <tr>
                        <td width="50"><font face="Arial" size="2">{{$permit_haz[$key]->dir}}</font></td>
                        <td width="172"><font face="Arial" size="2">{{$permit_haz[$key]->hazard}}</font></td>
                        <td width="237"><font face="Arial" size="2">{{$permit_haz[$key]->precaution}}</font></td>
                    </tr>
                @endforeach
			@endif
		</table>	</td>
	</tr>
	<tr>
		<td colspan="4" align="left">
		<p align="right">&nbsp;</p>
		<p align="right"><b><font face="Arial" size="2"><br>
		Sign. of Permit Requested by</font></b></td>
	</tr>
	<tr>
		<td colspan="4" align="left">
		<div align="center">
			<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000">
				<tr>
					<td width="688">
					<p align="center"><font face="Arial" size="2">(To be filled 
					by Working Agency)<br>
					Check Points</font></td>
					
				</tr>
					<tr>
					<td width="1260"><font face="Arial" size="2">1. Safe Work 
					Procedure has been made &amp; approved.:<b> @if((@$permit_p->safe_work) == "yes") {{"Yes"}} @endif 
							 @if((@$permit_p->safe_work) == "no") {{"No"}} @endif 
							 @if((@$permit_p->safe_work) == "na") {{"NA"}} </b> @endif</font>
					</td>
				</tr>
				<tr>
					<td width="1260"><font face="Arial" size="2">2. All persons 
					are medically fit and have adequate quality of personal safety 
					appliances. They will use it compulsorily as per requirement of 
					the job. All person will follow safety norms and conditions of 
					Jamipol.:<b>
					@if((@$permit_p->all_person) == "yes") {{"Yes"}} @endif 
					@if((@$permit_p->all_person) == "no") {{"No"}} @endif
					@if((@$permit_p->all_person) == "na") {{"NA"}} @endif </b></font></td>
				</tr>
				<tr>
					<td width="1260"><font face="Arial" size="2">3. Worker working 
					on height must have height pass from NTTF/Competent 
					authority.:<b>
					@if((@$permit_p->worker_working) == "yes") {{"Yes"}} @endif 
					@if((@$permit_p->worker_working) == "no") {{"No"}} @endif
					@if((@$permit_p->worker_working) == "na") {{"NA"}} @endif </b> </font></td>
				</tr>
				<tr>
					<td width="1260"><font face="Arial" size="2">4. All lifting 
					tools/Load bearing tools, tackles &amp; safety appliances are in 
					good condition with valid test certificate.:<b>
					@if((@$permit_p->all_lifting_tools) == "yes") {{"Yes"}} @endif 
					@if((@$permit_p->all_lifting_tools) == "no") {{"No"}} @endif
					@if((@$permit_p->all_lifting_tools) == "na") {{"NA"}} @endif </b></font></td>
				</tr>
				<tr>
					<td width="1260"><font face="Arial" size="2">5. All safety 
					requirement for working at height will be arranged I made and 
					checked before use as per job requirement ~ Access ladder, rest 
					platform, Extended platform, scaffolding, hand rail, fencing of 
					down area / ground area, Use of full body harness etc.:<b>
					@if((@$permit_p->all_safety_requirement) == "yes") {{"Yes"}} @endif 
					@if((@$permit_p->all_safety_requirement) == "no") {{"No"}} @endif
					@if((@$permit_p->all_safety_requirement) == "na") {{"NA"}} @endif</b> </font></td>
				</tr>
				<tr>
					<td width="1260"><font face="Arial" size="2">6. All persons 
					are trained on Safe Working Procedure (SWP):<b>
					@if((@$permit_p->all_person_are_trained) == "yes") {{"Yes"}} @endif 
					@if((@$permit_p->all_person_are_trained) == "no") {{"No"}} @endif
					@if((@$permit_p->all_person_are_trained) == "na") {{"NA"}} @endif </b> </font></td>
				</tr>
				<tr>
					<td width="1260"><font face="Arial" size="2">7. Ensure the 
					applicable activity check list, signed by permit requester, 
					receiver and verified by permit issuer. Checklist shall be 
					attached with permit to work system.:<b>
					@if((@$permit_p->ensure_the_appplicablle) == "yes") {{"Yes"}} @endif
					@if((@$permit_p->ensure_the_appplicablle) == "no") {{"No"}} @endif 
					@if((@$permit_p->ensure_the_appplicablle) == "na") {{"NA"}} @endif</b></font></td>
				</tr>
			</table>
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="4" align="left"><font face="Arial" size="2"><b>Details of 
		working persons</b></font></td>
	</tr>
	<tr>
		<td colspan="4" align="left">
		<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000">
			<tr>
				<td width="360"><font face="Arial" size="2">Name</font></td>
				<td><font face="Arial" size="2">P No./Gate Pass No.</font></td>
				<td><font face="Arial" size="2">Incoming Time</font></td>
				<td><font face="Arial" size="2">Outgoing Time</font></td>
				<td><font face="Arial" size="2">1st Renewal</font></td>
				<td><font face="Arial" size="2">2nd Renewal</font></td>
			</tr>
			@if($gate_pass_details->count() > 0)
					@foreach($gate_pass_details as $key => $value)
			<tr>
				<td width="360"><font face="Arial" size="2">{{$gate_pass_details[$key]->employee_name}}</font></td>
				<td><font face="Arial" size="2">{{$gate_pass_details[$key]->gate_pass_no}}</font></td>
				<td><font face="Arial" size="2">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
				@endforeach
				@endif
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="4" align="left">&nbsp;<p><br>
		<b><font face="Arial" size="2">Sign. of Permit Receiver (Working Agency)</font></b></td>
	</tr>
</table>

<?php // if ($explode[$o] !== "report-view"){ ?>
<font face="Arial" size="2">
	<center><td colspan="4"><p class="no-print"> <input type="button" onclick="window.print()" value="Print"></p>
<?php //} ?>
</body>
</html>
