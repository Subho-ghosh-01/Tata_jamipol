<?php 
$serverName     = "103.86.176.182";
$connectionInfo = ["Database" => "jamipol", "UID" => "jamipol", "PWD" => "6Grbi5%3","ReturnDatesAsStrings"=>true];
$sqlcon = sqlsrv_connect($serverName, $connectionInfo);
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');

$fetch_query  = sqlsrv_query($sqlcon, "SELECT * FROM safety_data_entry Where draft_till_date < $date");
while ($getlist = sqlsrv_fetch_array($fetch_query)) {
   $update = sqlsrv_query($sqlcon, "UPDATE safety_data_entry SET draft='No' Where id='".$getlist['id']."'");
}
?>