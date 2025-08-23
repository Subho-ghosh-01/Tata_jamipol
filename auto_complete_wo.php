<?php
error_reporting(0);
$serverName     = "103.86.176.182";
$connectionInfo = ["Database" => "jamipol", "UID" => "jamipol", "PWD" => "6Grbi5%3","ReturnDatesAsStrings"=>true];
$sqlcon = sqlsrv_connect($serverName, $connectionInfo);
$searchTerm = $_GET['search'];
$departments = sqlsrv_query($sqlcon,"SELECT order_code FROM work_order WHERE order_code LIKE '%$searchTerm%'");

//echo  "SELECT order_code FROM work_order WHERE order_code LIKE '%'.$searchTerm.'%'";
           
            $getDepartment = array();

            while($row1=sqlsrv_fetch_array($departments))
            {
                $data['order_code']=$row1['order_code'];
                $getDepartment[]=$data;
            }

            $data2['list']=$getDepartment;
             echo json_encode($data2);

?>