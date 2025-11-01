<?php
header("Content-type: application/excel");
header("Content-Disposition: attachment; filename=CLMS.xls");
header("Pragma: no-cache");
header("Expires: 0");



$serverName = "216.48.184.92";
$connectionInfo = ["Database" => "jamipol", "UID" => "ptwuser", "PWD" => "ptwuser", "ReturnDatesAsStrings" => true];
$sqlcon = sqlsrv_connect($serverName, $connectionInfo);


date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
// $date = date('Y-m-d H:i:s');

$from = $_REQUEST['from'];
$to = $_REQUEST['to'];


$division_id = $_REQUEST['division'];

if ($from) {
    $from = date('Y-m-d 00:00:00', strtotime($from));
    $to = date('Y-m-d 23:59:59', strtotime($to));
    $date = "date_time BETWEEN '$from' AND '$to'";
} else {
    $from = date('Y-m-d 00:00:00');
    $to = date('Y-m-d 23:59:59');
    $date = "date_time BETWEEN '$from' AND '$to'";
}

$division_id = isset($_GET['division']) ? $_GET['division'] : '';

$vendor_id = isset($_GET['vendor_id']) ? $_GET['vendor_id'] : '';
$a = ""; // base WHERE condition if any

if (!empty($division_id)) {
    // Optional: sanitize input
    $division_id = intval($division_id); // or use mysqli_real_escape_string if needed
    $a .= " AND division = '$division_id'";
}
if (!empty($vendor_id)) {
    // Optional: sanitize input
    $vendor_id = intval($vendor_id); // or use mysqli_real_escape_string if needed
    $a .= " AND created_by = '$vendor_id'";
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





                            $sql = sqlsrv_query($sqlcon, "select * from Clms_gatepass where created_datetime between '$from' AND '$to' $a");
                            while ($row = sqlsrv_fetch_array($sql)) {



                                $created_by = $row['created_by'];
                                $pending_excueting_by = $row['pending_excueting_by'];
                                $vendor = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT name FROM userlogins WHERE id='$created_by'"));
                                $excuting = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT name FROM userlogins WHERE id='$pending_excueting_by'"));

                                ?>
                                <tr>

                                    <td> </td>

                                    <td><?= $row['full_sl']; ?> </td>

                                    <td><?= $vendor['name'] ?> </td>

                                    <td><?= $row['name']; ?> </td>
                                    <td><?= $row['work_order_no']; ?> </td>

                                    <td><?php if ($row['status'] == 'Pending_for_shift_incharge') {
                                        echo "Pending To Shift Incharge";
                                    } elseif ($row['status'] == 'Pending_for_hr') {
                                        echo "Pending To HR";
                                    } elseif ($row['status'] == 'Pending_for_safety') {
                                        echo "Pending To Plant Head";
                                    } elseif ($row['status'] == 'Pending_for_security') {
                                        echo "Gatepass Approved";
                                    } elseif ($row['status'] == 'Rejected') {
                                        echo "Rejected";
                                    } ?></td>

                                    <td><?= $row['son_of']; ?></td>

                                    <td><?= $row['gender']; ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['date_of_birth'])); ?></td>

                                    <td><?= $row['mobile_no']; ?></td>

                                    <td><?= $row['identity_proof']; ?></td>
                                    <td><?= $row['unique_id_no']; ?></td>
                                    <td><?= $row['education']; ?></td>
                                    <td><?= $row['board_name']; ?></td>
                                    <td><?= $row['uan_no']; ?></td>
                                    <td><?= $row['esic']; ?></td>
                                    <td><?= $row['blood_group']; ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['medical_examination_date'])); ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['police_verification_date'])); ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['valid_to'])); ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['valid_till'])); ?></td>
                                    <td><?= $excuting['name']; ?></td>
                                    <td><?= $row['pending_excuting_decision']; ?></td>
                                    <td><?= $row['pending_excuting_remarks']; ?></td>
                                    <td><?= date('d-m-Y H:i:s', strtotime($row['pending_eccuting_date'])); ?></td>

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