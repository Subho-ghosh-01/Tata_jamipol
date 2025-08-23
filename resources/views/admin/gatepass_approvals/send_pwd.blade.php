Hi {{$data['name']}},<br><br>
Pending For Approval  :<br>
Sl No: {{$data['full_sl']}}<br>
Vendor Name: {{$data['vendor']}},<br>
Work Order No:{{$data['workorder']}}<br>
<a href="{{route('admin.edit_clms.edit',\Crypt::encrypt($data['id']))}}">Click here to Approve<br></a>
<br><br>
Thank You,<br>
JAMIPOL
<hr>
This is an Automatic Generated Email Do Not Reply.<br>
Powered by Anmoul Infomatics Pvt. Ltd.
<br><br>

 


