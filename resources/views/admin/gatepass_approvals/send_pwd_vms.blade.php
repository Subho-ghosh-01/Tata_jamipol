

Hi {{$data['name']}},<br><br>
Pending For Approval  :<br>
Sl No: {{$data['sl']}}<br>
Visitor Name: {{$data['visitor_name']}},<br>
Visitor Mobile No:{{$data['visitor_mobile_no']}}<br>
Visitor Company :{{$data['visitor_company']}}<br>
From Date :{{$data['From_date']}}<br>
To Date :{{$data['To_date']}}<br>
From Time :{{$data['from_time']}}<br>
To Time :{{$data['to_time']}}<br>
<!--id :{{$data['id']}}<br>-->
<!--<a href="{{route('admin.edit.edit',\Crypt::encrypt($data['id']))}}">Click here to Approve<br></a>-->

<a  href="https://wps.jamipol.com/api_ap.php?token=123456&api=vms_approve&id={{$data['id']}}">Approve<br></a>
<br>
<a href="https://wps.jamipol.com/api_ap.php?token=123456&api=vms_reject&id={{$data['id']}}">Reject<br></a>
<br><br>
Thank You,<br>
JAMIPOL
<hr>
This is an Automatic Generated Email Do Not Reply.<br>
Powered by Anmoul Infomatics Pvt. Ltd.
<br><br>

 


