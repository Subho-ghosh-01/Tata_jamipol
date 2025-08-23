<?php
error_reporting(0);
$token  = $_REQUEST["token"];
$serverName     = "199.79.62.22";
$connectionInfo = ["Database" => "wpsjamipoldb", "UID" => "wpsjamipolusr", "PWD" => "Lu~9qe06","ReturnDatesAsStrings"=>true];
$sqlcon = sqlsrv_connect($serverName, $connectionInfo);
$tokenMatchQuery = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT id,token FROM tokens WHERE token='$token'"));
if ($tokenMatchQuery['token']) {
    $api = $_REQUEST["api"];
    switch ($api) {
        case 'login':
            $username =  $_REQUEST["username"];
            $password =  $_REQUEST["password"];

            $password = md5($password);
            $userlogin = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT id,name,
                user_type,division_id,department_id,user_sub_type 
                FROM userlogins WHERE vendor_code='$username' AND password='$password'"));
            $found     = $userlogin['id'];

            if ($found) { 
                $message['response'] = "Login Successfully";
                $message['is_logged_in'] = true;
                $message['id']   = $userlogin['id'];
                $message['name'] = $userlogin['name'];
                $message['user_type'] = $userlogin['user_type'];
                $message['user_sub_type'] = $userlogin['user_sub_type'];
                if($userlogin['user_type'] == 1 && $userlogin['user_sub_type'] == 3){
                    $message['is_report'] = "Y";
                }
                elseif($userlogin['user_type'] == 1 && $userlogin['user_sub_type'] == 1){
                    $message['is_report'] = "Y";
                }
                else{
                    $message['is_report'] = "N";
                }
                $message['division_id'] = $userlogin['division_id'];

                echo json_encode($message); 
            } else {
                $message['response'] = "Incorrect Password";
                $message['is_logged_in'] = false;
                echo json_encode($message); 
            }
            break;
        case 'dashboard-stacks':
            $user_id = $_REQUEST["my-id"];
            $date = date('Y-m-d H:i:s');
            $toReturn =  array();
            $users = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT id FROM userlogins WHERE id='$user_id'"));
            
            if($users['id']){
            // My permits Tab
                $My_permits = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT COUNT(id) as mycount FROM permits WHERE entered_by='$user_id'"));
                $toReturn['my-permit-count'] = $My_permits['mycount'];


            // Permit for Approval Tab
                $approve = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT COUNT(id) as pending_approve
                                FROM permits
                                WHERE issuer_id='$user_id' AND status='Requested' 
                                OR area_clearence_id='$user_id' AND status='Parea'"));
                                // OR ppc_userid='$user_id' AND status='PPc'
                $toReturn['permit-approve'] = $approve['pending_approve'];


            // Issued permit Tab
                $Issued = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT COUNT(id) as issued_permit
                    FROM permits 
                        WHERE issuer_id='$user_id' AND status='Issued'
                        OR area_clearence_id ='$user_id' AND status ='Issued'"));
                $toReturn['my-issued-permit'] = $Issued['issued_permit'];
                

            // Return permit Tab 
                $returnPermit = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT COUNT(id) as return_count  FROM permits 
                    WHERE issuer_id='$user_id' AND status='Issued' AND return_status='Pending' 
                    OR return_status='Power_Getting' AND ppg_userid='$user_id'
                    OR return_status='Pending_area' AND area_clearence_id='$user_id'"));
                $toReturn['my-return-pending'] = $returnPermit['return_count'];


            // Renew permit Tab
                $renewPermit = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT COUNT(id) as renew_permit
                        FROM renew_permit 
                    WHERE (issuer_id='$user_id' AND status='Pending_Renew_Issuer')
                        OR (area_id='$user_id' AND status='Pending_Renew_Area')"));
                $toReturn['my-renew-pending'] = $renewPermit['renew_permit'];


            // Exipred permit Tab
                $expriedCount = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT COUNT(id) as expried_count
                        FROM permits 
                    WHERE issuer_id='$user_id' AND status='Issued' AND end_date < '$date'"));
                $toReturn['my-expired'] = $expriedCount['expried_count'];

            // my-power-cutting
                $powerCuttings = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT COUNT(id) as power_count FROM permits 
                    WHERE ppc_userid='$user_id' AND status='PPc'"));
                $toReturn['my-power-cutting'] = $powerCuttings['power_count'];

            // my-power-getting
                $powerGettings = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT COUNT(id) as power_count FROM permits 
                    WHERE ppg_userid='$user_id' AND return_status='PPg'"));
                $toReturn['my-power-getting'] = $powerGettings['power_count'];

            }
            else{
                $toReturn['error'] ="User not found";
            }
            echo json_encode($toReturn);
            break;
        // ----------------------------------------- Create Permits Palying IN TO ROLE---------------------------------------
        // Get All Division List
        case 'divisions':
            $user_type   = $_REQUEST['user_type'];
            $division_id = $_REQUEST['division_id'];

            if($user_type == 1){
                $query = "SELECT id,name FROM divisions"; 
            }
            elseif($user_type == 2){
                $query = "SELECT id,name FROM divisions WHERE id='$division_id'"; 
            }

            $fetch_query = sqlsrv_query($sqlcon, $query);
            $takeAll=array();
            $toReturn= array();

            while($row=sqlsrv_fetch_array($fetch_query))
            {
                $takeAll['id']=$row['id'];
                $takeAll['division_name']=$row['name'];
                $toReturn[] = $takeAll;
            }
            echo json_encode($toReturn);
            break;

        // Get Department Cum Executing A/c to Division id
        case 'department-executing':
            $division = $_REQUEST['division-id'];
            $my_id    = $_REQUEST['my-id'];

            $departments = sqlsrv_query($sqlcon,"SELECT id,department_name FROM departments WHERE division_id='$division'"); 
            $executing   = sqlsrv_query($sqlcon,"SELECT id,name FROM userlogins WHERE division_id='$division' AND user_type='1' AND id !='$my_id'"); 
            $toReturn = array();
            $getDepartment = array();
            $getExecuting = array();

            while($row1=sqlsrv_fetch_array($departments))
            {
                $getDepartment['id']=$row1['id'];
                $getDepartment['department']=$row1['department_name'];
                $toReturn['department_key'][] = $getDepartment;
            }

            while($row2=sqlsrv_fetch_array($executing))
            {
                $getExecuting['id']=$row2['id'];
                $getExecuting['executing_name']=$row2['name'];
                $toReturn['excuting_key'][] = $getExecuting;

            }
            echo json_encode($toReturn);
            break;
        
        //Get the Job Category A/c To division and department
        case 'job-category':
            $division   = $_REQUEST["division-id"];
            $department = $_REQUEST["department-id"];
            $fetchJobs = sqlsrv_query($sqlcon, "SELECT jobs.id,jobs.job_title 
                                        FROM jobs 
                                    LEFT JOIN jobs_linking ON jobs_linking.job_id   = jobs.id
                                    WHERE jobs_linking.division_id ='$division' AND jobs_linking.department_id='$department'");
            $getJobs = array();
            $toReturn = array();

            while($row = sqlsrv_fetch_array($fetchJobs))
            {
                $getJobs['id']=$row['id'];
                $getJobs['job_title']=$row['job_title'];
                $toReturn[] =  $getJobs;
        
            }
            echo json_encode($toReturn);
            break;  

        case 'swp-number':
            $id = $_REQUEST["job-id"];
            $query = "SELECT jobs.id,jobs.swp_number,swp__files.id,swp__files.swp_file 
                        FROM jobs,swp__files WHERE jobs.id='$id' AND jobs.id=swp__files.job_id"; 
            $fetch_query  = sqlsrv_query($sqlcon, $query);
            $getall = array();
            $toReturn = array();

            while($row=sqlsrv_fetch_array($fetch_query))
            {
                $getall['swp_number'] = $row['swp_number'];
                $getall['swp_file']   = $row['swp_file'];
                $toReturn[]  = $getall;

            }
            echo json_encode($toReturn);
            break; 

        case 'order-validity':
            $order_number = $_REQUEST["order-number"];
            $type         = $_REQUEST["user-type"];

            if($type == 1){
                $demo = "order_code='$order_number'";
            }
            else if($type == 2){
                $vendorCode = $_REQUEST["vendor-code"];
                $demo = "vendor_code ='$vendorCode' AND order_code='$order_number'";
            }
            $query = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT order_validity FROM work_order WHERE $demo"));
            echo json_encode($query['order_validity']);
            break; 
        case 'gate-pass-details':
            $user_type     = $_REQUEST['user_type'];
            $user_sub_type = $_REQUEST['user_sub_type'];
            $entername     = $_REQUEST['name'];
            $division_id   = $_REQUEST['division_id'];
            $my_id         = $_REQUEST['my-id'];

            $toReturn = array();
            $data     = array();

            if($user_type == 1 && $user_sub_type == 3){
                $fetch_query  = sqlsrv_query($sqlcon, "SELECT * FROM
                    userlogins_employee_details WHERE employee LIKE '%$entername%'");
                while ($getlist = sqlsrv_fetch_array($fetch_query)) {
                    $AsTemp['employee']    = $getlist['employee'];
                    $AsTemp['gatepass']    = $getlist['gatepass'];
                    $AsTemp['designation'] = $getlist['designation'];
                    $AsTemp['age']         = $getlist['age'];
                    $AsTemp['expiry']      = $getlist['expiry'];
                    $data[]  = $AsTemp;
                }
            }
            elseif ($user_type == 1 && $user_sub_type == 1) {
                $vendor  = sqlsrv_query($sqlcon, "SELECT * FROM userlogins 
                    WHERE user_type='2' AND division_id ='$division_id'");
                    while ($vendors =  sqlsrv_fetch_array($vendor)) {
                        $vendorArray[] =  $vendors['id'];
                    }
                    
               
                $fetch_query  = sqlsrv_query($sqlcon, "SELECT * FROM
                    userlogins_employee_details WHERE employee LIKE '%$entername%'
                    AND userlogins_id IN(".implode(',',$vendorArray).")");
                    while ($getlist = sqlsrv_fetch_array($fetch_query)) {
                        $AsTemp['employee']    = $getlist['employee'];
                        $AsTemp['gatepass']    = $getlist['gatepass'];
                        $AsTemp['designation'] = $getlist['designation'];
                        $AsTemp['age']         = $getlist['age'];
                        $AsTemp['expiry']      = $getlist['expiry'];
                        $data[]  = $AsTemp;
                    }
            }
            elseif ($user_type == 2 && $user_sub_type == 2) {
                $fetch_query  = sqlsrv_query($sqlcon, "SELECT * FROM
                    userlogins_employee_details WHERE employee LIKE '%$entername%'
                    AND userlogins_id='$my_id'");
                    while ($getlist = sqlsrv_fetch_array($fetch_query)) {
                        $AsTemp['employee']    = $getlist['employee'];
                        $AsTemp['gatepass']    = $getlist['gatepass'];
                        $AsTemp['designation'] = $getlist['designation'];
                        $AsTemp['age']         = $getlist['age'];
                        $AsTemp['expiry']      = $getlist['expiry'];
                        $data[]  = $AsTemp;
                    }
            }
            $toReturn['list'] = $data;
            echo  json_encode($toReturn);
        break;


        case 'six-directions':
            $id         = $_REQUEST["job-id"];
            $direction  = $_REQUEST["direction"]; 
            $getSixDirection =  sqlsrv_query($sqlcon, "SELECT hazarde,precaution
                    FROM hazardes WHERE job_id='$id' AND direction='$direction'"); 

            $getAllHazard = array();
            $toReturn = array();

            while($row=sqlsrv_fetch_array($getSixDirection))
            {
                $getAllHazard['hazarde']=$row['hazarde'];
                $getAllHazard['precaution']=$row['precaution'];
                $toReturn[] = $getAllHazard;
            }
            echo json_encode($toReturn);
            break; 

        case 'my-permits':
            $id = $_REQUEST["my-id"];
            $start = $_REQUEST["start"];
            $end   = $_REQUEST["end"];
            if($start == "" && $end ==""){
                $start= 1;
                $end =  9999;
            }
            
            $query ="SELECT * FROM (
                SELECT permits.id as permitid,
                    permits.serial_no,permits.division_id,permits.created_at,
                    permits.order_no,permits.status,permits.return_status,
                    userlogins.id as userid,userlogins.name,divisions.abbreviation,jobs.job_title,
                    permits.area_clearence_id,permits.issuer_id,permits.entered_by,permits.ppc_userid,
                    permits.renew_id_1,permits.renew_id_2, ROW_NUMBER() OVER (ORDER BY permits.id DESC) as row 

                FROM permits 
                    INNER JOIN userlogins ON permits.issuer_id   = userlogins.id
                    INNER JOIN divisions  ON permits.division_id = divisions.id
                    INNER JOIN jobs  ON permits.job_id = jobs.id
                    WHERE permits.entered_by='$id'
            ) a WHERE a.row >= '$start' and a.row <= '$end'";
            

            $fetch_query = sqlsrv_query($sqlcon, $query);
            $myPermits = array();
            $toReturn  = array();
            while($row = sqlsrv_fetch_array($fetch_query))
            {  

                $toReturn['create_date'] = $row['created_at'];
                $toReturn['permitid']    = $row['permitid'];
                $toReturn['order_no']    = $row['order_no'];
                $toReturn['job_title']   = $row['job_title'];
                $toReturn['return_status']   = $row['return_status'];

                $month                      = date('m-Y', strtotime($row['created_at']));
                $toReturn['permit_sl']      = $row['abbreviation'].'/'.$month.'/'.$row['serial_no'];


                switch ($row['status']) {
                    case "Requested":
                        $areaby = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$row['issuer_id']."'"));
                        $toReturn['status2']="Pending with Executing Agency (". $areaby['name'] .")";
                        break;
                    case "Returned":
                        $toReturn['status2']="Permit Returned";   
                        break;

                    case "PPc":
                        $ppc = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$row['ppc_userid']."'"));
                        $toReturn['status2']="Permit Pending at Power Cutting(". $ppc['name'] .")";
                        break;

                    case "Parea":
                        $areaby = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$row['area_clearence_id']."'"));
                        $toReturn['status2']="Pending with Owner Agency (". $areaby['name'] .")";
                        break;
                    
                    case "Issued":
                        switch ($row['return_status']) {
                            case 'PPg':
                                $ppguser  = "/Return Pending at Power Getting User";
                                break;
                            case 'Pending':
                                $ppguser  = "/Return Pending at Executing Agency";
                                break;
                            case 'Pending_area':
                                $ppguser  = "/Return Pending at Owner Agency";
                                break;
                        }
                        $toReturn['status2'] ="Issued". $ppguser;
                        break;
                }

                // Showing of the download button
                switch ($row['status']) {
                    case "Issued":
                        $toReturn['showdownloadbtn'] = "Yes";
                        $toReturn['linktodownload'] = "admin/permit/text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla/".base64_encode($row['permitid']);
                        break;
                    case "Returned":
                        $toReturn['showdownloadbtn'] = "Yes";
                        $toReturn['linktodownload'] = "admin/permit/text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla/".base64_encode($row['permitid']);
                        break;
                    default:
                        $toReturn['showdownloadbtn']  = "No";
                        $toReturn['linktodownload'] = "";
                }

                // Showing of the Return button
                if($row['status']  == "Issued" || $row['status']  == "Returned"){
                    switch ($row['return_status']) {
                        case 'PPg':
                            $toReturn['returnBtn'] = "No";
                            break;
                        case 'Pending':
                            $toReturn['returnBtn'] = "No";
                            break;
                        case 'Pending_area':
                            $toReturn['returnBtn'] = "No";
                            break;
                        case 'Returned':
                            $toReturn['returnBtn'] = "No";
                            break;
                        default:    
                            $toReturn['returnBtn'] = "Yes";
                        break;
                    }
                }

                if($row['status'] == "Issued"){
                    if($row['renew_id_1'] == "" && $row['renew_id_2']  == ""){
                        $toReturn['renewBtn']  = "Yes"; 
                    }
                    elseif($row['renew_id_1'] != "" && $row['renew_id_2']  == ""){
                        $toReturn['renewBtn']  = "Yes"; 
                    }
                    else{
                        $toReturn['renewBtn']  = "No"; 
                    }
                }else{
                    $toReturn['renewBtn']  = "No"; 
                }
            
                $myPermits[]=$toReturn;
            }
            echo json_encode($myPermits);
            break; 

        case 'permits-for-approval':
            $myid = $_REQUEST["my-id"];
            $getallforApprove = "SELECT permits.id as permitid,
                    permits.serial_no,permits.division_id,permits.created_at,permits.order_no,
                    permits.status,userlogins.id as userid,userlogins.vendor_code,userlogins.name,
                    userlogins.user_type,divisions.abbreviation,permits.area_clearence_id,jobs.job_title
                    FROM permits 
                        INNER JOIN userlogins ON permits.entered_by  = userlogins.id
                        INNER JOIN divisions  ON permits.division_id = divisions.id
                        INNER JOIN jobs  ON permits.job_id = jobs.id
                    WHERE  permits.issuer_id='$myid' AND status='Requested'
                        OR permits.area_clearence_id='$myid' AND status='Parea' 
                        ORDER BY permits.id DESC";
                    
            $fetch_query = sqlsrv_query($sqlcon, $getallforApprove);
            $approvalPermit=array();
            $toReturn = array();
                    
            while($row=sqlsrv_fetch_array($fetch_query))
            {
                $toReturn['permitid']     =  $row['permitid'];
                $toReturn['create_date']  =  $row['created_at'];
                $toReturn['name']         =  $row['name'];
                $toReturn['vendor_code']  =  $row['vendor_code'];
                $toReturn['jobtitle']     =  $row['job_title'];

                if($row['user_type'] == 2){
                    $toReturn['role']  = "Vendor";
                }
                else{
                    $toReturn['role']  = "Employee";
                }

                $month = date('m-Y', strtotime($row['created_at']));
                $toReturn['permit_sl'] = $row['abbreviation'].'/'.$month.'/'.$row['serial_no'];

                switch ($row['status']) {
                    case "Parea":
                        $areaby = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$row['area_clearence_id']."'"));
                        $toReturn['status2']="Pending with Owner Agency (". $areaby['name'] .")";
                        break;
                    default:
                        $toReturn['status2']="Requested";
                        break;
                }

                switch ($row['status']) {
                    case "Parea":
                        $toReturn['issueBtn']= "Yes";
                        break;
                    case "Requested":
                        $toReturn['issueBtn']= "Yes";
                        break;
                    default:
                        $toReturn['issueBtn']="No";
                        break;
                }
                $approvalPermit[]=$toReturn;
            }
            echo json_encode($approvalPermit);            
            break;

        case 'issued-permit-list':
            $myid = $_REQUEST["my-id"];
            $users = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT id FROM userlogins WHERE id='$myid'"));

            $start = $_REQUEST["start"];
            $end   = $_REQUEST["end"];
            if($start == "" && $end == ""){
                $start= 1;
                $end =  9999;
            }
            if($users['id']){
                $issuedPermits ="SELECT * FROM (
                        SELECT permits.id as permitid,
                            permits.serial_no,permits.division_id,permits.created_at,permits.order_no, 
                            permits.status,permits.issuer_id,userlogins.id as userid,userlogins.vendor_code,
                            userlogins.name,userlogins.user_type,divisions.abbreviation,jobs.job_title,
                            permits.renew_id_1,permits.renew_id_2, ROW_NUMBER() OVER (ORDER BY permits.id DESC) as row
                    FROM permits 
                        INNER JOIN userlogins ON permits.entered_by  = userlogins.id
                        INNER JOIN divisions  ON permits.division_id = divisions.id
                        INNER JOIN jobs  ON permits.job_id = jobs.id
                    WHERE  permits.issuer_id='$myid' AND permits.status='Issued' OR permits.status='Returned'
                        OR area_clearence_id='$myid' AND permits.status='Issued'
                ) a WHERE a.row >= '$start' and a.row <= '$end'";
            }
            // echo $issuedPermits;
            // exit;

            $fetch_query = sqlsrv_query($sqlcon, $issuedPermits);
            $issuedPermit=array();
            $toReturn =  array();

            while($row=sqlsrv_fetch_array($fetch_query))
            {
                $toReturn['permitid']       =    $row['permitid'];
                $toReturn['create_date']    =    $row['created_at'];

                $month = date('m-Y', strtotime($row['created_at']));
                $toReturn['permit_sl'] =  $row['abbreviation'].'/'.$month.'/'.$row['serial_no']; 

                $toReturn['name']           =    $row['name'];
                $toReturn['vendor_code']    =    $row['vendor_code'];
                if($row['user_type'] == 2){
                    $toReturn['user_type']    = "Vendor";
                }else{
                    $toReturn['user_type']    = "Employee";
                }
                $toReturn['job_title']    =    $row['job_title'];

                switch ($row['status']) {
                    case "Issued":
                        $toReturn['status2']="Permit Issued";
                        $toReturn['downloadBtn']  = "Yes";
                        $toReturn['linktodownload'] = "admin/permit/text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla/".base64_encode($row['permitid']);

                        break;
                    case "Returned":
                        $toReturn['status2']="Permit Returned";
                        $toReturn['downloadBtn']  = "Yes";
                        $toReturn['linktodownload'] = "admin/permit/text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla/".base64_encode($row['permitid']);

                        break;
                }
               
                if($row['status'] == "Issued"){
                    $toReturn['cancelBtn']  = "Yes";
                }else{
                    $toReturn['cancelBtn']  = "No";
                }
                
                $issuedPermit[]=$toReturn;
            }
            echo json_encode($issuedPermit);            
            break;

        case 'pending-for-return':
            $user_id = $_REQUEST["my-id"];
            $query_count = "SELECT permits.id as permitid,
                        permits.serial_no,permits.division_id,permits.created_at,permits.order_no, 
                        permits.status,permits.return_status,userlogins.id as userid,
                        userlogins.vendor_code,userlogins.name,userlogins.user_type,
                        divisions.abbreviation
                FROM permits 
                    INNER JOIN userlogins ON permits.entered_by  = userlogins.id
                    INNER JOIN divisions  ON permits.division_id = divisions.id
                 WHERE permits.issuer_id ='$user_id' AND permits.status = 'Issued' AND permits.return_status ='Pending' 
                    OR permits.area_clearence_id ='$user_id' AND permits.status = 'Issued' AND permits.return_status='Pending_area' 
                    ORDER BY permits.id DESC";
                        
            $fetch_query = sqlsrv_query($sqlcon, $query_count);
            $pendingReturnPermit=array();
            $toReturn = array();
            while($row=sqlsrv_fetch_array($fetch_query))
            {
                $toReturn['permitid'] = $row['permitid'];
                $toReturn['create_date'] = $row['created_at'];
                $month = date('m-Y', strtotime($row['created_at']));
                $toReturn['permit_sl']=$row['abbreviation'].'/'.$month.'/'.$row['serial_no']; 
                $toReturn['vendor_code'] = $row['vendor_code'];
                $toReturn['name'] = $row['name'];

                if($row['return_status'] == "Pending")
                {
                    $toReturn['status2']="Issued/Return Pending at Executing Agency";
                }
                elseif($row['return_status'] == "Pending_area")
                {
                    $toReturn['status2']= "Issued/Return Pending at Owner Agency";
                }
                elseif($row['return_status'] == "PPg")
                {
                    $toReturn['status2']= "Issued/Return Pending at Executing Agency";
                }

                if($row['return_status'] == "Pending")
                {
                    $toReturn['approveBtn']="Yes";
                }
                elseif($row['return_status'] == "Pending_area")
                {
                    $toReturn['approveBtn']= "Yes";
                } 
                elseif($row['return_status'] == "PPg")
                {
                    $toReturn['approveBtn']= "Yes";
                }
                
                $pendingReturnPermit[]=$toReturn;
            }
            echo json_encode($pendingReturnPermit);
            break;

        case 'pending-for-renew':
            $user_id = $_REQUEST["my-id"];
            $query_count = "SELECT permits.id as permitid,permits.serial_no,permits.division_id,
                    renew_permit.datetime_apply,renew_permit.status,userlogins.id as userid,
                    userlogins.vendor_code,userlogins.name,userlogins.user_type,
                    divisions.abbreviation,renew_permit.id as renewid,permits.created_at
                        FROM permits 
                            INNER JOIN userlogins ON permits.entered_by  = userlogins.id
                            INNER JOIN divisions  ON permits.division_id = divisions.id
                            INNER JOIN renew_permit  ON permits.id = renew_permit.permit_id
                        WHERE renew_permit.issuer_id='$user_id' AND renew_permit.status='Pending_Renew_Issuer'
                            OR renew_permit.area_id='$user_id' AND renew_permit.status='Pending_Renew_Area' 
                            ORDER BY renew_permit.id DESC";

            // echo $query_count;
            $fetch_query = sqlsrv_query($sqlcon, $query_count);
            $pendingRenewalPermit=array();
            $toReturn = array();
            while($row=sqlsrv_fetch_array($fetch_query))
            {   
                $toReturn['renewid'] = $row['renewid'];
                $toReturn['renew_apply_date'] = $row['datetime_apply'];
                $toReturn['permitid'] = $row['permitid'];
                $month = date('m-Y', strtotime($row['created_at']));
                $toReturn['permit_sl']= $row['abbreviation'].'/'.$month.'/'.$row['serial_no']; 
                $toReturn['vendor_name']= $row['vendor_code']; 
                $toReturn['name']       = $row['name']; 

                // Showing status
                if($row['status'] == "Pending_Renew_Issuer")
                {
                    $toReturn['status2']="Pending at Executing Agency";
                    $toReturn['issueBtn']="Yes";
                }
                elseif($row['status'] == "Pending_Renew_Area")
                {
                    $toReturn['status2']="Pending at Owner Agency";
                    $toReturn['issueBtn']="Yes";
                }

                $pendingRenewalPermit[]=$toReturn;
            }
            echo json_encode($pendingRenewalPermit);
            break;

        case 'permit-expired':
            $myid = $_REQUEST["my-id"];
            $date = date('Y-m-d H:i:s');
            $start = $_REQUEST["start"];
            $end   = $_REQUEST["end"];
            if($start == "" && $end ==""){
                $start= 1;
                $end =  9999;
            }

            $listOfExpired ="SELECT * FROM (
                    SELECT permits.id as permitid,permits.serial_no,permits.division_id,
                        permits.created_at,permits.status,permits.return_status,userlogins.id as userid,
                        userlogins.vendor_code,userlogins.name,userlogins.user_type,
                        divisions.abbreviation, ROW_NUMBER() OVER (ORDER BY permits.id DESC) as row
                FROM permits 
                    INNER JOIN userlogins ON permits.entered_by  = userlogins.id
                    INNER JOIN divisions  ON permits.division_id = divisions.id
                WHERE  permits.issuer_id='$myid' AND status='Issued' AND end_date < '$date'   
            ) a WHERE a.row >= '$start' and a.row <= '$end'";

                    
                    
            $fetch_query = sqlsrv_query($sqlcon, $listOfExpired);
            $expiredPermit=array();
            $toReturn  =  array();
            while($row=sqlsrv_fetch_array($fetch_query))
            {
                $toReturn['create_date'] =   $row['created_at'];
                $month = date('m-Y', strtotime($row['created_at']));
                $toReturn['permit_sl']=$row['abbreviation'].'/'.$month.'/'.$row['serial_no']; 
                $toReturn['vendor_code']  =  $row['vendor_code']; 
                $toReturn['name']         =  $row['name']; 

                if($row['status'] == "Issued")
                {
                    $toReturn['status2']="Issued";
                }

                // show the return status activity 
                if($row['return_status'] == "Pending"){
                    $toReturn['status2'] .= '/Return Pending at Executing Agency';
                }
                elseif($row['return_status'] == "Returned"){
                    $toReturn['status2'] .= '/Permit Returned';
                }
                elseif($row['return_status'] == "Pending_area")
                {
                    $toReturn['status2'] .= "/Return Pending at Owner Agency";
                }
                elseif($row['return_status'] == "PPg")
                {
                    $toReturn['status2'] .= "/Return Pending at Power Cutting Agency";
                }

                $expiredPermit[]=$toReturn;
            }
            echo json_encode($expiredPermit);
            break;

        case 'permit-power-cutting':
            $myid = $_REQUEST["my-id"];

            $listOfExpired ="SELECT permits.id as permitid,
                        permits.serial_no,permits.division_id,permits.created_at,permits.status,
                        permits.return_status,userlogins.id as userid,userlogins.vendor_code,
                        userlogins.name,userlogins.user_type,divisions.abbreviation
                FROM permits 
                    INNER JOIN userlogins ON permits.entered_by  = userlogins.id
                    INNER JOIN divisions  ON permits.division_id = divisions.id
                WHERE  permits.ppc_userid='$myid' AND status='PPc' ORDER BY permits.id DESC";  

            $fetch_query    = sqlsrv_query($sqlcon, $listOfExpired);
            $expiredPermit  = array();
            $toReturn  =  array();
            while($row=sqlsrv_fetch_array($fetch_query))
            {
                $toReturn['create_date'] =   $row['created_at'];
                $toReturn['permitid'] =   $row['permitid'];
                $month   = date('m-Y', strtotime($row['created_at']));
                $toReturn['permit_sl']   =$row['abbreviation'].'/'.$month.'/'.$row['serial_no']; 
                $toReturn['vendor_code']  =  $row['vendor_code']; 
                $toReturn['name']         =  $row['name']; 

                if($row['status'] == "PPc")
                {
                    $toReturn['viewBtn']="Yes";
                }else{
                    $toReturn['viewBtn']="No";
                }

                // show the return status activity 
                if($row['status'] == "PPc"){
                    $toReturn['status2'] = 'Pending at Power Cutting';
                }
                
                $expiredPermit[]=$toReturn;
            }
            echo json_encode($expiredPermit);
            break;

        case 'permit-power-getting':
            $myid = $_REQUEST["my-id"];
            $listOfExpired ="SELECT permits.id as permitid,
                        permits.serial_no,
                        permits.division_id,
                        permits.created_at,
                        permits.status,
                        permits.return_status,
                        userlogins.id as userid,
                        userlogins.vendor_code,
                        userlogins.name,
                        userlogins.user_type,
                        divisions.abbreviation,
                        jobs.job_title
                FROM permits 
                    INNER JOIN userlogins ON permits.entered_by  = userlogins.id
                    INNER JOIN divisions  ON permits.division_id = divisions.id
                    INNER JOIN jobs ON permits.job_id = jobs.id
                WHERE  permits.ppg_userid='$myid' AND return_status='PPg' ORDER BY permits.id DESC";  

            $fetch_query    = sqlsrv_query($sqlcon, $listOfExpired);
            $expiredPermit  = array();
            $toReturn  =  array();
            while($row=sqlsrv_fetch_array($fetch_query))
            {
                $toReturn['create_date']  =   $row['created_at'];
                $toReturn['permitid']     =   $row['permitid'];
                $month = date('m-Y', strtotime($row['created_at']));
                $toReturn['permit_sl']    =  $row['abbreviation'].'/'.$month.'/'.$row['serial_no']; 
                $toReturn['vendor_code']  =  $row['vendor_code']; 
                $toReturn['name']         =  $row['name']; 
                $toReturn['job_title']    =  $row['job_title']; 


                if($row['user_type'] == 1){
                    $toReturn['user_type'] = "Empolyee";
                }else{
                    $toReturn['user_type'] = "Vendor";
                }

                if($row['return_status'] == "PPg")
                {
                    $toReturn['viewBtn']="Yes";
                }else{
                    $toReturn['viewBtn']="No";
                }

                // show the return status activity 
                if($row['return_status'] == "PPc"){
                    $toReturn['status2'] = 'Pending at Power Cutting';
                }
                
                $expiredPermit[]=$toReturn;
            }
            echo json_encode($expiredPermit);
            break;

        case 'createPermit':
            header("Access-Control-Allow-Origin: *");
            $data = json_decode(file_get_contents('php://input'), true);
            date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
            $currentTime =  date('Y-m-d H:i:s'); 
          
            $division          = $data["division-id"];
            $department        = $data["department-id"];
            $orderNumber       = $data["orderNo"];
            $orderValidity     = $data["orderValidity"];
            $start             = $data["start-date"];
            $end               = $data["end-date"];
            $jobDescription    = $data["job-description"];
            $jobLocation       = $data["job-location"];
            $jobID             = $data["job-category"];
            $enterID           = $data["enter-by"];
            $issuer_id         = $data["issuer-id"];
            $weldingGas        = $data["welding-gas"];
            $riggine           = $data["riggine"];
            $workingAtHeight   = $data["working-at-height"];
            $hydraulicPneumatic = $data["hydraulic-pneumatic"];
            $paintingCleaning   = $data["painting-cleaning"];
            $gas                = $data["gas"];
            $others             = $data["others"];
            $specifyOthers      = $data["specify-others"];
            $safeWork           = $data["safe-work"];
            $allPersons         = $data["all-persons"];
            $workerWorking      = $data["worker-working"];
            $allLifting         = $data["all-lifting"];
            $allSafety          = $data["all-safety"];
            $allPersonsTrained  = $data["all-persons-trained"];
            $ensureApplicable   = $data["ensure-applicable"];
            $permitRequestName  = $data["permit-requster-name"];

                
            $userType = sqlsrv_query($sqlcon,"SELECT user_type from userlogins WHERE id='$enterID'");
            $gettype  = sqlsrv_fetch_array($userType);
            $type     = $gettype['user_type'];

            // For Vendor Validation
            if($type == 2){
                if($orderNumber == '' && $orderValidity == '')
                {
                    //echo Given Order Number and validity";
                    $message['response'] = "Order No & Order Validity can't be blank";
                    echo json_encode($message); 
                    exit;
                }
                $order1 = date('Y-m-d', strtotime($orderValidity));
                $order2 = date('Y-m-d', strtotime($end));

                if($order2 > $order1)
                {
                    $message['response'] = "Order Validity must be greater than End Date";
                    echo json_encode($message); 
                    exit;
                }
            }
          
            // Get the Job Details based on job id
                $runJob = sqlsrv_query($sqlcon, "SELECT swp_number,high_risk,power_clearance,confined_space
                                        from jobs WHERE id='$jobID'");
                $jobFetch = sqlsrv_fetch_array($runJob);
                $swpNumber        = $jobFetch['swp_number'];
                $highRisk         = $jobFetch['high_risk'];
                $powerClearence   = $jobFetch['power_clearance'];
                $confinedSpace    = $jobFetch['confined_space'];

            // Serial Number Generated 
                $transdate = date('Y-m-d');
                $month = date('m', strtotime($transdate));
                $year  = date('Y', strtotime($transdate));
         
                $divv = "SELECT serial_no from permits WHERE division_id='$division' AND YEAR(created_at)='$year' AND MONTH(created_at)='$month' order by id DESC"; 
                $resultid2     = sqlsrv_query($sqlcon,$divv);
                $rowd2         = sqlsrv_fetch_array($resultid2);
                if ($divv)
                {
                    $v = $rowd2['serial_no'];
                    $v++;
                    $serial_no=$v;
                }
                else{
                    $serial_no="1"; 
                }

            // Insert Record INTO Permit 
            $permitInsertQuery = "INSERT INTO permits 
                (
                    division_id,department_id,serial_no,order_no,order_validity,
                    start_date,end_date,job_id,welding_gas,riggine,
                    working_at_height,hydraulic_pneumatic,painting_cleaning,gas,others,
                    specify_others,swp_number,high_risk,power_clearance,confined_space,
                    post_site_pic,status,entered_by,request_dt,issuer_id,job_description,
                    job_location,permit_req_name,safe_work,all_person,
                    worker_working,all_lifting_tools,all_safety_requirement,
                    all_person_are_trained,ensure_the_appplicablle,created_at
                ) 
                VALUES ('$division','$department','$serial_no','$orderNumber','$orderValidity',
                    '$start','$end','$jobID','$weldingGas','$riggine',
                    '$workingAtHeight','$hydraulicPneumatic','$paintingCleaning','$gas','$others',
                    '$specifyOthers','$swpNumber','$highRisk','$powerClearence','$confinedSpace','',
                    'Requested','$enterID','$currentTime','$issuer_id','$jobDescription',
                    '$jobLocation','$permitRequestName','$safeWork','$allPersons',
                    '$workerWorking','$allLifting','$allSafety',
                    '$allPersonsTrained','$ensureApplicable','$currentTime') SELECT SCOPE_IDENTITY()";

            // echo $permitInsertQuery;
            // exit;

            $excute         = sqlsrv_query($sqlcon, $permitInsertQuery);
            sqlsrv_next_result($excute); 
            sqlsrv_fetch($excute); 
            $latestPermitID = sqlsrv_get_field($excute, 0); 

            $dataHazards  =  $data["SixDirection"];
            foreach($dataHazards as $key => $value) {
                $permit_id    = $latestPermitID;
                $direction    = $value['direction'];
                $hazard       = $value['hazard'];
                $precaution   = $value['precaution'];
                
                $hazInsert ="Insert INTO permit_hazards (permit_id,dir,hazard,precaution) 
                            VALUES ('$permit_id','$direction','$hazard','$precaution')";
                $result = sqlsrv_query($sqlcon,$hazInsert); 
            }
        
            // Insert Into Employee table
            $dataEmployee   = $data["Employee"];
            foreach($dataEmployee as $key => $value) {
                $permit_id    = $latestPermitID;
                $employeeName = $value['employee-name'];
                $gatePass     = $value['gate-pass-no'];
                $designation  = $value['designation'];
                $age          = $value['age'];
                $expiryDate   = $value['expiry-date'];

                $gatePassInsert="Insert INTO gate_pass_details (permit_id,employee_name,
                                gate_pass_no,designation,age,expirydate) 
                                VALUES ('$permit_id','$employeeName','$gatePass','$designation',
                                '$age','$expiryDate')";
                $result = sqlsrv_query($sqlcon,$gatePassInsert); 
            }

            if ($excute && $gatePassInsert && $hazInsert)    
            {   
                $message['response'] = "Insert Successfully";
                echo json_encode($message); 
            }     
            else     
            {    
                $message['response'] ="Permit Not Insert";
                echo json_encode($message);
            }
            break;

        case 'viewPermit':
            $my_id     =  $_REQUEST["my-id"];
            $permit_id =  $_REQUEST["permit-id"];
            $queryview = "SELECT permits.id as
                permitid,permits.power_clearance,permits.high_risk,permits.confined_space,
                permits.serial_no,permits.division_id,divisions.name as divname,
                permits.department_id,departments.department_name,permits.order_no,
                permits.order_validity,permits.start_date,permits.end_date,permits.job_description,
                permits.job_location,jobs.id as jobid,permits.welding_gas,permits.riggine,
                permits.working_at_height,permits.hydraulic_pneumatic,permits.painting_cleaning,
                permits.gas,permits.others,permits.specify_others,permits.post_site_pic,
                permits.latlong,permits.safe_work,permits.all_person,permits.worker_working,
                permits.all_lifting_tools,permits.all_safety_requirement,
                permits.all_person_are_trained,permits.ensure_the_appplicablle,
                permits.power_clearance_number,permits.area_clearence_required,
                permits.area_clearence_id,jobs.job_title,jobs.swp_number,forissuer1.id as issuer1id,
                forissuer1.name as issuer1name,froareaclearence.id as area_cls_id,
                froareaclearence.name as area_cls_name,permits.status,permits.vlevel,permits.issuer_power,
                permits.electrical_license_issuer,permits.validity_date_issuer,permits.rec_power,
                permits.electrical_license_rec,permits.validity_date_rec,permits.created_at,
                divisions.abbreviation,permits.s_instruction,permits.ppc_userid,power_cutting_remarks,
                permits.other_isolation,permits.executing_lock,permits.working_lock
                FROM permits
                    LEFT JOIN userlogins as forissuer1 ON permits.issuer_id =  forissuer1.id
                    LEFT JOIN userlogins as froareaclearence ON permits.area_clearence_id = froareaclearence.id
                    LEFT JOIN divisions ON permits.division_id      = divisions.id
                    LEFT JOIN departments ON permits.department_id  = departments.id
                    LEFT JOIN jobs ON permits.job_id                = jobs.id
                WHERE  (permits.issuer_id = '$my_id' AND permits.id = '$permit_id') 
                    OR (permits.area_clearence_id = '$my_id' AND permits.id = '$permit_id') 
                    OR (permits.entered_by = '$my_id' AND permits.id = '$permit_id')";
            // echo $queryview;
            // exit;
            $fetch_query1 = sqlsrv_query($sqlcon, $queryview);
            $getIssuerPermitDetails = array();
            while($row = sqlsrv_fetch_array($fetch_query1))
            {
                $getIssuerPermitDetails['permitid']     = $row['permitid'];
                $getIssuerPermitDetails['divisionId']   = $row['division_id'];
                $getIssuerPermitDetails['divisionName'] = $row['divname'];
                $getIssuerPermitDetails['departmentId'] = $row['department_id'];
                $getIssuerPermitDetails['departmentName'] = $row['department_name'];
                $getIssuerPermitDetails['orderNumber']    = $row['order_no'];
                $getIssuerPermitDetails['orderValidity']  = $row['order_validity'];
                $getIssuerPermitDetails['startDate']      = $row['start_date'];
                $getIssuerPermitDetails['endDate']        = $row['end_date'];
                $getIssuerPermitDetails['jobDescription'] = $row['job_description'];
                $getIssuerPermitDetails['jobLocation']    = $row['job_location'];
                $getIssuerPermitDetails['jobId']          = $row['jobid'];
                $jobid                                    = $row['jobid'];
                $getIssuerPermitDetails['job_title'] = $row['job_title'];
                $getIssuerPermitDetails['swp_number'] = $row['swp_number'];

                $swpfile = "SELECT swp_file FROM swp_files WHERE job_id='$jobid'";
                $runQueryswp   = sqlsrv_query($sqlcon, $swpfile);
                $li0 =0;
                while($rowswp=sqlsrv_fetch_array($runQueryswp))
                {
                    $getIssuerPermitDetails['SwpFile'][$li0]['swp_file']    = $rowswp['swp_file'];
                    $li0++;
                }

                $getIssuerPermitDetails['weldingGas']         =  $row['welding_gas'];
                $getIssuerPermitDetails['riggine']            =  $row['riggine'];
                $getIssuerPermitDetails['workingAtHeight']    =  $row['working_at_height'];
                $getIssuerPermitDetails['hydraulicPneumatic'] =  $row['hydraulic_pneumatic'];
                $getIssuerPermitDetails['paintingCleaning']   =  $row['painting_cleaning'];
                $getIssuerPermitDetails['gas']                =  $row['gas'];
                $getIssuerPermitDetails['others']             =  $row['others'];
                $getIssuerPermitDetails['specifyOthers']      =  $row['specify_others'];

                $getHazard = "SELECT id,dir,hazard,precaution FROM permit_hazards WHERE permit_id='$permit_id'";
                $runQuery1   = sqlsrv_query($sqlcon, $getHazard);
                $li1=0;
                while($row2=sqlsrv_fetch_array($runQuery1))
                {
                    $getIssuerPermitDetails['SixDirection'][$li1]['id']         = $row2['id'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['dir']        = $row2['dir'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['hazard']     = $row2['hazard'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['precaution'] = $row2['precaution'];
                    $li1++;
                }


                $getIssuerPermitDetails['issuer1id'] = $row['issuer1id'];
                $getIssuerPermitDetails['issuer1name'] = $row['issuer1name'];

                $getGatePass = "SELECT id,employee_name,gate_pass_no,designation,age,expirydate FROM gate_pass_details WHERE permit_id='$permit_id'";
                $runQuery2   = sqlsrv_query($sqlcon, $getGatePass);
                $li2=0;
                while($row3=sqlsrv_fetch_array($runQuery2))
                {
                    $getIssuerPermitDetails['Empolyee'][$li2]['id']              = $row3['id'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['emp_name']        = $row3['employee_name'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['g_pass_number']   = $row3['gate_pass_no'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['design']          = $row3['designation'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['age']             = $row3['age'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['expirydate']      = $row3['expirydate'];
                    $li2++;
                }

                $getIssuerPermitDetails['safe_work']            = $row['safe_work'];
                $getIssuerPermitDetails['all_person']           = $row['all_person'];
                $getIssuerPermitDetails['worker_working']       = $row['worker_working'];
                $getIssuerPermitDetails['all_lifting_tools']    = $row['all_lifting_tools'];
                $getIssuerPermitDetails['all_safety_requirement']  = $row['all_safety_requirement'];
                $getIssuerPermitDetails['all_person_are_trained']  = $row['all_person_are_trained'];
                $getIssuerPermitDetails['ensure_the_appplicablle'] = $row['ensure_the_appplicablle'];
                $getIssuerPermitDetails['status']                  = $row['status'];
                $getIssuerPermitDetails['post_site_pic']           = $row['post_site_pic'];
                $getIssuerPermitDetails['high_risk']               = $row['high_risk'];
                $getIssuerPermitDetails['latlong']                 = $row['latlong'];
                $getIssuerPermitDetails['power_clearance_number']  = $row['power_clearance_number'];
                $getIssuerPermitDetails['area_cls_id']             = $row['area_cls_id'];
                $getIssuerPermitDetails['area_cls_name']           = $row['area_cls_name'];
                $getIssuerPermitDetails['area_clearence_required'] = $row['area_clearence_required'];
                $getIssuerPermitDetails['executing_lock']           = $row['executing_lock'];
                $getIssuerPermitDetails['working_lock']             = $row['working_lock'];

                

                
                $powerCuttingUsers = sqlsrv_query($sqlcon, "SELECT id,name FROM userlogins WHERE division_id='".$row['division_id']."' AND user_type ='1' AND power_cutting ='Yes'");
                $indexPC = 0;
                while($powerCuttingUsersList  =  sqlsrv_fetch_array($powerCuttingUsers))
                {
                    $getIssuerPermitDetails['pcUserList'][$indexPC]['id']  = $powerCuttingUsersList['id'];
                    $getIssuerPermitDetails['pcUserList'][$indexPC]['name']  = $powerCuttingUsersList['name'];
                    $indexPC++;
                }
                

                $forAreaClearence = sqlsrv_query($sqlcon, "SELECT id,name FROM userlogins WHERE division_id='".$row['division_id']."'");
                $indexOG = 0;
                while($forAreaClearenceList  =  sqlsrv_fetch_array($forAreaClearence))
                {
                    $getIssuerPermitDetails['ownerAgencyList'][$indexOG]['id']  = $forAreaClearenceList['id'];
                    $getIssuerPermitDetails['ownerAgencyList'][$indexOG]['name']  = $forAreaClearenceList['name'];
                    $indexOG++;
                }

                $pcusername = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT id,name FROM userlogins WHERE id='".$row['ppc_userid']."'"));
                $getIssuerPermitDetails['id']           = $pcusername['id'];
                $getIssuerPermitDetails['pcusername']   = $pcusername['name'];

                $getIssuerPermitDetails['vlevel']       = $row['vlevel'];
                $getIssuerPermitDetails['issuer_power_id'] = $row['issuer_power'];
                if($getIssuerPermitDetails['issuer_power_id'] != '0'){
                    $IssuerName = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$getIssuerPermitDetails['issuer_power_id']."'"));
                    $getIssuerPermitDetails['powerIssuerName'] = $IssuerName['name'];
                }
                $getIssuerPermitDetails['electrical_license_issuer'] = $row['electrical_license_issuer'];
                $getIssuerPermitDetails['validity_date_issuer']      = $row['validity_date_issuer'];

                $getIssuerPermitDetails['power_clearance']           = $row['power_clearance'];
                $getPowercls = "SELECT id,equipment,positive_isolation_no,location,box_no,caution_no FROM power_clearences WHERE permit_id='$permit_id'";
                $runQuery3   = sqlsrv_query($sqlcon, $getPowercls);
                $li3 = 0;
                while($row4=sqlsrv_fetch_array($runQuery3))
                {
                    $getIssuerPermitDetails['PowerClearences'][$li3]['id']                      = $row4['id'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['equipment']               = $row4['equipment'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['positive_isolation_no']   = $row4['positive_isolation_no'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['location']                = $row4['location'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['box_no']                  = $row4['box_no'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['caution_no']              = $row4['caution_no'];
                    $li3++;
                }
                $getIssuerPermitDetails['power_cutting_remarks'] = $row['power_cutting_remarks'];
                $getIssuerPermitDetails['s_instruction']         = $row['s_instruction'];

                $getIssuerPermitDetails['other_isolation']           = $row['other_isolation'];
                $otherIsolationQuery = "SELECT id,positive_other,equipment_other,location_other FROM other_isolation WHERE permit_id='$permit_id'";
                $runIsolation   = sqlsrv_query($sqlcon, $otherIsolationQuery);
                $index = 0;
                while($row4=sqlsrv_fetch_array($runIsolation))
                {
                    $getIssuerPermitDetails['OtherIsolation'][$index]['id']               = $row4['id'];
                    $getIssuerPermitDetails['OtherIsolation'][$index]['positive_other']   = $row4['positive_other'];
                    $getIssuerPermitDetails['OtherIsolation'][$index]['equipment_other']  = $row4['equipment_other'];
                    $getIssuerPermitDetails['OtherIsolation'][$index]['location_other']   = $row4['location_other'];
                    $index++;
                }

                $getIssuerPermitDetails['confined_space'] = $row['confined_space'];
                $getconfined = "SELECT id,clearance_no,depth,location FROM confined_spaces 
                                WHERE permit_id='$permit_id'";
                $runQuery4   = sqlsrv_query($sqlcon, $getconfined);
                $key1 =0;
                while($row5=sqlsrv_fetch_array($runQuery4))
                {
                    $getIssuerPermitDetails['ConfinedSpace'][$key1]['id']   = $row5['id'];
                    $getIssuerPermitDetails['ConfinedSpace'][$key1]['clearence_num']   = $row5['clearance_no'];
                    $getIssuerPermitDetails['ConfinedSpace'][$key1]['depth']          = $row5['depth'];
                    $getIssuerPermitDetails['ConfinedSpace'][$key1]['location']       = $row5['location'];
                    $key1++;
                }
            }
            echo json_encode($getIssuerPermitDetails);
            break;
        
        case 'voltage-level-issuer':
            $vlevel   = $_REQUEST['voltage-level'];
            $division = $_REQUEST['division-id'];
            $Alldata = array();

            if($vlevel == '[132KV]'){
                $querytoget ="SELECT shutdownchilds.*,userlogins.id as userid,userlogins.name
                FROM userlogins
                    LEFT JOIN shutdownchilds ON shutdownchilds.userlogins_id =  userlogins.id
                WHERE  userlogins.division_id = '$division' 
                    AND shutdownchilds.[132KV] = 'yes'
                    AND shutdownchilds.[33KV] = 'yes'
                    AND shutdownchilds.[11KV] = 'yes'
                    AND shutdownchilds.[LT] = 'yes'
                    AND shutdownchilds.issue_power = 'yes'";
            }
            elseif($vlevel == '[33KV]'){
                $querytoget ="SELECT shutdownchilds.*,userlogins.id as userid,userlogins.name
                FROM userlogins
                    LEFT JOIN shutdownchilds ON shutdownchilds.userlogins_id =  userlogins.id
                WHERE  userlogins.division_id = '$division' 
                    AND shutdownchilds.[33KV] = 'yes'
                    AND shutdownchilds.[11KV] = 'yes'
                    AND shutdownchilds.[LT] = 'yes'
                    AND shutdownchilds.issue_power = 'yes'";
            }
            elseif($vlevel == '[11KV]'){
                $querytoget ="SELECT shutdownchilds.*,userlogins.id as userid,userlogins.name
                FROM userlogins
                    LEFT JOIN shutdownchilds ON shutdownchilds.userlogins_id =  userlogins.id
                WHERE  userlogins.division_id = '$division' 
                    AND shutdownchilds.[11KV] = 'yes'
                    AND shutdownchilds.[LT] = 'yes'
                    AND shutdownchilds.issue_power = 'yes'";
            }
            elseif($vlevel == '[LT]'){
                $querytoget ="SELECT shutdownchilds.*,userlogins.id as userid,userlogins.name
                FROM userlogins
                    LEFT JOIN shutdownchilds ON shutdownchilds.userlogins_id =  userlogins.id
                WHERE  userlogins.division_id = '$division' 
                    AND shutdownchilds.[LT] = 'yes'
                    AND shutdownchilds.issue_power = 'yes'";
            }

            $Querycon     =   sqlsrv_query($sqlcon, $querytoget);
            $i=0;                   
            while($rowData2 = sqlsrv_fetch_array($Querycon))
            {
                if($rowData2['supervisor_name']){
                    $supervisor  = "(". $rowData2['supervisor_name'] . ")";
                }
                else{
                    $supervisor = "";
                }
                $Alldata['PowerClearanceIssuer'][$i]['id']                 = $rowData2['userid'];
                $Alldata['PowerClearanceIssuer'][$i]['name']               = $rowData2['name']. $supervisor;
                $Alldata['PowerClearanceIssuer'][$i]['electrical_license'] = $rowData2['electrical_license'];
                $Alldata['PowerClearanceIssuer'][$i]['validity_date']      = $rowData2['validity_date'];
                $i++;
            }

            echo json_encode($Alldata); 
            break;
           
        case 'voltage-level-receiver':
            $vlevel   = $_REQUEST['voltage-level'];
            $division = $_REQUEST['division-id'];
            $Alldata = array(); 

                if($vlevel == '[132KV]'){
                    $querytoget ="SELECT shutdownchilds.*,userlogins.id as userid,userlogins.name
                    FROM userlogins
                        LEFT JOIN shutdownchilds ON shutdownchilds.userlogins_id =  userlogins.id
                    WHERE  userlogins.division_id = '$division' 
                        AND shutdownchilds.[132KV] = 'yes'
                        AND shutdownchilds.[33KV] = 'yes'
                        AND shutdownchilds.[11KV] = 'yes'
                        AND shutdownchilds.[LT] = 'yes'
                        AND shutdownchilds.receive_power = 'yes'";
                }
                elseif($vlevel == '[33KV]'){
                    $querytoget ="SELECT shutdownchilds.*,userlogins.id as userid,userlogins.name
                    FROM userlogins
                        LEFT JOIN shutdownchilds ON shutdownchilds.userlogins_id =  userlogins.id
                    WHERE  userlogins.division_id = '$division' 
                        AND shutdownchilds.[33KV] = 'yes'
                        AND shutdownchilds.[11KV] = 'yes'
                        AND shutdownchilds.[LT] = 'yes'
                        AND shutdownchilds.receive_power = 'yes'";
                }
                elseif($vlevel == '[11KV]'){
                    $querytoget ="SELECT shutdownchilds.*,userlogins.id as userid,userlogins.name
                    FROM userlogins
                        LEFT JOIN shutdownchilds ON shutdownchilds.userlogins_id =  userlogins.id
                    WHERE  userlogins.division_id = '$division' 
                        AND shutdownchilds.[11KV] = 'yes'
                        AND shutdownchilds.[LT] = 'yes'
                        AND shutdownchilds.receive_power = 'yes'";
                }
                elseif($vlevel == '[LT]'){
                    $querytoget ="SELECT shutdownchilds.*,userlogins.id as userid,userlogins.name
                    FROM userlogins
                        LEFT JOIN shutdownchilds ON shutdownchilds.userlogins_id =  userlogins.id
                    WHERE  userlogins.division_id = '$division' 
                        AND shutdownchilds.[LT] = 'yes'
                        AND shutdownchilds.receive_power = 'yes'";
                }

                $Querycon     =   sqlsrv_query($sqlcon, $querytoget);
                $i=0;                   
                while($rowData2 = sqlsrv_fetch_array($Querycon))
                {
                    if($rowData2['supervisor_name']){
                        $supervisor  = "(". $rowData2['supervisor_name'] . ")";
                    }
                    else{
                        $supervisor = "";
                    }

                    $Alldata['PowerClearanceReceiver'][$i]['id']    = $rowData2['userid'];
                    $Alldata['PowerClearanceReceiver'][$i]['name']    = $rowData2['name'] .$supervisor;
                    $Alldata['PowerClearanceReceiver'][$i]['electrical_license']    = $rowData2['electrical_license'];
                    $Alldata['PowerClearanceReceiver'][$i]['validity_date']    = $rowData2['validity_date'];
                    $i++;
                }
                echo json_encode($Alldata); 
                break;

        case 'updatePermit':
            header("Access-Control-Allow-Origin: *");
            $data = json_decode(file_get_contents('php://input'), true);
            date_default_timezone_set("Asia/Calcutta");
            $currentTime =  date('Y-m-d H:i:s'); 

            $myid               = $data['my-id'];
            $permit_id          = $data["permit-id"];
          
            $forID = "SELECT id FROM permits 
                        WHERE issuer_id='$myid' AND id='$permit_id' AND status='Requested' 
                        OR  ppc_userid='$myid' AND id='$permit_id' AND status='PPc' 
                        OR  area_clearence_id='$myid' AND id='$permit_id' AND status='Parea'";
            $runQuery   = sqlsrv_query($sqlcon, $forID);
            $row        = sqlsrv_fetch_array($runQuery);
            $found      = $row['id'];


            if($found)
            {
                if($data["area_clearance_req"] == 'on')
                {
                    $area_clearance_req = 'on';
                }
                else{
                    $area_clearance_req = 'off';
                }
                // echo $area_clearance_req;
                // exit;

                $area_clearence_id       =  @$data["area_clearence_id"];
                $power_cutting_userid    =  @$data["power_cutting_userid"];
                $excuting_personal_lock  =  @$data["executing_personal_lock"];
                $working_personal_lock   =  @$data["working_personal_lock"];

                        
                // Check permit details
                $permitdata = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT status,power_clearance,power_clearance_number
                                         FROM permits WHERE id='$permit_id'"));
                
                    //1st  Phase
                    if($permitdata['status'] == 'Requested' && $permitdata['power_clearance'] == 'on' && $permitdata['power_clearance_number'] == ''){
                        //echo "1";
                        $status          = 'PPc';
                        $colname         = 'issuer_dt';
                        $other_Isolation = $data['other_Isolation'];
                    }

                    elseif($permitdata['status'] == 'Requested' && $area_clearance_req =='off' && $permitdata['power_clearance'] == 'off'){
                        // echo "2";
                        $status       ='Issued';
                        $colname      ='issuer_dt';
                        $other_Isolation = $data['other_Isolation'];
                    }

                    elseif($permitdata['status'] == 'Requested' && $area_clearance_req =='on' && $permitdata['power_clearance'] == 'off'){
                        // echo "3";
                        $status       ='Parea';
                        $colname      ='issuer_dt';
                        $other_Isolation = $data['other_Isolation'];
                    }

                    //2nd Phase
                    elseif($permitdata['status'] == 'Requested' && $area_clearance_req =='off' && $permitdata['power_clearance'] == 'on' && $permitdata['power_clearance_number'] != ''){
                        // echo "4";
                        $status       ='Issued';
                        $colname      ='issuer_dt2';
                        $other_Isolation = $data['other_Isolation'];
                    }           
                    elseif($permitdata['status'] == 'Requested' &&  $area_clearance_req =='on'  && $permitdata['power_clearance'] == 'on' && $permitdata['power_clearance_number'] != ''){
                        // echo "5";
                        $status       ='Parea';
                        $colname      ='issuer_dt2';
                        $other_Isolation = $data['other_Isolation'];
                    }
                    elseif($permitdata['status'] == 'Parea'){
                       // echo "6";
                        $status       = 'Issued';
                        $colname      = 'area_clearence_dt';
                        $other_Isolation = $data['other_Isolation'];
                    }
                    // echo $status;
                    // exit;
                
                if($permitdata['status'] == "Prcv"){
                }
                else{
                    if(!empty($data["post_site_pic"]))
                    {
                        $encoded = $data["post_site_pic"];
                        $rand='public/documents/site_pics/'. uniqid() . '.png';
                        $file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
                        fwrite($file, base64_decode($encoded));
                        fclose($file);

                        $query = "UPDATE permits 
                        SET
                            area_clearence_required  = '$area_clearance_req',
                            area_clearence_id        = '$area_clearence_id',
                            ppc_userid               = '$power_cutting_userid',
                            post_site_pic          =  '$rand',
                            status                 =  '$status',
                            $colname               =  '$currentTime',
                            s_instruction          =  '$s_instruction',
                            other_isolation        =  '$other_Isolation',
                            executing_lock         =  '$excuting_personal_lock',
                            working_lock           =  '$working_personal_lock'
                        WHERE id='$permit_id'";
                    }
                    else{
                        $query = "UPDATE permits 
                        SET area_clearence_required  = '$area_clearance_req',
                            area_clearence_id        = '$area_clearence_id',
                            ppc_userid               = '$power_cutting_userid',
                            status                 =  '$status',
                            $colname               =  '$currentTime',
                            s_instruction          =  '$s_instruction',
                            other_isolation        =  '$other_Isolation',
                            executing_lock         =  '$excuting_personal_lock',
                            working_lock           =  '$working_personal_lock'
                        WHERE id='$permit_id'";
                    }
                    // echo $query;
                    // exit;
                    $executeQuery = sqlsrv_query($sqlcon, $query);
                }   

                // Six directional Hazards
                $SixDirection     =  $data["sixDirection"];
                foreach($SixDirection as $key => $value) {
                    $unique_id   = $value['id'];

                    if(!empty($unique_id)){
                        $direction        = $value['direction'];
                        $hazard           = $value['hazard'];
                        $precaution       = $value['precaution'];
                        $Queryforins_upt  ="UPDATE permit_hazards  
                                            SET dir      = '$direction',
                                                hazard   = '$hazard',
                                                precaution  = '$precaution'
                                            WHERE id ='$unique_id'";
                       $runQuery1 = sqlsrv_query($sqlcon,$Queryforins_upt); 
                    }
                    else{
                        $permit_id        = $permit_id;
                        $direction        = $value['direction'];
                        $hazard           = $value['hazard'];
                        $precaution       = $value['precaution'];

                        $Queryforins_upt ="Insert INTO permit_hazards (permit_id,dir,hazard,precaution) 
                                        VALUES ('$permit_id','$direction','$hazard','$precaution')";
                        $runQuery1 = sqlsrv_query($sqlcon,$Queryforins_upt); 
                    }
                }

                //Other Power clearance  
                if($other_Isolation == 'yes'){
                    $otherIsolationData     =  $data["otherIsolationArray"];
                    foreach($otherIsolationData as $key => $value) {
                        $unique_id   = $value['id'];
                        if(!empty($unique_id)){
                            echo "update";
                            $positiveOther       = $value['positive_other'];
                            $equipmentOther      = $value['equipment_other'];
                            $locationOther       = $value['location_other'];
                        
                            $otherIsolationQuery ="UPDATE other_isolation 
                                    SET positive_other   = '$positiveOther',
                                        equipment_other  = '$equipmentOther',
                                        location_other   = '$locationOther'
                                    WHERE id='$unique_id'";
                            $runQuery3 = sqlsrv_query($sqlcon,$otherIsolationQuery); 
                        }
                        else{
                            $permit_id           = $permit_id;
                            $positiveOther       = $value['positive_other'];
                            $equipmentOther      = $value['equipment_other'];
                            $locationOther       = $value['location_other'];
                            $otherIsolationQuery ="Insert INTO other_isolation (permit_id,
                                            positive_other,equipment_other,location_other) 
                                            VALUES ('$permit_id','$positiveOther','$equipmentOther',
                                                    '$locationOther')";
                            $runQuery3 = sqlsrv_query($sqlcon,$otherIsolationQuery); 
                        }
                    }
                }


                //Confined Space Details
                if($data['ConfinedSpace'] == "Yes"){
                    $ConfinedSpace    =  $data["Confined_Space"];
                    foreach($ConfinedSpace as $key => $value) {
                        $unique_id   = $value['id'];
                        if(!empty($unique_id)){
                            $clearance_no    = $value['clearence_num'];
                            $depth           = $value['depth'];
                            $location        = $value['location'];
                            $updatedate      = $currentTime;

                        
                            $ins_uptConfinedSpace ="UPDATE confined_spaces 
                                    SET clearance_no  = '$clearance_no',
                                        depth         = '$depth',
                                        location      = '$location',
                                        updated_at    = '$updatedate'
                                    WHERE id='$unique_id'";
                            $runQuery4 = sqlsrv_query($sqlcon,$ins_uptConfinedSpace); 

                        }else{
                            $permit_id           = $permit_id;
                            $clearance_no        = $value['clearence_num'];
                            $depth               = $value['depth'];
                            $location            = $value['location'];
                            $createdata          = $currentTime;
                        
                            $ins_uptConfinedSpace ="Insert INTO confined_spaces(permit_id,clearance_no,
                                                    depth,location,created_at) 
                                                    VALUES ('$permit_id','$clearance_no','$depth',
                                                        '$location','$createdata')";
                            $runQuery4 = sqlsrv_query($sqlcon,$ins_uptConfinedSpace); 
                        }
                    }
                }
                

                if ($executeQuery)    
                {   
                    $message['response'] = "Permit Updated Successfully";
                    echo json_encode($message); 
                }     
                else     
                {    
                    $message['response'] = "Update Error";
                    echo json_encode($message);
                }
            }  
            else {
                echo "myid variable not associated";
                die( print_r( sqlsrv_errors(), true));  
            }
            break;

        case 'viewPowerCuttingPermit':
            $my_id     =  $_REQUEST["my-id"];
            $permit_id =  $_REQUEST["permit-id"];
            $queryview = "SELECT permits.id as
                permitid,permits.serial_no,permits.division_id,divisions.name as divname,
                permits.department_id,departments.department_name,permits.order_no,permits.order_validity,
                permits.start_date,permits.end_date,permits.job_description,permits.job_location,jobs.id as jobid,
                permits.welding_gas,permits.riggine,permits.working_at_height,permits.hydraulic_pneumatic,
                permits.painting_cleaning,permits.gas,permits.others,permits.specify_others,
                permits.latlong,permits.safe_work,permits.all_person,permits.worker_working,
                permits.all_lifting_tools,permits.all_safety_requirement,permits.all_person_are_trained,
                permits.ensure_the_appplicablle,permits.area_clearence_id,jobs.job_title,jobs.swp_number,
                forissuer1.id as issuer1id,forissuer1.name as issuer1name,permits.created_at,divisions.abbreviation
                FROM permits
                    LEFT JOIN userlogins as forissuer1 ON permits.issuer_id =  forissuer1.id
                    LEFT JOIN divisions ON permits.division_id      = divisions.id
                    LEFT JOIN departments ON permits.department_id  = departments.id
                    LEFT JOIN jobs ON permits.job_id                = jobs.id
                WHERE permits.ppc_userid = '$my_id' AND permits.id = '$permit_id' AND status='PPc'";
            // echo $queryview;
            // exit;
            $fetch_query1 = sqlsrv_query($sqlcon, $queryview);
            $getIssuerPermitDetails = array();
            while($row = sqlsrv_fetch_array($fetch_query1))
            {
                $getIssuerPermitDetails['permitid']     = $row['permitid'];
                $getIssuerPermitDetails['divisionId']   = $row['division_id'];
                $getIssuerPermitDetails['divisionName'] = $row['divname'];
                $getIssuerPermitDetails['departmentId'] = $row['department_id'];
                $getIssuerPermitDetails['departmentName'] = $row['department_name'];
                $getIssuerPermitDetails['orderNumber']    = $row['order_no'];
                $getIssuerPermitDetails['orderValidity']  = $row['order_validity'];
                $getIssuerPermitDetails['startDate']      = $row['start_date'];
                $getIssuerPermitDetails['endDate']        = $row['end_date'];
                $getIssuerPermitDetails['jobDescription'] = $row['job_description'];
                $getIssuerPermitDetails['jobLocation']    = $row['job_location'];
                $getIssuerPermitDetails['jobId']          = $row['jobid'];
                $jobid                                    = $row['jobid'];
                $getIssuerPermitDetails['job_title']      = $row['job_title'];
                $getIssuerPermitDetails['swp_number']     = $row['swp_number'];

                $swpfile = "SELECT swp_file FROM swp_files WHERE job_id='$jobid'";
                $runQueryswp   = sqlsrv_query($sqlcon, $swpfile);
                $li0 =0;
                while($rowswp=sqlsrv_fetch_array($runQueryswp))
                {
                    $getIssuerPermitDetails['SwpFile'][$li0]['swp_file']    = $rowswp['swp_file'];
                    $li0++;
                }

                $getIssuerPermitDetails['weldingGas']         =  $row['welding_gas'];
                $getIssuerPermitDetails['riggine']            =  $row['riggine'];
                $getIssuerPermitDetails['workingAtHeight']    =  $row['working_at_height'];
                $getIssuerPermitDetails['hydraulicPneumatic'] =  $row['hydraulic_pneumatic'];
                $getIssuerPermitDetails['paintingCleaning']   =  $row['painting_cleaning'];
                $getIssuerPermitDetails['gas']                =  $row['gas'];
                $getIssuerPermitDetails['others']             =  $row['others'];
                $getIssuerPermitDetails['specifyOthers']      =  $row['specify_others'];

                $getHazard = "SELECT id,dir,hazard,precaution FROM permit_hazards WHERE permit_id='$permit_id'";
                $runQuery1   = sqlsrv_query($sqlcon, $getHazard);
                $li1=0;
                while($row2=sqlsrv_fetch_array($runQuery1))
                {
                    $getIssuerPermitDetails['SixDirection'][$li1]['id']         = $row2['id'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['dir']        = $row2['dir'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['hazard']     = $row2['hazard'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['precaution'] = $row2['precaution'];
                    $li1++;
                }

                $getIssuerPermitDetails['issuer1id'] = $row['issuer1id'];
                $getIssuerPermitDetails['issuer1name'] = $row['issuer1name'];

                $getGatePass = "SELECT id,employee_name,gate_pass_no,designation,age,expirydate FROM gate_pass_details WHERE permit_id='$permit_id'";
                $runQuery2   = sqlsrv_query($sqlcon, $getGatePass);
                $li2=0;
                while($row3=sqlsrv_fetch_array($runQuery2))
                {
                    $getIssuerPermitDetails['Empolyee'][$li2]['id']              = $row3['id'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['emp_name']        = $row3['employee_name'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['g_pass_number']   = $row3['gate_pass_no'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['design']          = $row3['designation'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['age']             = $row3['age'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['expirydate']      = $row3['expirydate'];
                    $li2++;
                }

                $getIssuerPermitDetails['safe_work']            = $row['safe_work'];
                $getIssuerPermitDetails['all_person']           = $row['all_person'];
                $getIssuerPermitDetails['worker_working']       = $row['worker_working'];
                $getIssuerPermitDetails['all_lifting_tools']    = $row['all_lifting_tools'];
                $getIssuerPermitDetails['all_safety_requirement']  = $row['all_safety_requirement'];
                $getIssuerPermitDetails['all_person_are_trained']  = $row['all_person_are_trained'];
                $getIssuerPermitDetails['ensure_the_appplicablle'] = $row['ensure_the_appplicablle'];
               
                $powerC = sqlsrv_query($sqlcon,"SELECT id,sl,created_at as pcCreateDate FROM power_cutting 
                        WHERE division_id='".$row['division_id']."' AND department_id='".$row['department_id']."'
                            AND getting  = 'N' AND status   = 'APP' AND user_id  = '$my_id'");
                $pcindex = 0;
                $divisionAbb = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT abbreviation FROM divisions WHERE id='".$row['division_id']."'"));
                while($power  = sqlsrv_fetch_array($powerC)){
                    $transdate2  = date('m-Y',strtotime($power['pcCreateDate']));

                    $getIssuerPermitDetails['AllPowerCuttings'][$pcindex]['pc_id']   = $power['id'];
                    $getIssuerPermitDetails['AllPowerCuttings'][$pcindex]['pc_sl']   = "PC/". $divisionAbb['abbreviation'] ."/". $transdate2 ."/". $power['sl'];
                    $pcindex++;
                }

                $createMonth = date('m-Y', strtotime($row['created_at']));
                $getIssuerPermitDetails['permitsl']   = $divisionAbb['abbreviation'] .'/'.@$createMonth. '/'. $row['serial_no'];
                              
            }
            echo json_encode($getIssuerPermitDetails);
            break;

        case 'viewExistingPowerCutting':
            $pc_id  = $_REQUEST['pc-id'];
            $toReturn = array();
            $toSendArray = array();

            $allCutting = sqlsrv_query($sqlcon,"SELECT id,
                                vlevel,issuer_power,electrical_license_issuer,validity_date_issuer,rec_power,
                                electrical_license_rec,validity_date_rec,created_at,division_id,serial_no,
                                power_cutting_remarks FROM permits WHERE pc_id='$pc_id'");
            $i=0;
            while($permits  = sqlsrv_fetch_array($allCutting)){
                $month  = date('m-Y', strtotime($permits['created_at']));
                $abb    = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT abbreviation from divisions WHERE id ='".$permits['division_id']."'"));
                
                $toReturn['powerCutting'][$i]['permitLevel'] =    $abb['abbreviation'] .'/'. $month .'/'.$permits['serial_no'];
                $toReturn['powerCutting'][$i]['vlevel']      =    $permits['vlevel'];
                $toReturn['powerCutting'][$i]['permitid']    =    $permits['id'];


                if($permits['issuer_power'])
                {
                    $isspower = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT id,name FROM userlogins WHERE id='".$permits['issuer_power']."'"));
                    $toReturn['powerCutting'][$i]['issuser_power_id']   = $isspower['id'];
                    $toReturn['powerCutting'][$i]['issuser_power_name'] = $isspower['name'];
                }
                if($permits['electrical_license_issuer'])
                {
                    $toReturn['powerCutting'][$i]['electrical_license_issuer'] = $permits['electrical_license_issuer'];
                }
                if($permits['validity_date_issuer'])
                {
                    $toReturn['powerCutting'][$i]['validity_date_issuer']    = $permits['validity_date_issuer'];
                }
                if($permits['rec_power'])
                {
                    $recpower = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT id,name FROM userlogins WHERE id='".$permits['rec_power']."'"));
                    $toReturn['powerCutting'][$i]['receiver_power_id']   = $recpower['id'];
                    $toReturn['powerCutting'][$i]['receiver_power_name'] = $recpower['name'];
                }
                if($permits['electrical_license_rec'])
                {
                    $toReturn['powerCutting'][$i]['electrical_license_receiver'] = $permits['electrical_license_rec'];
                }
                if($permits['validity_date_rec'])
                {
                    $toReturn['powerCutting'][$i]['validity_date_receiver']     = $permits['validity_date_rec'];
                }
              
                $powerClearence = sqlsrv_query($sqlcon,"SELECT equipment,positive_isolation_no,
                                        location,box_no,caution_no FROM power_clearences 
                                        WHERE permit_id='".$permits['id']."'");
                $i1 = 0;
                while($powerClS = sqlsrv_fetch_array($powerClearence)){
                    $toReturn['PowerClearence'][$i]['power_clearances'][$i1]['permit_sl_no'] =  $toReturn['powerCutting'][$i]['permitLevel']; 
                    $toReturn['PowerClearence'][$i]['power_clearances'][$i1]['equipment']    =  $powerClS['equipment']; 
                    $toReturn['PowerClearence'][$i]['power_clearances'][$i1]['positive_isolation_no'] =  $powerClS['positive_isolation_no']; 
                    $toReturn['PowerClearence'][$i]['power_clearances'][$i1]['location']     =   $powerClS['location']; 
                    $toReturn['PowerClearence'][$i]['power_clearances'][$i1]['box_no']       =   $powerClS['box_no'];
                    $toReturn['PowerClearence'][$i]['power_clearances'][$i1]['caution_no']   =   $powerClS['caution_no'];
                    $toReturn['PowerClearence'][$i]['powerCuttingRemarks'] =    $permits['power_cutting_remarks'];
                    $i1++; 
                }
                $i++;
            }
            echo json_encode($toReturn);
            break;
        
        case 'updatePowerCutting':
            header("Access-Control-Allow-Origin: *");
            $data = json_decode(file_get_contents('php://input'), true); 

                $permit_id = $data['permit-id'];
                $existing_power_cutting = $data['existing_power_cutting'];
                $myid                      = $data['my-id'];
                $vlevel                    = $data['vlevel'];
                $issuer_power              = $data['issuer_power'];
                $electrical_license_issuer = $data['electrical_license_issuer'];
                $validity_date_issuer      = $data['validity_date_issuer'];
                $rec_power                 = $data['rec_power'];
                $electrical_license_rec    = $data['electrical_license_rec'];
                $validity_date_rec         = $data['validity_date_rec'];
                $comment_power_cutting     = $data['comment_power_cutting'];

                date_default_timezone_set("Asia/Calcutta");   
                $u_dt =  date('Y-m-d H:i:s'); 

                $permitID = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT division_id,department_id FROM permits WHERE id = '$permit_id' AND status='PPc'"));
                if($permitID){
                    if($existing_power_cutting == 'NEW')
                    {
                        $transdate = date('Y-m-d');
                        $month = date('m', strtotime($transdate));
                        $year  = date('Y', strtotime($transdate));

                        // Check Start Generate serial number
                            $divv = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT MAX(sl) as pcsl FROM power_cutting 
                                WHERE YEAR(created_at) = '$year' AND MONTH(created_at) = '$month' 
                                AND division_id='".$permitID['division_id']."' AND type_of_permit ='PC'"));
                            if ($divv['pcsl']){   $v= $divv['pcsl'];$v++;$serial_no=$v;}else{ $serial_no="1"; }
                        // End Generate serial number

                        $pcFirstInsert ="Insert INTO power_cutting(status,sl,division_id,department_id,
                                    user_id,type_of_permit,getting,created_at) 
                                VALUES ('APP','$serial_no','".$permitID['division_id']."','".$permitID['department_id']."',
                                    '$myid','PC','N','$u_dt') SELECT SCOPE_IDENTITY()";
                        // exit;
                        $excute         = sqlsrv_query($sqlcon, $pcFirstInsert);
                        sqlsrv_next_result($excute); 
                        sqlsrv_fetch($excute); 
                        $pcFirstInsertID = sqlsrv_get_field($excute, 0); 

                        $transdate2 = date('m-Y');
                        $slnumber = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT abbreviation FROM divisions WHERE id='".$permitID['division_id']."'"));
                        // Start Generate power cutting
                            $generatesl= 'PC/'.$slnumber['abbreviation'].'/'.$transdate2.'/'.$serial_no;
                        // End Generate power cutting

                            $getPermitData = "UPDATE permits SET status = 'Requested',
                                vlevel = '$vlevel',issuer_power = '$issuer_power',
                                electrical_license_issuer = '$electrical_license_issuer',
                                validity_date_issuer = '$validity_date_issuer',
                                power_clearance_number = '$generatesl',
                                pc_id  = '$pcFirstInsertID',
                                power_cutting_remarks = '$comment_power_cutting',
                                power_cutting_user_dt ='$u_dt' 
                            WHERE id='$permit_id'";
                        $exceuteForPermit      =  sqlsrv_query($sqlcon,$getPermitData);
                        $CUTTINGID    = $pcFirstInsertID;

                    }
                    else{
                        $powercutting = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT division_id,sl FROM power_cutting WHERE id='$existing_power_cutting'"));
                        $transdate2   = date('m-Y');
                        $slnumber     = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT abbreviation FROM divisions WHERE id='".$powercutting['division_id']."'"));
                        //Generate power cutting
                            $generatesl  = 'PC/'.$slnumber['abbreviation'] .'/'.$transdate2.'/'.@$powercutting['sl'];
                        // End Generate power cutting

                        $getPermitData = "UPDATE permits SET status = 'Requested',
                                vlevel = '$vlevel',issuer_power = '$issuer_power',
                                electrical_license_issuer = '$electrical_license_issuer',
                                validity_date_issuer = '$validity_date_issuer',
                                power_clearance_number = '$generatesl',
                                pc_id  = '$existing_power_cutting',
                                power_cutting_remarks = '$comment_power_cutting',
                                power_cutting_user_dt ='$u_dt' 
                                WHERE id='$permit_id'";
                        // echo $getPermitData;exit;
                        $exceuteForPermit      =  sqlsrv_query($sqlcon,$getPermitData);
                        $CUTTINGID    = $existing_power_cutting;
                    }
            
                    $powewrClearenece = $data['PowerClearence']; 
                    foreach($powewrClearenece as $key => $value) {
                            $equipment                  = $value['equipment'];
                            $positive_isolation_no      = $value['positive_isolation_no'];
                            $location                   = $value['location'];
                            $box_no                     = $value['box_no'];
                            $caution_no                 = $value['caution_no'];
                    
                            $powerClS ="Insert INTO power_clearences(permit_id,
                                                power_cutting_id,equipment,positive_isolation_no,
                                                location,box_no,caution_no,created_at) 
                                        VALUES ('$permit_id','$CUTTINGID','$equipment','$positive_isolation_no',
                                            '$location','$box_no','$caution_no','$u_dt')";
                            $runQuery = sqlsrv_query($sqlcon,$powerClS); 
                    }
                    if($excute || $exceuteForPermit && $runQuery){
                        $toReturn['message'] = "Permit Update Suceessfully";
                    }
                    else{
                        $toReturn['message'] = "Ooops... Error While Update Permit";
                    }
                }else{
                    $toReturn['message'] = "Permit Not available for Cutting!!";
                }
            echo json_encode($toReturn); 
            break;

        // for view old time and excuting Details
        case 'viewRenewForWorking':
            $id  =  $_REQUEST['permit-id'];
            $data = array();
            $endDate = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT end_date,renew_id_1,division_id FROM permits WHERE id = '$id'"));
            $ExecutingDivision = sqlsrv_query($sqlcon,"SELECT id,name FROM userlogins WHERE division_id ='".$endDate['division_id']."' AND user_type='1'");
            while($row = sqlsrv_fetch_array($ExecutingDivision)){
                $data['issuer1'][]= $row;
                if(!$endDate['renew_id_1'])
                {
                    $data['end']=$endDate['end_date'];
                }
                else{
                    $endDate = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT new_time FROM renew_permit WHERE permit_id = '$id' AND status ='Approved' ORDER BY id DESC"));
                    $data['end']=$endDate['new_time'];
                }

            }
            echo json_encode($data);
            break;

        // for apply from working Agency Details
        case 'renewApplyFromWorking':
            $permit_id     = $_REQUEST['permit-id'];
            $permitOldTime = $_REQUEST['permit-old-time'];
            $permitNewTime = $_REQUEST['permit-new-time'];
            $executingName = $_REQUEST['executing-name'];

            $old = date('Y-m-d H:i',strtotime($permitOldTime)); 
            $new_time = date('Y-m-d H:i', strtotime($permitNewTime));
            if ($new_time > $old)
            {
                $permit = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT start_date,end_date FROM permits WHERE id = '$permit_id'"));
                $start = strtotime($permit['start_date']);
                $end   = strtotime($permit['end_date']);
                $endDate = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT end_date,renew_id_1 FROM permits WHERE id = '$permit_id'"));
                if(!$endDate['renew_id_1'])
                {
                    $end2=$endDate['end_date'];
                }
                else{
                    $endDate = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT new_time FROM renew_permit WHERE permit_id = '$permit_id' AND status='Approved' ORDER BY id DESC"));
                    $end2=$endDate['new_time'];
                }

                $diffhrs=round((abs($end - $start) / 60)/60,2);
                $diffhrs2=round((abs(strtotime($end2) - $start) / 60)/60,2);

                $allowed=12-$diffhrs2;
                $allowedtillrenewal = strtotime("+".$allowed." hours", strtotime($end2));
                $allowedtillrenewal = date('Y-m-d H:i', $allowedtillrenewal);
                $renewalcount = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT count(id) as renewid FROM renew_permit WHERE permit_id = '$permit_id' AND status='Approved'"));
                // exit;
                
                if ($diffhrs < 12 && $renewalcount['renewid'] < 2 && $new_time <= $allowedtillrenewal)
                {
                    date_default_timezone_set("Asia/Calcutta");
                    $CurrentDT =  date('Y-m-d H:i:s');

                    $InsertQuery = "INSERT INTO renew_permit (permit_id,datetime_apply,old_time,new_time,issuer_id,status,created_at) 
                        VALUES ('$permit_id','$CurrentDT','$old','$new_time','$executingName','Pending_Renew_Issuer','$CurrentDT')";
                    $execute  = sqlsrv_query($sqlcon,$InsertQuery);
                    if($execute){
                        $message['response'] = "Applied For Renewal!";
                    }else{
                        $message['response'] = "Cannot Be Renewed!";
                    }
                }
                else
                {   
                    $message['response'] = "Cannot Be Renewed!";
                }
            }
            else
            {
                $message['response'] = "End Time Should Be Greater Than Start Time!";
            }
            echo json_encode($message);
            break;

        // for view Executing and Owner Agency 
        case 'viewRenewForExecuting':
            $my_id     =  $_REQUEST["my-id"];
            $renew_id  =  $_REQUEST["renew-id"];
            $getRenew  = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT permit_id FROM renew_permit 
                        WHERE id = '$renew_id' AND status != 'Approved'"));
            $permit_id =  $getRenew['permit_id'];
            $queryview = "SELECT permits.id as
                permitid,permits.power_clearance,permits.high_risk,permits.confined_space,
                permits.serial_no,permits.division_id,divisions.name as divname,
                permits.department_id,departments.department_name,
                permits.order_no,permits.order_validity,permits.start_date,permits.end_date,
                permits.job_description,permits.job_location,jobs.id as jobid,
                permits.welding_gas,permits.riggine,permits.working_at_height,
                permits.hydraulic_pneumatic,permits.painting_cleaning,permits.gas,
                permits.others,permits.specify_others,permits.post_site_pic,permits.latlong,
                permits.safe_work,permits.all_person,permits.worker_working,
                permits.all_lifting_tools,permits.all_safety_requirement,
                permits.all_person_are_trained,permits.ensure_the_appplicablle,
                permits.power_clearance_number,permits.area_clearence_required,
                permits.area_clearence_id,jobs.job_title,jobs.swp_number,
                forissuer1.id as issuer1id,forissuer1.name as issuer1name,
                froareaclearence.id as area_cls_id,froareaclearence.name as area_cls_name,
                permits.status,permits.vlevel,permits.issuer_power,
                permits.electrical_license_issuer,permits.validity_date_issuer,
                permits.rec_power,permits.electrical_license_rec,permits.validity_date_rec,
                permits.created_at,divisions.abbreviation,permits.s_instruction,
                permits.other_isolation,renew_permit.status as renewStatus,renew_permit.area_id
                FROM permits
                    LEFT JOIN userlogins as forissuer1 ON permits.issuer_id =  forissuer1.id
                    LEFT JOIN userlogins as froareaclearence ON permits.area_clearence_id = froareaclearence.id
                    LEFT JOIN divisions ON permits.division_id      = divisions.id
                    LEFT JOIN departments ON permits.department_id  = departments.id
                    LEFT JOIN jobs ON permits.job_id                = jobs.id
                    LEFT JOIN renew_permit ON permits.id            = renew_permit.permit_id
                WHERE  (renew_permit.issuer_id = '$my_id' AND permits.id = '$permit_id' AND renew_permit.status='Pending_Renew_Issuer') 
                    OR (renew_permit.area_id = '$my_id' AND permits.id = '$permit_id' AND renew_permit.status='Pending_Renew_Area')";
            // echo $queryview;
            // exit;
            $fetch_query1 = sqlsrv_query($sqlcon, $queryview);
            $getIssuerPermitDetails = array();
            while($row = sqlsrv_fetch_array($fetch_query1))
            {
                $getIssuerPermitDetails['permitid']     = $row['permitid'];
                $getIssuerPermitDetails['divisionId']   = $row['division_id'];
                $getIssuerPermitDetails['divisionName'] = $row['divname'];
                $getIssuerPermitDetails['departmentId'] = $row['department_id'];
                $getIssuerPermitDetails['departmentName'] = $row['department_name'];
                $getIssuerPermitDetails['orderNumber']    = $row['order_no'];
                $getIssuerPermitDetails['orderValidity']  = $row['order_validity'];
                $getIssuerPermitDetails['startDate']      = $row['start_date'];
                $getIssuerPermitDetails['endDate']        = $row['end_date'];
                $getIssuerPermitDetails['jobDescription'] = $row['job_description'];
                $getIssuerPermitDetails['jobLocation']    = $row['job_location'];
                $getIssuerPermitDetails['jobId']          = $row['jobid'];
                $jobid                                    = $row['jobid'];
                $getIssuerPermitDetails['job_title']      = $row['job_title'];
                $getIssuerPermitDetails['swp_number']     = $row['swp_number'];

                $swpfile = "SELECT swp_file FROM swp_files WHERE job_id='$jobid'";
                $runQueryswp   = sqlsrv_query($sqlcon, $swpfile);
                $li0 = 0;
                while($rowswp=sqlsrv_fetch_array($runQueryswp))
                {
                    $getIssuerPermitDetails['SwpFile'][$li0]['swp_file']    = $rowswp['swp_file'];
                    $li0++;
                }

                $getIssuerPermitDetails['weldingGas']         =  $row['welding_gas'];
                $getIssuerPermitDetails['riggine']            =  $row['riggine'];
                $getIssuerPermitDetails['workingAtHeight']    =  $row['working_at_height'];
                $getIssuerPermitDetails['hydraulicPneumatic'] =  $row['hydraulic_pneumatic'];
                $getIssuerPermitDetails['paintingCleaning']   =  $row['painting_cleaning'];
                $getIssuerPermitDetails['gas']                =  $row['gas'];
                $getIssuerPermitDetails['others']             =  $row['others'];
                $getIssuerPermitDetails['specifyOthers']      =  $row['specify_others'];

                $getHazard = "SELECT dir,hazard,precaution FROM permit_hazards WHERE permit_id='$permit_id'";
                $runQuery1   = sqlsrv_query($sqlcon, $getHazard);
                $li1=0;
                while($row2=sqlsrv_fetch_array($runQuery1))
                {
                    $getIssuerPermitDetails['SixDirection'][$li1]['dir']        = $row2['dir'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['hazard']     = $row2['hazard'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['precaution'] = $row2['precaution'];
                    $li1++;
                }

                $getIssuerPermitDetails['issuer1id'] = $row['issuer1id'];
                $getIssuerPermitDetails['issuer1name'] = $row['issuer1name'];

                $getGatePass = "SELECT employee_name,gate_pass_no,designation,age,expirydate FROM gate_pass_details WHERE permit_id='$permit_id'";
                $runQuery2   = sqlsrv_query($sqlcon, $getGatePass);
                $li2=0;
                while($row3=sqlsrv_fetch_array($runQuery2))
                {
                    $getIssuerPermitDetails['Empolyee'][$li2]['emp_name']        = $row3['employee_name'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['g_pass_number']   = $row3['gate_pass_no'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['design']          = $row3['designation'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['age']             = $row3['age'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['expirydate']      = $row3['expirydate'];
                    $li2++;
                }

                $getIssuerPermitDetails['safe_work']            = $row['safe_work'];
                $getIssuerPermitDetails['all_person']           = $row['all_person'];
                $getIssuerPermitDetails['worker_working']       = $row['worker_working'];
                $getIssuerPermitDetails['all_lifting_tools']    = $row['all_lifting_tools'];
                $getIssuerPermitDetails['all_safety_requirement']  = $row['all_safety_requirement'];
                $getIssuerPermitDetails['all_person_are_trained']  = $row['all_person_are_trained'];
                $getIssuerPermitDetails['ensure_the_appplicablle'] = $row['ensure_the_appplicablle'];
                $getIssuerPermitDetails['status']                  = $row['status'];
                $getIssuerPermitDetails['post_site_pic']           = $row['post_site_pic'];
                $getIssuerPermitDetails['power_clearance']         = $row['power_clearance'];
                $getIssuerPermitDetails['vlevel']                  = $row['vlevel'];
                $powerClearenceIssuer = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$row['issuer_power']."'"));
                $getIssuerPermitDetails['issuer_power_name']          = $powerClearenceIssuer['name'];
                $getIssuerPermitDetails['electrical_license_issuer']  = $row['electrical_license_issuer'];
                $getIssuerPermitDetails['validity_date_issuer']       = $row['validity_date_issuer'];
                $getIssuerPermitDetails['power_clearance_number']     = $row['power_clearance_number'];
                
                $powerClearence = sqlsrv_query($sqlcon,"SELECT equipment,positive_isolation_no,
                                        location,box_no,caution_no FROM power_clearences 
                                        WHERE permit_id='$permit_id'");
                $index = 0;
                while($powerClS = sqlsrv_fetch_array($powerClearence)){

                    $getIssuerPermitDetails['PowerClearence'][$index]['equipment']    =  $powerClS['equipment']; 
                    $getIssuerPermitDetails['PowerClearence'][$index]['positive_isolation_no'] =  $powerClS['positive_isolation_no']; 
                    $getIssuerPermitDetails['PowerClearence'][$index]['location']     =   $powerClS['location']; 
                    $getIssuerPermitDetails['PowerClearence'][$index]['box_no']       =   $powerClS['box_no'];
                    $getIssuerPermitDetails['PowerClearence'][$index]['caution_no']   =   $powerClS['caution_no'];
                    $index++; 
                }

                $getIssuerPermitDetails['other_isolation']    =  $row['other_isolation']; 
                $otherIsolationQuery = "SELECT positive_other,equipment_other,location_other FROM other_isolation WHERE permit_id='$permit_id'";
                $runIsolation   = sqlsrv_query($sqlcon, $otherIsolationQuery);
                $index2 = 0;
                while($OtherIsolation =sqlsrv_fetch_array($runIsolation))
                {
                    $getIssuerPermitDetails['OtherIsolation'][$index2]['positive_other']   = $OtherIsolation['positive_other'];
                    $getIssuerPermitDetails['OtherIsolation'][$index2]['equipment_other']  = $OtherIsolation['equipment_other'];
                    $getIssuerPermitDetails['OtherIsolation'][$index2]['location_other']   = $OtherIsolation['location_other'];
                    $index2++;
                }


                $getIssuerPermitDetails['confined_space'] =  $row['confined_space']; 
                $getconfined = "SELECT clearance_no,depth,location FROM confined_spaces WHERE permit_id='$permit_id'";
                $runQuery4   = sqlsrv_query($sqlcon, $getconfined);
                $index3 = 0;
                while($ConfinedSpace = sqlsrv_fetch_array($runQuery4))
                {
                    $getIssuerPermitDetails['ConfinedSpace'][$index3]['clearance_no']   = $ConfinedSpace['clearance_no'];
                    $getIssuerPermitDetails['ConfinedSpace'][$index3]['depth']          = $ConfinedSpace['depth'];
                    $getIssuerPermitDetails['ConfinedSpace'][$index3]['location']       = $ConfinedSpace['location'];
                    $index3++;
                }
                $getIssuerPermitDetails['high_risk']     = $row['high_risk'];
                $forAreaClearence = sqlsrv_query($sqlcon,"SELECT id,name FROM userlogins WHERE division_id='".$row['division_id']."'");
                $indexOG = 0;
                while($owners = sqlsrv_fetch_array($forAreaClearence))
                {
                    $getIssuerPermitDetails['ownerAgencyList'][$indexOG]['id']  =  $owners['id'];
                    $getIssuerPermitDetails['ownerAgencyList'][$indexOG]['name'] = $owners['name'];
                    $indexOG++;
                }
                $getIssuerPermitDetails['renewstatus'] = $row['renewStatus'];
                if($row['renewStatus'] == "Pending_Renew_Area"){
                    $areaname = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT id,name FROM userlogins WHERE id='".$row['area_id']."'"));
                    $getIssuerPermitDetails['new_area_name'] = $areaname['name'];
                }

            }
            echo json_encode($getIssuerPermitDetails);
            break;

        // for Both apply From Executing and Owner Agency 
        case 'renewApplyFromExecuting':   
            $renew_id  = $_REQUEST['renew_id'];
            $area_clearance_req  = $_REQUEST['area_clearance_req'];
            $area_clearence_id   = $_REQUEST['area_clearence_id'];

            date_default_timezone_set("Asia/Calcutta");
            $CurrentDT =  date('Y-m-d H:i:s');

            $renewStatus  = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT status,permit_id FROM renew_permit WHERE id = '$renew_id'"));
            $permitCheck1 = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT renew_id_1 FROM permits WHERE id ='".$renewStatus['permit_id']."'"));

            if($permitCheck1['renew_id_1'])
            {
                $val="renew_id_2";
            }
            else
            {
                $val="renew_id_1";
            }

            if($renewStatus['status'] == 'Pending_Renew_Issuer' && $area_clearance_req == 'on')
            {
                $queryMake = "UPDATE renew_permit SET 
                                issuer_confirm_dt = '$CurrentDT',
                                area_id = '$area_clearence_id',
                                status = 'Pending_Renew_Area'
                                WHERE id='$renew_id'";

                $exceuteQuery     =  sqlsrv_query($sqlcon,$queryMake);
            }
            elseif($renewStatus['status'] == 'Pending_Renew_Issuer' && $area_clearance_req != 'on'){
                $queryMake = "UPDATE renew_permit SET 
                            issuer_confirm_dt = '$CurrentDT',
                            status = 'Approved'
                        WHERE id='$renew_id'";
                $exceuteQuery     =  sqlsrv_query($sqlcon,$queryMake);

                $permits = sqlsrv_query($sqlcon,"UPDATE permits SET $val = '$renew_id' WHERE id='".$renewStatus['permit_id']."'");
            
            }
            elseif($renewStatus['status'] == 'Pending_Renew_Area'){
                $queryMake = "UPDATE renew_permit SET 
                        area_confirm_dt = '$CurrentDT',status = 'Approved'
                        WHERE id='$renew_id'";
                $exceuteQuery     =  sqlsrv_query($sqlcon,$queryMake);

                $permits = sqlsrv_query($sqlcon,"UPDATE permits SET $val = '$renew_id' WHERE id='".$renewStatus['permit_id']."'");
            }
            if($exceuteQuery){
                $toReturn['message']  = "Approved!!";
            }
            else{
                $toReturn['message']  = "Error in Approved!!";
            }
            echo json_encode($toReturn);
            break;

        case 'violationCancel':
            date_default_timezone_set("Asia/Calcutta");
            $cancelDate =  date('Y-m-d H:i:s');

            $pid                    = $_REQUEST["permit-id"];
            $violations_details     = $_REQUEST["violation-details"];
            $img1                   = $_REQUEST["img1"];
            $img2                   = $_REQUEST["img2"];
            $img3                   = $_REQUEST["img3"];
            $cancelDate             = $cancelDate;
            $c_id                   = $_REQUEST["cancel-id"];

            // insert code for cancel the permit
            $cancelledQuery  = "INSERT INTO permit_cancels (permit_id,date,violations_details,img1,img2,img3,cancel_by_id)   
                    VALUES ('$pid','$cancelDate','$violations_details','$img1','$img2','$img3','$c_id')";
            // echo $cancelledQuery;
            $executeQuery = sqlsrv_query($sqlcon, $cancelledQuery);

            // update the permit status
            $updatep = "UPDATE permits SET  
                            status = 'Cancel'
                            WHERE id ='$pid'"; 
            // echo $updatep;

            $executeQuerysts = sqlsrv_query($sqlcon, $updatep);
            // exit; 

            if($executeQuery && $executeQuerysts){
                $message['response'] = "Permit Cancelled!!";
                echo json_encode($message); 
            }
            else{
                $message['response'] = "Permit Not Cancel";
                echo json_encode($message);
            }
            break;

        case 'viewReturn':
            $my_id     =  $_REQUEST["my-id"];
            $permit_id =  $_REQUEST["permit-id"];
            $queryview = "SELECT permits.id as
                    permitid,permits.power_clearance,permits.high_risk,permits.confined_space,
                    permits.serial_no,permits.division_id,divisions.name as divname,
                    permits.department_id,departments.department_name,
                    permits.order_no,permits.order_validity,permits.start_date,permits.end_date,
                    permits.job_description,permits.job_location,jobs.id as jobid,
                    permits.welding_gas,permits.riggine,permits.working_at_height,
                    permits.hydraulic_pneumatic,permits.painting_cleaning,permits.gas,
                    permits.others,permits.specify_others,permits.post_site_pic,permits.latlong,
                    permits.safe_work,permits.all_person,permits.worker_working,
                    permits.all_lifting_tools,permits.all_safety_requirement,
                    permits.all_person_are_trained,permits.ensure_the_appplicablle,
                    permits.power_clearance_number,permits.area_clearence_required,
                    permits.area_clearence_id,jobs.job_title,jobs.swp_number,forissuer1.id as issuer1id,
                    forissuer1.name as issuer1name,froareaclearence.id as area_cls_id,
                    froareaclearence.name as area_cls_name,permits.status,permits.vlevel,
                    permits.issuer_power,permits.electrical_license_issuer,permits.validity_date_issuer,
                    permits.rec_power,permits.electrical_license_rec,permits.validity_date_rec,
                    permits.created_at,divisions.abbreviation,permits.s_instruction,
                    permits.power_cutting_remarks,permits.executing_lock,permits.working_lock,
                    permits.other_isolation,permits.complete,permits.requester_remark,permits.pg_ins1,
                    permits.pg_ins2,permits.pg_ins3,permits.ppg_userid,permits.pg_number,permits.pg_id,
                    permits.exe_lock,permits.work_lock,permits.q1,permits.q2,permits.q3,permits.q4,
                    permits.q5_others,permits.issuer_remark,permits.area_return_remark,permits.return_status
                FROM permits
                    LEFT JOIN userlogins as forissuer1 ON permits.issuer_id =  forissuer1.id
                    LEFT JOIN userlogins as froareaclearence ON permits.area_clearence_id = froareaclearence.id
                    LEFT JOIN divisions ON permits.division_id      = divisions.id
                    LEFT JOIN departments ON permits.department_id  = departments.id
                    LEFT JOIN jobs ON permits.job_id                = jobs.id
                WHERE  permits.entered_by = '$my_id' AND permits.id = '$permit_id'
                    OR permits.issuer_id  = '$my_id' AND permits.id = '$permit_id' AND permits.return_status='Pending'
                    OR permits.area_clearence_id  = '$my_id' AND permits.id = '$permit_id' AND permits.return_status='Pending_area'";
            

            $fetch_query1 = sqlsrv_query($sqlcon, $queryview);
            $getIssuerPermitDetails = array();
            while($row = sqlsrv_fetch_array($fetch_query1))
            {
                $getIssuerPermitDetails['permitid']     = $row['permitid'];
                $getIssuerPermitDetails['divisionId']   = $row['division_id'];
                $getIssuerPermitDetails['divisionName'] = $row['divname'];
                $getIssuerPermitDetails['departmentId'] = $row['department_id'];
                $getIssuerPermitDetails['departmentName'] = $row['department_name'];
                $getIssuerPermitDetails['orderNumber']    = $row['order_no'];
                $getIssuerPermitDetails['orderValidity']  = $row['order_validity'];
                $getIssuerPermitDetails['startDate']      = $row['start_date'];
                $getIssuerPermitDetails['endDate']        = $row['end_date'];
                $getIssuerPermitDetails['jobDescription'] = $row['job_description'];
                $getIssuerPermitDetails['jobLocation']    = $row['job_location'];
                $getIssuerPermitDetails['jobId']          = $row['jobid'];
                $jobid                                    = $row['jobid'];
                $getIssuerPermitDetails['job_title']      = $row['job_title'];
                $getIssuerPermitDetails['swp_number']     = $row['swp_number'];
                $getIssuerPermitDetails['return_status']  = $row['return_status'];

                $swpfile = "SELECT swp_file FROM swp_files WHERE job_id='$jobid'";
                $runQueryswp   = sqlsrv_query($sqlcon, $swpfile);
                $li0 =0;
                while($rowswp=sqlsrv_fetch_array($runQueryswp))
                {
                    $getIssuerPermitDetails['SwpFile'][$li0]['swp_file']    = $rowswp['swp_file'];
                    $li0++;
                }

                $getIssuerPermitDetails['weldingGas']         =  $row['welding_gas'];
                $getIssuerPermitDetails['riggine']            =  $row['riggine'];
                $getIssuerPermitDetails['workingAtHeight']    =  $row['working_at_height'];
                $getIssuerPermitDetails['hydraulicPneumatic'] =  $row['hydraulic_pneumatic'];
                $getIssuerPermitDetails['paintingCleaning']   =  $row['painting_cleaning'];
                $getIssuerPermitDetails['gas']                =  $row['gas'];
                $getIssuerPermitDetails['others']             =  $row['others'];
                $getIssuerPermitDetails['specifyOthers']      =  $row['specify_others'];

                $getHazard = "SELECT dir,hazard,precaution FROM permit_hazards WHERE permit_id='$permit_id'";
                $runQuery1   = sqlsrv_query($sqlcon, $getHazard);
                $li1=0;
                while($row2=sqlsrv_fetch_array($runQuery1))
                {
                    $getIssuerPermitDetails['SixDirection'][$li1]['dir']        = $row2['dir'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['hazard']     = $row2['hazard'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['precaution'] = $row2['precaution'];
                    $li1++;
                }


                $getIssuerPermitDetails['issuer1id'] = $row['issuer1id'];
                $getIssuerPermitDetails['issuer1name'] = $row['issuer1name'];

                $getGatePass = "SELECT employee_name,gate_pass_no,designation,age,expirydate FROM gate_pass_details WHERE permit_id='$permit_id'";
                $runQuery2   = sqlsrv_query($sqlcon, $getGatePass);
                $li2=0;
                while($row3=sqlsrv_fetch_array($runQuery2))
                {
                    $getIssuerPermitDetails['Empolyee'][$li2]['emp_name']        = $row3['employee_name'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['g_pass_number']   = $row3['gate_pass_no'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['design']          = $row3['designation'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['age']             = $row3['age'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['expirydate']      = $row3['expirydate'];
                    $li2++;

                }

                $getIssuerPermitDetails['safe_work']            = $row['safe_work'];
                $getIssuerPermitDetails['all_person']           = $row['all_person'];
                $getIssuerPermitDetails['worker_working']       = $row['worker_working'];
                $getIssuerPermitDetails['all_lifting_tools']    = $row['all_lifting_tools'];
                $getIssuerPermitDetails['all_safety_requirement']  = $row['all_safety_requirement'];
                $getIssuerPermitDetails['all_person_are_trained']  = $row['all_person_are_trained'];
                $getIssuerPermitDetails['ensure_the_appplicablle'] = $row['ensure_the_appplicablle'];
                $getIssuerPermitDetails['status']                  = $row['status'];
                $getIssuerPermitDetails['post_site_pic']           = $row['post_site_pic'];
                $getIssuerPermitDetails['latlong'] = $row['latlong'];
                $getIssuerPermitDetails['power_clearance']         = $row['power_clearance'];
                $getIssuerPermitDetails['power_clearance_number']  = $row['power_clearance_number'];
                $getIssuerPermitDetails['vlevel']                  = $row['vlevel'];
                $getIssuerPermitDetails['issuer_power']            = $row['issuer_power'];

                if($getIssuerPermitDetails['issuer_power'] != ''){
                    $IssuerName = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$getIssuerPermitDetails['issuer_power']."'"));
                    $getIssuerPermitDetails['powerIssuerName'] = $IssuerName['name'];
                }
                $getIssuerPermitDetails['electrical_license_issuer']    = $row['electrical_license_issuer'];
                $getIssuerPermitDetails['validity_date_issuer']         = $row['validity_date_issuer'];
                
                $getIssuerPermitDetails['rec_power']                    = $row['rec_power'];
                if($getIssuerPermitDetails['rec_power'] != ''){
                    $ReceriverName = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$getIssuerPermitDetails['rec_power']."'"));
                    $getIssuerPermitDetails['powerReceiverName'] = $ReceriverName['name'];
                }
                $getIssuerPermitDetails['electrical_license_rec']       = $row['electrical_license_rec'];
                $getIssuerPermitDetails['validity_date_rec']            = $row['validity_date_rec'];
                $getIssuerPermitDetails['power_cutting_remarks']           = $row['power_cutting_remarks'];
                
                $getPowercls = "SELECT positive_isolation_no,equipment,
                                    location,box_no,caution_no FROM power_clearences WHERE permit_id='$permit_id'";
                $runQuery3   = sqlsrv_query($sqlcon, $getPowercls);
                $li3 = 0;
                while($row4=sqlsrv_fetch_array($runQuery3))
                {
                    $getIssuerPermitDetails['PowerClearences'][$li3]['positive_isolation_no'] = $row4['positive_isolation_no'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['equipment']        = $row4['equipment'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['location']         = $row4['location'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['box_no']           = $row4['box_no'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['caution_no']       = $row4['caution_no'];
                    $li3++;
                }
                $getIssuerPermitDetails['executing_lock']             = $row['executing_lock'];
                $getIssuerPermitDetails['working_lock']               = $row['working_lock'];

                $getIssuerPermitDetails['other_isolation']            =  $row['other_isolation']; 
                $otherIsolationQuery = "SELECT positive_other,equipment_other,location_other FROM other_isolation WHERE permit_id='$permit_id'";
                $runIsolation   = sqlsrv_query($sqlcon, $otherIsolationQuery);
                $li4 = 0;
                while($OtherIsolation =sqlsrv_fetch_array($runIsolation))
                {
                    $getIssuerPermitDetails['OtherIsolation'][$li4]['positive_other']   = $OtherIsolation['positive_other'];
                    $getIssuerPermitDetails['OtherIsolation'][$li4]['equipment_other']  = $OtherIsolation['equipment_other'];
                    $getIssuerPermitDetails['OtherIsolation'][$li4]['location_other']   = $OtherIsolation['location_other'];
                    $li4++;
                }
                $getIssuerPermitDetails['high_risk']               = $row['high_risk'];

                $getIssuerPermitDetails['area_cls_id']   = $row['area_cls_id'];
                $getIssuerPermitDetails['area_cls_name'] = $row['area_cls_name'];
                $getIssuerPermitDetails['s_instruction'] = $row['s_instruction'];

                $getIssuerPermitDetails['area_clearence_required'] = $row['area_clearence_required'];
              
                $getIssuerPermitDetails['confined_space'] = $row['confined_space'];
                $getconfined = "SELECT clearance_no,depth,location FROM confined_spaces WHERE permit_id='$permit_id'";
                $runQuery4   = sqlsrv_query($sqlcon, $getconfined);
                $key1 =0;
                while($row5=sqlsrv_fetch_array($runQuery4))
                {
                    $getIssuerPermitDetails['ConfinedSpace'][$key1]['clearence_num']   = $row5['clearance_no'];
                    $getIssuerPermitDetails['ConfinedSpace'][$key1]['depth']          = $row5['depth'];
                    $getIssuerPermitDetails['ConfinedSpace'][$key1]['location']       = $row5['location'];
                    $key1++;
                }
                $getIssuerPermitDetails['complete']         = $row['complete'];
                $getIssuerPermitDetails['requester_remark'] = $row['requester_remark'];
                $getIssuerPermitDetails['pg_ins1'] = $row['pg_ins1'];
                $getIssuerPermitDetails['pg_ins2'] = $row['pg_ins2'];
                $getIssuerPermitDetails['pg_ins3'] = $row['pg_ins3'];

                $listPowerGetting = sqlsrv_query($sqlcon,"SELECT id,name 
                    FROM userlogins 
                        WHERE division_id='".$row['division_id']."' 
                        AND user_type='1' AND power_getting='Yes'");
                $list =0;
                while($listuser =sqlsrv_fetch_array($listPowerGetting))
                {
                    $getIssuerPermitDetails['pgetting'][$list]['id']   = $listuser['id'];
                    $getIssuerPermitDetails['pgetting'][$list]['name'] = $listuser['name'];
                    $list++;
                }

                // $getIssuerPermitDetails['area_cls_name'] = $row['area_cls_name'];
                $powerGet = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$row['ppg_userid']."'"));
                $getIssuerPermitDetails['powerGettingUserName'] = $powerGet['name'];
                $getIssuerPermitDetails['powerGettingSl'] = $row['pg_number'];
                $powerGettingComment = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT power_cutting_comment FROM power_getting WHERE id='".$row['pg_id']."'"));
                $getIssuerPermitDetails['power_getting_comment']  = $powerGettingComment['power_cutting_comment'];
                
                $getIssuerPermitDetails['exe_lock']  = $row['exe_lock'];
                $getIssuerPermitDetails['work_lock'] = $row['work_lock'];
                $getIssuerPermitDetails['q1']  = $row['q1'];
                $getIssuerPermitDetails['q2']  = $row['q2'];
                $getIssuerPermitDetails['q3']  = $row['q3'];
                $getIssuerPermitDetails['q4']  = $row['q4'];
                $getIssuerPermitDetails['q5_others']  = $row['q5_others'];
                $getIssuerPermitDetails['issuer_remark']  = $row['issuer_remark'];
                $getIssuerPermitDetails['area_return_remark']  = $row['area_return_remark'];
                

            }
            echo json_encode($getIssuerPermitDetails);
            break;
        
        case 'update-Return':
            date_default_timezone_set("Asia/Calcutta");
            $currentTime =  date('Y-m-d H:i:s');

            $permit_id         =  $_REQUEST["permit-id"];
            $owner_check       =  $_REQUEST["owner-check"];
            $complete1         =  $_REQUEST["complete1"];
            $requester_remark  =  $_REQUEST["requester_remark"];
            $power_getting_userid  =  $_REQUEST["power_getting_userid"];
            $ins1                  =  $_REQUEST["ins1"];
            $ins2                  =  $_REQUEST["ins2"];
            $ins3                  =  $_REQUEST["ins3"];
          

            $getStatus = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT power_clearance,
                            return_status FROM permits WHERE id='$permit_id'"));

            if($getStatus['return_status'] == 'Pending'){
                $issuer_remark     =  $_REQUEST["issuer_remark"];
            }
            elseif($getStatus['return_status'] == 'Pending_area'){
                $area_remark       =  $_REQUEST["issuer_remark"];
            }



            ($owner_check == 'on') ? $OwnerCheck = 'on' : $OwnerCheck = 'off';

            if($getStatus['power_clearance'] == 'on'){
                if($getStatus['return_status'] == '')
                {
                    //echo "1";
                    $cancel = "UPDATE permits 
                            SET complete         = '$complete1',
                                requester_remark = '$requester_remark',
                                ppg_userid       = '$power_getting_userid',
                                pg_ins1          = '$ins1',
                                pg_ins2          = '$ins2',
                                pg_ins3          = '$ins3',
                                complete_date    = '$currentTime',
                                return_status    = 'PPg'
                            WHERE id='$permit_id'";
                    $execute  = sqlsrv_query($sqlcon,$cancel);
                    // return_issuer_id  = $request->return_executing_id,
                }
            }
            if($getStatus['power_clearance'] == 'off')
            {
                if($getStatus['return_status'] == '')
                {  // echo "2";
                    $cancel = "UPDATE permits 
                            SET complete         = '$complete1',
                                requester_remark = '$requester_remark',
                                pg_ins1          = '$ins1',
                                pg_ins2          = '$ins2',
                                pg_ins3          = '$ins3',
                                complete_date    = '$currentTime',
                                return_status    = 'Pending'
                            WHERE id='$permit_id'";
                    $execute  = sqlsrv_query($sqlcon,$cancel);
                    // 'return_issuer_id'  => $request->return_executing_id,
                }
            }
            if($getStatus['return_status'] == 'Pending'  &&  $OwnerCheck == 'off')
            {
                //echo "3";
                $cancel = "UPDATE permits 
                            SET issuer_return_date     = '$currentTime',
                                issuer_remark          = '$issuer_remark',
                                return_status    = 'Returned',
                                status           = 'Returned'
                            WHERE id='$permit_id'";
                $execute  = sqlsrv_query($sqlcon,$cancel);
            }
            elseif($getStatus['return_status'] == 'Pending'  &&  $OwnerCheck == 'on')
            {
                //echo "4";
                $cancel = "UPDATE permits 
                            SET issuer_return_date     = '$currentTime',
                                issuer_remark          = '$issuer_remark',
                                return_status    = 'Pending_area'
                            WHERE id='$permit_id'";
                $execute  = sqlsrv_query($sqlcon,$cancel);
            }
            elseif($getStatus['return_status'] == 'Pending_area')
            {
                //echo "5";
                $cancel = "UPDATE permits 
                            SET area_return_date     = '$currentTime',
                                area_return_remark   = '$area_remark',
                                return_status        = 'Returned',
                                status               = 'Returned'
                            WHERE id='$permit_id'";
                $execute  = sqlsrv_query($sqlcon,$cancel);
            }

            if($execute){
                $message['message'] = "Permit Returned !!!";
            }
            else{
                $message['message'] = "Ooops... Error While Return Permit !!!";
            }
            echo json_encode($message); 
            break;  

        case 'view-power-getting':
            $permit_id         =  $_REQUEST["permit-id"];
            $permits = "SELECT id,vlevel,issuer_power,electrical_license_issuer,
                validity_date_issuer,rec_power,electrical_license_rec,
                validity_date_rec,created_at,division_id,serial_no,
                power_cutting_remarks,power_clearance_number,pg_ins1,pg_ins2,
                pg_ins3,return_status,pc_id FROM permits WHERE id='$permit_id'";
            $fetchPermit   = sqlsrv_query($sqlcon, $permits);
            $getIssuerPermitDetails = array(); 
            $key1 =0;

            while($eachByEach =sqlsrv_fetch_array($fetchPermit))
            {
                $getIssuerPermitDetails['power_clearance_number']   = $eachByEach['power_clearance_number'];
                $getIssuerPermitDetails['issuer_power']             = $eachByEach['issuer_power'];
                $getIssuerPermitDetails['vlevel']             = $eachByEach['vlevel'];


                if($getIssuerPermitDetails['issuer_power'] != ''){
                    $IssuerName = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$getIssuerPermitDetails['issuer_power']."'"));
                    $getIssuerPermitDetails['powerIssuerName'] = $IssuerName['name'];
                }
                $getIssuerPermitDetails['electrical_license_issuer']  = $eachByEach['electrical_license_issuer'];
                $getIssuerPermitDetails['validity_date_issuer']       = $eachByEach['validity_date_issuer'];
                $getIssuerPermitDetails['pc_id']   = $eachByEach['pc_id'];

                // Serial No.
                $month  = date('m-Y', strtotime($eachByEach['created_at']));
                $abb = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT * FROM divisions WHERE id='".$eachByEach['division_id']."'"));
                $oldPSerial = @$abb['abbreviation'] .'/'. $month .'/'.$eachByEach['serial_no'];
                

                $powerClearence = sqlsrv_query($sqlcon,"SELECT equipment,positive_isolation_no,
                                        location,box_no,caution_no FROM power_clearences 
                                        WHERE permit_id='$permit_id'");
                $i1 = 0;
                while($powerClS = sqlsrv_fetch_array($powerClearence)){
                    $getIssuerPermitDetails['PowerClearence'][$i1]['permit_sl_no'] =  $oldPSerial; 
                    $getIssuerPermitDetails['PowerClearence'][$i1]['equipment']    =  $powerClS['equipment']; 
                    $getIssuerPermitDetails['PowerClearence'][$i1]['positive_isolation_no'] =  $powerClS['positive_isolation_no']; 
                    $getIssuerPermitDetails['PowerClearence'][$i1]['location']     =   $powerClS['location']; 
                    $getIssuerPermitDetails['PowerClearence'][$i1]['box_no']       =   $powerClS['box_no'];
                    $getIssuerPermitDetails['PowerClearence'][$i1]['caution_no']   =   $powerClS['caution_no'];
                    $i1++; 
                }
                $getIssuerPermitDetails['power_cutting_remarks']       = $eachByEach['power_cutting_remarks'];
                $getIssuerPermitDetails['pg_ins1']       = $eachByEach['pg_ins1'];
                $getIssuerPermitDetails['pg_ins2']       = $eachByEach['pg_ins2'];
                $getIssuerPermitDetails['pg_ins3']       = $eachByEach['pg_ins3'];
                $getIssuerPermitDetails['personal_lock_remove']       = "Yes";
                $getIssuerPermitDetails['status']       = "Permit Returned";
            }

            echo json_encode($getIssuerPermitDetails);
            break;
        case 'apply-power-getting':
            date_default_timezone_set("Asia/Calcutta");
            $u_dt =  date('Y-m-d H:i:s');
            $permit_id       = $_REQUEST['permit-id'];
            $powerCuttingID  = $_REQUEST['power-cutting-id'];
            $user_id         = $_REQUEST['user-id'];
            $comment_power_cutting   = $_REQUEST['comment-power-cutting'];
            $rec_power               = $_REQUEST['rec_power'];
            $electrical_license_rec  = $_REQUEST['electrical_license_rec'];
            $validity_date_rec       = $_REQUEST['validity_date_rec'];
            $exe_lock                = $_REQUEST['exe_lock'];
            $work_lock               = $_REQUEST['work_lock'];
            $q1                      = $_REQUEST['q1'];
            $q2                      = $_REQUEST['q2'];
            $q3                      = $_REQUEST['q3'];
            $q4                      = $_REQUEST['q4'];
            $q5_others               = $_REQUEST['q5_others'];

            // FOR SL GETTING FROM POWER GETTING TABLE
            $transdate = date('Y-m-d');
            $month = date('m', strtotime($transdate));
            $year  = date('Y', strtotime($transdate));
            
            $divv = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT MAX(sl) as pgsl FROM power_getting WHERE YEAR(created_at)='$year' AND MONTH(created_at)='$month'"));
            if ($divv['pgsl'])
            {
                $v = $divv['pgsl'];
                $v++;
                $serial_no=$v;
            }
            else{
                $serial_no="1"; 
            }

            $powerGetting ="Insert INTO power_getting(permit_id,power_cutting_id,user_id,sl,
                        power_cutting_comment,created_at) 
                VALUES ('$permit_id','$powerCuttingID','$user_id','$serial_no',
                    '$comment_power_cutting','$u_dt') SELECT SCOPE_IDENTITY()";

            $excute         = sqlsrv_query($sqlcon, $powerGetting);
            sqlsrv_next_result($excute); 
            sqlsrv_fetch($excute); 
            $arrayPowerGetting = sqlsrv_get_field($excute, 0); 


            $pDiv = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT division_id FROM permits WHERE id='$permit_id'"));
            $transdate2 = date('m-Y');
            $abb = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT abbreviation FROM divisions WHERE id='".$pDiv['division_id']."'"));
            $generatesl = 'PG/'.$abb['abbreviation'].'/'.$transdate2.'/'.$serial_no;


            $getPermitData = "UPDATE permits 
                        SET return_status = 'Pending',
                            pg_action_dt  = '$u_dt',
                            pg_number     = '$generatesl',
                            pg_id         = '$arrayPowerGetting',
                            rec_power     = '$rec_power',
                            electrical_license_rec = '$electrical_license_rec',
                            validity_date_rec      = '$validity_date_rec',
                            exe_lock      = '$exe_lock',
                            work_lock     = '$work_lock',
                            q1            = '$q1',
                            q2            = '$q2',
                            q3            = '$q3',
                            q4            = '$q4',
                            q5_others     = '$q5_others'
                            WHERE id='$permit_id'";
            $arrayPermit      =  sqlsrv_query($sqlcon,$getPermitData);

                
            if($excute && $arrayPermit){
                $message['response'] = 'Permit Returned!';
            }
            else{
                $message['response']  = 'Ooops... Error While Cancle Permit';
            }
            
            echo json_encode($message);
            break;

        case 'removeall':
            $uniqueid = $_REQUEST['uniqueid'];
            $type     = $_REQUEST['type'];

            switch ($type) {
                case 'SixDirection':
                    $query ="DELETE FROM permit_hazards WHERE id='$uniqueid'";
                    $deletesixdirection   = sqlsrv_query($sqlcon, $query);
                    break;
                case 'PowerClearence':
                    $query ="DELETE FROM power_clearences WHERE id='$uniqueid'";
                    $powerclerance   = sqlsrv_query($sqlcon, $query);
                    break;
                case 'ConfinedSpace':
                    $query ="DELETE FROM confined_spaces WHERE id='$uniqueid'";
                    $confinedspance   = sqlsrv_query($sqlcon, $query);
                    break;
            }

            
            if($deletesixdirection || $powerclerance || $confinedspance){
                $message['response'] = "Data Deleted";
                $message['is_deleted'] = true;
                echo json_encode($message); 
            }
            else{
                $message['response'] = "Ooops... Error While Deleteing";
                $message['is_deleted'] = false;
                echo json_encode($message); 
            }
            break;
        case 'get-report':
            $division_id   = $_REQUEST['division_id'];
            $department_id = $_REQUEST['department_id'];
            $start = $_REQUEST["start"];
            $end   = $_REQUEST["end"];
            if($start == "" && $end ==""){
                $start= 1;
                $end =  9999;
            }
            
            $query ="SELECT * FROM (
                SELECT permits.id as permitid,
                    permits.serial_no,permits.division_id,permits.created_at,
                    permits.order_no,permits.status,permits.return_status,
                    userlogins.id as userid,userlogins.name,divisions.abbreviation,jobs.job_title,
                    permits.area_clearence_id,permits.issuer_id,permits.entered_by,permits.ppc_userid,
                    permits.renew_id_1,permits.renew_id_2, ROW_NUMBER() OVER (ORDER BY permits.id DESC) as row 
                FROM permits 
                    INNER JOIN userlogins ON permits.issuer_id   = userlogins.id
                    INNER JOIN divisions  ON permits.division_id = divisions.id
                    INNER JOIN jobs  ON permits.job_id = jobs.id
                    WHERE permits.division_id='$division_id' AND permits.department_id='$department_id'
            ) a WHERE a.row >= '$start' and a.row <= '$end'";
            

            $fetch_query = sqlsrv_query($sqlcon, $query);
            $myPermits = array();
            $toReturn  = array();
            while($row = sqlsrv_fetch_array($fetch_query))
            {  

                $toReturn['create_date'] = $row['created_at'];
                $toReturn['permitid']    = $row['permitid'];
                $toReturn['order_no']    = $row['order_no'];
                $toReturn['job_title']   = $row['job_title'];
                $toReturn['return_status']   = $row['return_status'];

                $month                      = date('m-Y', strtotime($row['created_at']));
                $toReturn['permit_sl']      = $row['abbreviation'].'/'.$month.'/'.$row['serial_no'];


                switch ($row['status']) {
                    case "Requested":
                        $areaby = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$row['issuer_id']."'"));
                        $toReturn['status2']="Pending with Executing Agency (". $areaby['name'] .")";
                        break;
                    case "Returned":
                        $toReturn['status2']="Permit Returned";   
                        break;

                    case "PPc":
                        $ppc = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$row['ppc_userid']."'"));
                        $toReturn['status2']="Permit Pending at Power Cutting(". $ppc['name'] .")";
                        break;

                    case "Parea":
                        $areaby = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$row['area_clearence_id']."'"));
                        $toReturn['status2']="Pending with Owner Agency (". $areaby['name'] .")";
                        break;
                    
                    case "Issued":
                        switch ($row['return_status']) {
                            case 'PPg':
                                $ppguser  = "/Return Pending at Power Getting User";
                                break;
                            case 'Pending':
                                $ppguser  = "/Return Pending at Executing Agency";
                                break;
                            case 'Pending_area':
                                $ppguser  = "/Return Pending at Owner Agency";
                                break;
                        }
                        $toReturn['status2'] ="Issued". $ppguser;
                        break;
                }

                $myPermits[]=$toReturn;
            }
            echo json_encode($myPermits);
            break; 
        case 'forgot-password':
            $p_number = $_REQUEST['pno'];
            $getUserDetails  = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT id FROM userlogins WHERE vendor_code = $p_number"));
            if(!empty($getUserDetails['id'])){
                    $seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789!@#$%^&*()'); // and any other characters
                    shuffle($seed); // probably optional since array_is randomized; this may be redundant
                    $rand = '';
                    foreach (array_rand($seed, 10) as $k) $rand .= $seed[$k];
                        $password = $rand;
                        $enc  = md5($password);
                        $employee  = sqlsrv_query($sqlcon,"UPDATE userlogins SET password = '$enc' WHERE id ='".$getUserDetails['id']."'");

                        $getUserUpdateDetails = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT  * FROM userlogins WHERE id ='".$getUserDetails['id']."'"));
                        $user = array('name'        => $getUserUpdateDetails['name'],
                                    'email'         => $getUserUpdateDetails['email'],
                                    'vendor_code'   => $getUserUpdateDetails['vendor_code'],
                                    'password'      => $password,
                            );

                        if($employee){
                            include "function.php";
                            $to=$getUserUpdateDetails['email'];
                            $from_mail='saprly@jamipol.com'; 
                            $replyto=$to;
                            $replyname='noreply';
                            $from_name='PTW';
                            $subject='Work Permit System - New Password';
                            $body='Hi '.$getUserUpdateDetails['name'].',<br><br>Your PTW System password is given below : ' .$password.'<br>Thank You<br>';
                            sendSMTP($to,$from_mail,$from_name,$replyto,$replyname,$subject,$body);
                            $message['response'] = "1";
                            echo json_encode($message); 
                        }
            }
            else{
                $message['response'] = "0";
                echo json_encode($message); 
            }
            break;
        case 'p-division':
            $my_id         =  $_REQUEST["my-id"];
            if(!empty($my_id)){
                $permits = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT division_id,id,user_type,user_sub_type FROM userlogins WHERE id='$my_id'"));

                if($permits['user_type'] == 1 && $permits['user_sub_type'] == 3)
                {
                    $divi = sqlsrv_query($sqlcon,"SELECT id,name FROM divisions");
                }
                elseif ($permits['user_type'] == 1 && $permits['user_sub_type'] == 1) {
                    $divi = sqlsrv_query($sqlcon,"SELECT id,name FROM divisions 
                        WHERE id='".$permits['division_id']."'");
                }
                $list=0;
                $getIssuerPermitDetails = array(); 
                while($divisionList = sqlsrv_fetch_array($divi))
                {
                    $getIssuerPermitDetails[$list]['id']   = $divisionList['id'];
                    $getIssuerPermitDetails[$list]['name'] = $divisionList['name'];
                    $list++;
                }
            }
            echo json_encode($getIssuerPermitDetails);
            break;
        case 'viewReport':
            $permit_id =  $_REQUEST["permit-id"];
            $queryview = "SELECT permits.id as
                permitid,permits.power_clearance,permits.high_risk,permits.confined_space,
                permits.serial_no,permits.division_id,divisions.name as divname,
                permits.department_id,departments.department_name,permits.order_no,
                permits.order_validity,permits.start_date,permits.end_date,permits.job_description,
                permits.job_location,jobs.id as jobid,permits.welding_gas,permits.riggine,
                permits.working_at_height,permits.hydraulic_pneumatic,permits.painting_cleaning,
                permits.gas,permits.others,permits.specify_others,permits.post_site_pic,
                permits.latlong,permits.safe_work,permits.all_person,permits.worker_working,
                permits.all_lifting_tools,permits.all_safety_requirement,
                permits.all_person_are_trained,permits.ensure_the_appplicablle,
                permits.power_clearance_number,permits.area_clearence_required,
                permits.area_clearence_id,jobs.job_title,jobs.swp_number,forissuer1.id as issuer1id,
                forissuer1.name as issuer1name,froareaclearence.id as area_cls_id,
                froareaclearence.name as area_cls_name,permits.status,permits.vlevel,permits.issuer_power,
                permits.electrical_license_issuer,permits.validity_date_issuer,permits.rec_power,
                permits.electrical_license_rec,permits.validity_date_rec,permits.created_at,
                divisions.abbreviation,permits.s_instruction,permits.ppc_userid,power_cutting_remarks,
                permits.other_isolation,permits.executing_lock,permits.working_lock
                FROM permits
                    LEFT JOIN userlogins as forissuer1 ON permits.issuer_id =  forissuer1.id
                    LEFT JOIN userlogins as froareaclearence ON permits.area_clearence_id = froareaclearence.id
                    LEFT JOIN divisions ON permits.division_id      = divisions.id
                    LEFT JOIN departments ON permits.department_id  = departments.id
                    LEFT JOIN jobs ON permits.job_id                = jobs.id
                WHERE  permits.id = '$permit_id'";
            // echo $queryview;
            // exit;
            $fetch_query1 = sqlsrv_query($sqlcon, $queryview);
            $getIssuerPermitDetails = array();
            while($row = sqlsrv_fetch_array($fetch_query1))
            {
                $getIssuerPermitDetails['permitid']     = $row['permitid'];
                $getIssuerPermitDetails['divisionId']   = $row['division_id'];
                $getIssuerPermitDetails['divisionName'] = $row['divname'];
                $getIssuerPermitDetails['departmentId'] = $row['department_id'];
                $getIssuerPermitDetails['departmentName'] = $row['department_name'];
                $getIssuerPermitDetails['orderNumber']    = $row['order_no'];
                $getIssuerPermitDetails['orderValidity']  = $row['order_validity'];
                $getIssuerPermitDetails['startDate']      = $row['start_date'];
                $getIssuerPermitDetails['endDate']        = $row['end_date'];
                $getIssuerPermitDetails['jobDescription'] = $row['job_description'];
                $getIssuerPermitDetails['jobLocation']    = $row['job_location'];
                $getIssuerPermitDetails['jobId']          = $row['jobid'];
                $jobid                                    = $row['jobid'];
                $getIssuerPermitDetails['job_title'] = $row['job_title'];
                $getIssuerPermitDetails['swp_number'] = $row['swp_number'];

                $swpfile = "SELECT swp_file FROM swp_files WHERE job_id='$jobid'";
                $runQueryswp   = sqlsrv_query($sqlcon, $swpfile);
                $li0 =0;
                while($rowswp=sqlsrv_fetch_array($runQueryswp))
                {
                    $getIssuerPermitDetails['SwpFile'][$li0]['swp_file']    = $rowswp['swp_file'];
                    $li0++;
                }

                $getIssuerPermitDetails['weldingGas']         =  $row['welding_gas'];
                $getIssuerPermitDetails['riggine']            =  $row['riggine'];
                $getIssuerPermitDetails['workingAtHeight']    =  $row['working_at_height'];
                $getIssuerPermitDetails['hydraulicPneumatic'] =  $row['hydraulic_pneumatic'];
                $getIssuerPermitDetails['paintingCleaning']   =  $row['painting_cleaning'];
                $getIssuerPermitDetails['gas']                =  $row['gas'];
                $getIssuerPermitDetails['others']             =  $row['others'];
                $getIssuerPermitDetails['specifyOthers']      =  $row['specify_others'];

                $getHazard = "SELECT id,dir,hazard,precaution FROM permit_hazards WHERE permit_id='$permit_id'";
                $runQuery1   = sqlsrv_query($sqlcon, $getHazard);
                $li1=0;
                while($row2=sqlsrv_fetch_array($runQuery1))
                {
                    $getIssuerPermitDetails['SixDirection'][$li1]['id']         = $row2['id'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['dir']        = $row2['dir'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['hazard']     = $row2['hazard'];
                    $getIssuerPermitDetails['SixDirection'][$li1]['precaution'] = $row2['precaution'];
                    $li1++;
                }


                $getIssuerPermitDetails['issuer1id'] = $row['issuer1id'];
                $getIssuerPermitDetails['issuer1name'] = $row['issuer1name'];

                $getGatePass = "SELECT id,employee_name,gate_pass_no,designation,age,expirydate FROM gate_pass_details WHERE permit_id='$permit_id'";
                $runQuery2   = sqlsrv_query($sqlcon, $getGatePass);
                $li2=0;
                while($row3=sqlsrv_fetch_array($runQuery2))
                {
                    $getIssuerPermitDetails['Empolyee'][$li2]['id']              = $row3['id'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['emp_name']        = $row3['employee_name'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['g_pass_number']   = $row3['gate_pass_no'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['design']          = $row3['designation'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['age']             = $row3['age'];
                    $getIssuerPermitDetails['Empolyee'][$li2]['expirydate']      = $row3['expirydate'];
                    $li2++;
                }

                $getIssuerPermitDetails['safe_work']            = $row['safe_work'];
                $getIssuerPermitDetails['all_person']           = $row['all_person'];
                $getIssuerPermitDetails['worker_working']       = $row['worker_working'];
                $getIssuerPermitDetails['all_lifting_tools']    = $row['all_lifting_tools'];
                $getIssuerPermitDetails['all_safety_requirement']  = $row['all_safety_requirement'];
                $getIssuerPermitDetails['all_person_are_trained']  = $row['all_person_are_trained'];
                $getIssuerPermitDetails['ensure_the_appplicablle'] = $row['ensure_the_appplicablle'];
                $getIssuerPermitDetails['status']                  = $row['status'];
                $getIssuerPermitDetails['post_site_pic']           = $row['post_site_pic'];
                $getIssuerPermitDetails['high_risk']               = $row['high_risk'];
                $getIssuerPermitDetails['latlong']                 = $row['latlong'];
                $getIssuerPermitDetails['power_clearance_number']  = $row['power_clearance_number'];
                $getIssuerPermitDetails['area_cls_id']             = $row['area_cls_id'];
                $getIssuerPermitDetails['area_cls_name']           = $row['area_cls_name'];
                $getIssuerPermitDetails['area_clearence_required'] = $row['area_clearence_required'];
                $getIssuerPermitDetails['executing_lock']           = $row['executing_lock'];
                $getIssuerPermitDetails['working_lock']             = $row['working_lock'];

                
                $powerCuttingUsers = sqlsrv_query($sqlcon, "SELECT id,name FROM userlogins WHERE division_id='".$row['division_id']."' AND user_type ='1' AND power_cutting ='Yes'");
                $indexPC = 0;
                while($powerCuttingUsersList  =  sqlsrv_fetch_array($powerCuttingUsers))
                {
                    $getIssuerPermitDetails['pcUserList'][$indexPC]['id']  = $powerCuttingUsersList['id'];
                    $getIssuerPermitDetails['pcUserList'][$indexPC]['name']  = $powerCuttingUsersList['name'];
                    $indexPC++;
                }
                

                $forAreaClearence = sqlsrv_query($sqlcon, "SELECT id,name FROM userlogins WHERE division_id='".$row['division_id']."'");
                $indexOG = 0;
                while($forAreaClearenceList  =  sqlsrv_fetch_array($forAreaClearence))
                {
                    $getIssuerPermitDetails['ownerAgencyList'][$indexOG]['id']  = $forAreaClearenceList['id'];
                    $getIssuerPermitDetails['ownerAgencyList'][$indexOG]['name']  = $forAreaClearenceList['name'];
                    $indexOG++;
                }

                $pcusername = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT id,name FROM userlogins WHERE id='".$row['ppc_userid']."'"));
                $getIssuerPermitDetails['id']           = $pcusername['id'];
                $getIssuerPermitDetails['pcusername']   = $pcusername['name'];

                $getIssuerPermitDetails['vlevel']       = $row['vlevel'];
                $getIssuerPermitDetails['issuer_power_id'] = $row['issuer_power'];
                if($getIssuerPermitDetails['issuer_power_id'] != '0'){
                    $IssuerName = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT name FROM userlogins WHERE id='".$getIssuerPermitDetails['issuer_power_id']."'"));
                    $getIssuerPermitDetails['powerIssuerName'] = $IssuerName['name'];
                }
                $getIssuerPermitDetails['electrical_license_issuer'] = $row['electrical_license_issuer'];
                $getIssuerPermitDetails['validity_date_issuer']      = $row['validity_date_issuer'];

                $getIssuerPermitDetails['power_clearance']           = $row['power_clearance'];
                $getPowercls = "SELECT id,equipment,positive_isolation_no,location,box_no,caution_no FROM power_clearences WHERE permit_id='$permit_id'";
                $runQuery3   = sqlsrv_query($sqlcon, $getPowercls);
                $li3 = 0;
                while($row4=sqlsrv_fetch_array($runQuery3))
                {
                    $getIssuerPermitDetails['PowerClearences'][$li3]['id']                      = $row4['id'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['equipment']               = $row4['equipment'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['positive_isolation_no']   = $row4['positive_isolation_no'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['location']                = $row4['location'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['box_no']                  = $row4['box_no'];
                    $getIssuerPermitDetails['PowerClearences'][$li3]['caution_no']              = $row4['caution_no'];
                    $li3++;
                }
                $getIssuerPermitDetails['power_cutting_remarks'] = $row['power_cutting_remarks'];
                $getIssuerPermitDetails['s_instruction']         = $row['s_instruction'];

                $getIssuerPermitDetails['other_isolation']           = $row['other_isolation'];
                $otherIsolationQuery = "SELECT id,positive_other,equipment_other,location_other FROM other_isolation WHERE permit_id='$permit_id'";
                $runIsolation   = sqlsrv_query($sqlcon, $otherIsolationQuery);
                $index = 0;
                while($row4=sqlsrv_fetch_array($runIsolation))
                {
                    $getIssuerPermitDetails['OtherIsolation'][$index]['id']               = $row4['id'];
                    $getIssuerPermitDetails['OtherIsolation'][$index]['positive_other']   = $row4['positive_other'];
                    $getIssuerPermitDetails['OtherIsolation'][$index]['equipment_other']  = $row4['equipment_other'];
                    $getIssuerPermitDetails['OtherIsolation'][$index]['location_other']   = $row4['location_other'];
                    $index++;
                }

                $getIssuerPermitDetails['confined_space'] = $row['confined_space'];
                $getconfined = "SELECT id,clearance_no,depth,location FROM confined_spaces 
                                WHERE permit_id='$permit_id'";
                $runQuery4   = sqlsrv_query($sqlcon, $getconfined);
                $key1 =0;
                while($row5=sqlsrv_fetch_array($runQuery4))
                {
                    $getIssuerPermitDetails['ConfinedSpace'][$key1]['id']   = $row5['id'];
                    $getIssuerPermitDetails['ConfinedSpace'][$key1]['clearence_num']   = $row5['clearance_no'];
                    $getIssuerPermitDetails['ConfinedSpace'][$key1]['depth']          = $row5['depth'];
                    $getIssuerPermitDetails['ConfinedSpace'][$key1]['location']       = $row5['location'];
                    $key1++;
                }
            }
            echo json_encode($getIssuerPermitDetails);
            break;
    } //end of switch
}//End of if
else {
    echo "Token Not Matched";
}
?>