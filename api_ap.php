<?php
error_reporting(0);
date_default_timezone_set('Asia/Kolkata');
$serverName     = "localhost";
$connectionInfo = ["Database" => "jamipol", "UID" => "ptwuser", "PWD" => "ptwuser","ReturnDatesAsStrings"=>true];
$sqlcon = sqlsrv_connect($serverName, $connectionInfo);

$token = $_REQUEST["token"];
$match_token = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT id,token FROM tokens WHERE token='$token'"));
//echo "SELECT id,token FROM tokens WHERE token='$token'";
//exit;
//echo $match_token['token'];
//exit;
if (!empty($match_token['id'])) {
	 $api =  $_REQUEST["api"];
	
	if($api == "vms_approve"){
		
			$id = $_REQUEST['id'];
			
			
			date_default_timezone_set("Asia/Calcutta");
           $currentTime =  date('Y-m-d H:i:s'); 
			 	
		  
			$response = array();
			
	
//$lastid=mysqli_insert_id($conn);

$approver='approve';
 $sql04x5 = "UPDATE visitor_gate_pass SET approver_decision='$approver',approver_remarks='Ok',status='issued',approver_datetime='$currentTime' WHERE id='$id'";
//echo $sql04x5;

 $result04x5 = sqlsrv_query($sqlcon,$sql04x5);


	 if ($result04x5)
	  {
	 ?>
   <script>alert("Visitor Gate Pass has been approved successfully");window.close();</script>
   <?php
		 $response ['Decision']= "Approved" ;
	  }
	  else{
		   $response ['status']= "Not ok";
	  }
	
		echo json_encode($response);

		}
		if($api == "vms_reject"){
		
			$id = $_REQUEST['id'];
			
			
			date_default_timezone_set("Asia/Calcutta");
           $currentTime =  date('Y-m-d H:i:s'); 
			 	
		  
			$response = array();
			
	
//$lastid=mysqli_insert_id($conn);

$approver='reject';
 $sql04x5 = "UPDATE visitor_gate_pass SET approver_decision='$approver',approver_remarks='Not Ok',status='Rejected',approver_datetime='$currentTime' WHERE id='$id'";
//echo $sql04x5;

 $result04x5 = sqlsrv_query($sqlcon,$sql04x5);


	 if ($result04x5)
	  {
		  ?>
   <script>alert("Visitor Gate Pass has been Rejected successfully");window.close();</script>
   <?php
		 $response ['Decision']= "Rejected" ;
	  }
	  else{
		   $response ['status']= "Not ok";
	  }
	
		echo json_encode($response);

		}
	
	}
 else {
    echo "Invalid Token";
}

?>