<?php
// header("Content-type: application/excel");
 //header("Content-Disposition: attachment; filename=complaint_All.xls");
 //header("Pragma: no-cache");
// header("Expires: 0");
session_start();
$userType=$_SESSION['userType'];

$serverName     = "103.86.176.182";
$connectionInfo = ["Database" => "jamipol", "UID" => "jamipol", "PWD" => "6Grbi5%3","ReturnDatesAsStrings"=>true];

$myid = $_SESSION['userid'];
date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
// $date = date('Y-m-d H:i:s');

$from = $_REQUEST['from'];
$to   = $_REQUEST['to'];
$division_id=$_REQUEST['division_id'];
if ($from) {
    $from  = date('Y-m-d 00:00:00', strtotime($from));
    $to    = date('Y-m-d 23:59:59', strtotime($to));
    $date = "date_time BETWEEN '$from' AND '$to'";
} else {
    $from  = date('Y-m-d 00:00:00');
    $to    = date('Y-m-d 23:59:59');
    $date = "date_time BETWEEN '$from' AND '$to'";
}
if($division_id!=''){
    $a .= "AND division='$division_id'";
}
  

?>

<head>
    <!-- <style> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous"> -->
    <!-- </style> -->
</head>
<div id="content" class="">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title" style="float:left">
                    </div>
                </div>
				
                <div class="panel-body">
                    <?php
					
                    echo "From Date : $from | To Date : $to ";
					  
					 
                            
			    	   ?>
					 
                   				  
					
                    <table border="1" id="" class="">
                                <thead>
                                    <tr>
                                    <th>#</th>
                            <th>Sl No</th>
                           <th>Vendor Name</th>
                             <th>Name</th>
                            <th>Work Order No</th>
                           <th>Status</th>
                           <th>Son/Daughter/Wife of</th>
                           <th>Gender</th>
                           <th>Date of Birth</th>
                           <th>Mobile Number</th>
                           <th>Identity Proof</th>
                           <th>Identity Proof No</th>
                           <th>Education</th>
                           <th>Board Name</th>
                           <th>UAN / PF</th>
                           <th>ESIC</th>
                           <th>Blood Group</th>
                           <th>Medical Examination Date</th>
                           <th>Police Verification Date</th>
                           <th>Valid From</th>
                           <th>Upto</th>
                           <th>Executing Agency By</th>
                           <th>Executing Agency Decision</th>
                           <th>Executing Agency Remarks</th>
                           <th>Executing Agency Remarks Datetime</th>
                           </tr>
						  </thead>
							
                          <tbody>
							
							
								<?php
								
											   
								$count++;
								$sql=sqlsrv_query($conn,"select * from Clms_gatepass where created_datetime < '$from' AND created_datetime > '$to' $a");
                           while($row=sqlsrv_fetch_array($sql))
							   {
                                $created_by = $row['created_by'];
                                $pending_excueting_by = $row['pending_excueting_by'];
                        $vendor = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT name FROM userlogins WHERE id='$created_by'"));
                        $excuting = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT name FROM userlogins WHERE id='$pending_excueting_by'"));
		                 	 ?>
							<tr>
							<td> <?=$count++;?></td>
							<td><?= $row['full_sl']; ?> </td>
	                       	<td><?=$vendor['name']?> </td>
                            <td><?= $row['name']; ?> </td>
                            <td><?= $row['work_order_no']; ?> </td>
                            <td><?php if($row['status']=='Pending_for_shift_incharge'){
                                echo "Pending To Shift Incharge";
                            }elseif($row['status']=='Pending_for_hr'){
                                echo "Pending To HR";
                            }elseif($row['status']=='Pending_for_safety'){
                                echo "Pending To Plant Head";
                            }elseif($row['status']=='Pending_for_security'){
                                echo "Gatepass Approved";
                            }elseif($row['status']=='Rejected'){
                                echo "Rejected";
                            }?></td>
	                        <td><?= $row['son_of']; ?></td>
                            <td><?= $row['gender']; ?></td>
                            <td><?= date('d-m-Y',$row['date_of_birth']); ?></td>
                            <td><?= $row['mobile_no']; ?></td>
                            <td><?= $row['identity_proof']; ?></td>
                            <td><?= $row['unique_id_no']; ?></td>
                            <td><?= $row['education']; ?></td>
                            <td><?= $row['board_name']; ?></td>
                            <td><?= $row['uan_no']; ?></td>
                            <td><?= $row['esic']; ?></td>
                            <td><?= $row['blood_group']; ?></td>
                            <td><?= date('d-m-Y',$row['medical_examination_date']); ?></td>
                            <td><?= date('d-m-Y',$row['police_verification_date']); ?></td>
                            <td><?= date('d-m-Y',$row['valid_to']); ?></td>
                            <td><?= date('d-m-Y',$row['valid_till']); ?></td>
                            <td><?= $excuting['name']; ?></td>
                            <td><?= $row['pending_excuting_decision']; ?></td>
                            <td><?= $row['pending_excuting_remarks']; ?></td>
                            <td><?= date('d-m-Y H:i:s',$row['pending_eccuting_date']); ?></td>
							</tr>
							 	<?php 
						        }
								?>
								
					
                               
							   
							    </tbody>
			      
                            </table>
								
                                    
                                   
                                    
                                     
                                 
                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
