@if($data['condition'] == "RequesterSendEmail")
	Dear {{@$data['issuer_name']}},<br><br>
	Permit Number :{{@$data['serial_no']}} <br>
	Order Number:{{@$data['ordernumber']}}<br>
	Start Date: {{@$data['start']}}<br>
	End Date:   {{@$data['end']}}<br>
	Job Category : {{@$data['jobcategory']}}<br>
	Working Agency Name:{{@$data['working_agency']}}({{@$data['working_vendor_code']}})<br>
	Status: {{'Pending with Executing Agency'}}({{@$data['issuer_name']}})<br><br>
	<a href="https://wps.jamipol.com">Click Here to Approve</a> 
@elseif($data['condition'] == "AreaSendEmail")
	Dear {{@$data['issuer_name']}},<br><br>
	Permit Number :{{@$data['serial_no']}} <br>
	Order Number:{{@$data['ordernumber']}}<br>
	Start Date: {{@$data['start']}}<br>
	End Date:   {{@$data['end']}}<br>
	Job Category : {{@$data['jobcategory']}}<br>
	Working Agency Name:{{@$data['working_agency']}}({{@$data['working_vendor_code']}})<br>
	Status: {{'Pending with Owner Agency'}}({{@$data['issuer_name']}})<br><br>
	<a href="https://wps.jamipol.com">Click Here to Approve</a> 
@elseif($data['condition'] == "IssuedSendEmail")
	Dear {{@$data['working_agency']}},<br><br>
	Permit Number :{{@$data['serial_no']}} <br>
	Order Number:{{@$data['ordernumber']}}<br>
	Start Date: {{@$data['start']}}<br>
	End Date:   {{@$data['end']}}<br>
	Job Category : {{@$data['jobcategory']}}<br>
	Working Agency Name:{{@$data['working_agency']}}({{@$data['working_vendor_code']}})<br>
	Status: {{'Permit Issued'}}<br><br>
	<a href="https://wps.jamipol.com">Click Here to Download</a> 
@endif


 


