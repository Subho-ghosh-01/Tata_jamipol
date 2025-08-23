<?php
error_reporting(0);
$token  = $_REQUEST["token"];


//$serverName = "localhost";
//$connectionInfo = ["Database" => "jamipol_owps", "UID" => "laravel", "PWD" => "987654321","ReturnDatesAsStrings"=>true];

//$serverName     = "103.86.176.182";
$serverName     = "216.48.184.92";
$connectionInfo = ["Database" => "jamipol", "UID" => "ptwuser", "PWD" => "ptwuser","ReturnDatesAsStrings"=>true];
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
                user_type,division_id,department_id,user_sub_type,vms,vms_roll,clm,clm_role,clms_admin,safety,safety_role,safety_admin,wps,status,hr_by
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
				
			
				
				if($userlogin['status']=='' && $userlogin['hr_by']!=''){
					$message['vendor_reg'] = "Yes";
				}else{
					$message['vendor_reg'] = "No";
				}
				
				
                $message['division_id'] = $userlogin['division_id'];
                 $message['wps'] = $userlogin['wps'];
                 $message['vms'] = $userlogin['vms'];
                $message['vms_role'] = $userlogin['vms_roll'];
                $message['vms_admin'] = $userlogin['vms_admin'];
                $message['clms'] = $userlogin['clm'];
                $message['clms_role'] = $userlogin['clm_role'];
                $message['clms_admin'] = $userlogin['clms_admin'];
                $message['safety'] = $userlogin['safety'];
                $message['safety_role'] = $userlogin['safety_role'];
                $message['safety_admin'] = $userlogin['safety_admin'];

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
			
		// vms Division	
      case 'vms_divisions':
            
           $query = "SELECT id,name FROM divisions"; 
            
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
            case 'executing_agency_user':
            $division_id = $_REQUEST['division_id'];
                $query = "SELECT id,name FROM userlogins where division_id='$division_id'"; 
                 
                 $fetch_query = sqlsrv_query($sqlcon, $query);
                 $takeAll=array();
                 $toReturn= array();
     
                 while($row=sqlsrv_fetch_array($fetch_query))
                 {
                     $takeAll['id']=$row['id'];
                     $takeAll['username_executing']=$row['name'];
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
               // $demo = "vendor_code ='$vendorCode' AND order_code='$order_number'";
				                $demo = "order_code='$order_number'";

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
                    WHERE  permits.issuer_id='$myid' AND permits.status='Requested'
                        OR permits.area_clearence_id='$myid' AND permits.status='Parea' 
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
                    WHERE  permits.issuer_id='$myid' AND permits.status='Issued'
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
                WHERE  permits.ppc_userid='$myid' AND permits.status='PPc' ORDER BY permits.id DESC";  

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
                $employeeName = $value['emp_name'];
                $gatePass     = $value['gate_pass_no'];
                $designation  = $value['desig'];
                $age          = $value['age'];
                $expiryDate   = $value['expirydate'];
                $intime   = $value['intime'];

                $gatePassInsert="Insert INTO gate_pass_details (permit_id,employee_name,
                                gate_pass_no,designation,age,expirydate,intime,type) 
                                VALUES ('$permit_id','$employeeName','$gatePass','$designation',
                                '$age','$expiryDate','$intime','New')";
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
                    $getIssuerPermitDetails['Empolyee'][$li2]['intime']          = $row3['intime'];
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
                            //echo "update";
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
                WHERE permits.ppc_userid = '$my_id' AND permits.id = '$permit_id' AND permits.status='PPc'";
             //echo $queryview;
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
            $outtime               = $_REQUEST['outtime'];
          
            
            $getStatus = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT power_clearance,
                            return_status FROM permits WHERE id='$permit_id'"));

            if($getStatus['return_status'] == 'Pending'){
                $issuer_remark     =  $_REQUEST["issuer_remark"];
                $gouttime = sqlsrv_query($sqlcon,"Update gate_pass_details SET outtime='$outtime' Where permit_id='$permit_id'");
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
            $start_date = $_REQUEST["start-date"];
            $end_date   = $_REQUEST["end-date"];
            if($start == "" && $end ==""){
                $start= 1;
                $end =  9999;
            }

            $TTCount = sqlsrv_fetch_array(sqlsrv_query($sqlcon,"SELECT COUNT(permits.id) AS totalCount FROM permits
                INNER JOIN userlogins ON permits.issuer_id = userlogins.id
                INNER JOIN divisions ON permits.division_id = divisions.id
                INNER JOIN jobs ON permits.job_id = jobs.id
            WHERE permits.division_id='$division_id' AND permits.department_id='$department_id'
            AND permits.created_at >= '$start_date 00:00:00' 
            AND permits.created_at <= '$end_date 23:59:59.999'"));
            // echo $TTCount['totalCount'];

            
            $query = "SELECT * FROM (
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
                WHERE permits.division_id='$division_id' 
                    AND permits.department_id='$department_id'
                    AND permits.created_at >= '$start_date 00:00:00' 
                    AND permits.created_at <= '$end_date 23:59:59.999'
            ) a WHERE a.row >= '$start' and a.row <= '$end'";
            

            $fetch_query = sqlsrv_query($sqlcon, $query);
            $myPermits = array();
            $toReturn  = array();
            $myPermits['totalCount'] = $TTCount['totalCount'];
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

                $myPermits['list'][]=$toReturn;
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

            // clms / vms / safety

           case 'vms_Insert':
		   header("Access-Control-Allow-Origin: *");
            $data = json_decode(file_get_contents('php://input'), true);
		//	print_r($data);
			//exit;
           $mobile_no  = $data['mobile_no'];
           $visitor_name = $data['visitor_name'];
           $visitor_company = $data['visitor_company'];
           $visitor_email = $data['visitor_email'];
           $visitor_emergency_contact_no = $data['visitor_emergency_contact_no'];
           $blood_group = $data['blood_group'];
           $upload_photo= $data['upload_photo'];
           $division= $data['division'];
           $department= $data['department'];
           $approver =$data['approver'];
           $select_days=$data['select_days'];
           $from_date= $data['from_date'];
           $to_date = $data['to_date'];
           $from_time =$data['from_time'];
           $to_time =$data['to_time'];
           $any_material =$data['any_material'];
           //$material_name = serialize($_REQUEST['material_name']);
           //$material_idenrification_no = serialize($_REQUEST['material_idenrification_no']);
          // $returnable = serialize($_REQUEST['returnable']);
          // $purpose_of_material_entry = serialize($_REQUEST['purpose_of_material_entry']);
           $visitor_coming_by_any_vehicle = $data['visitor_coming_by_any_vehicle'];
           $driving_mode = $data['driving_mode'];
           $driver_name = $data['driver_name'];
           $vehicle_no = $data['vehicle_no'];
           $dl_no = $data['dl_no'];
           $created_by=$data['created_by'];
           date_default_timezone_set("Asia/Calcutta");
           $date =  date('Y-m-d H:i:s');


if($select_days=='Single'){
$to_date = $data['from_date'];
}else{
  $to_date = $data['to_date'];
}
  

  //Select Sl No Start
       $transdate = date('Y-m-d');
       $transdate1 = date('m-d');
        $month = date('m', strtotime($transdate));
        $year  = date('Y', strtotime($transdate));

 
      $encoded = $data['upload_photo'];
	  $rr =uniqid();
     $rand='public/documents/clm_pics/'. $rr . '.png';
      $file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
	 $rand2 =$rr . '.png';
     fwrite($file, base64_decode($encoded));
     fclose($file);



$sl_no = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT * FROM visitor_gate_pass WHERE  YEAR(created_datetime)='$year' AND MONTH(created_datetime)='$month' AND division_id='$division' order by id DESC"));
   if ($sl_no['sl']) {
      $c_no = $sl_no['sl'] + 1;
    } else {
      $c_no = 1; 
    }

    $division1 = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT abbreviation FROM divisions WHERE id='$division'"));
  $abb=$division1['abbreviation'];
  $full_sl='VGP'.'/'.$abb.'/'.$transdate1.'/'. $c_no;

//Select Sl no End
header("Access-Control-Allow-Origin: *");
        $data = json_decode(file_get_contents('php://input'), true);  

$material_details = $data['material_details'];

$material_name1 = [];
$material_idenrification_no1 = [];
$returnable1 = [];
$purpose_of_material_entry1 = [];

foreach($material_details as $key => $value) {
	
        $material_name1[] = $value['material_name'];
        $material_idenrification_no1[] = $value['material_identification_no'];
        $returnable1[] = $value['returnable'];
        $purpose_of_material_entry1[] = $value['purpose_of_material_entry'];
}

$material_name = serialize($material_name1);
$material_idenrification_no = serialize($material_idenrification_no1);
$returnable = serialize($returnable1);
$purpose_of_material_entry = serialize($purpose_of_material_entry1);

 $vms_insert ="INSERT INTO visitor_gate_pass (sl,full_sl,visitor_mobile_no,visitor_name,visitor_company,visitor_email,visitor_emergency_contact_no,blood_group,upload_photo,division_id,department,approver,days,from_date,to_date,from_time,to_time,any_material,material_name,material_identification_no,returnable,propose_of_entry,visitor_any_vehicle,driving_mode,driver_name,vehicle_no,dl_no,status,created_by,created_datetime) VALUES ('$c_no','$full_sl','$mobile_no','$visitor_name','$visitor_company','$visitor_email','$visitor_emergency_contact_no','$blood_group','$rand2','$division','$department','$approver','$select_days','$from_date','$to_date','$from_time','$to_time','$any_material','$material_name','$material_idenrification_no','$returnable','$purpose_of_material_entry','$visitor_coming_by_any_vehicle','$driving_mode','$driver_name','$vehicle_no','$dl_no','Pending_to_approve','$created_by','$date')";
  
  $executeQuery = sqlsrv_query($sqlcon, $vms_insert);

            if($executeQuery){ 
                $message['response'] = "Successfully";
                echo json_encode($message); 
            }
            else{ 
                $message['response'] = "INSERT INTO visitor_gate_pass (sl,full_sl,visitor_mobile_no,visitor_name,visitor_company,visitor_email,visitor_emergency_contact_no,blood_group,upload_photo,division_id,department,approver,days,from_date,to_date,from_time,to_time,any_material,material_name,material_identification_no,returnable,propose_of_entry,visitor_any_vehicle,driving_mode,driver_name,vehicle_no,dl_no,status,created_by,created_datetime) VALUES ('$c_no','$full_sl','$mobile_no','$visitor_name','$visitor_company','$visitor_email','$visitor_emergency_contact_no','$blood_group','$rand2','$division','$department','$approver','$select_days','$from_date','$to_date','$from_time','$to_time','$any_material','$material_name','$material_idenrification_no','$returnable','$purpose_of_material_entry','$visitor_coming_by_any_vehicle','$driving_mode','$driver_name','$vehicle_no','$dl_no','Pending_to_approve','$created_by','$date'";
                echo json_encode($message);
              }
          
             break;

// Pending Approval visitor Gate pass start
     case 'vms_view_pending';
 $division = $_REQUEST['division'];
 $vms_role = $_REQUEST['vms_role'];
 $session_id = $_REQUEST['user_id'];

$start = $_REQUEST["start"];
  $end   = $_REQUEST["end"];
            if($start == "" && $end ==""){
                $start= 1;
                $end =  9999;
            }

if($vms_role=='Requester'){ 


$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (


    SELECT visitor_gate_pass.*,divisions.name as division_name, ROW_NUMBER() OVER (ORDER BY visitor_gate_pass.id DESC) as row  FROM visitor_gate_pass
     INNER JOIN divisions ON visitor_gate_pass.division_id   = divisions.id
	where  created_by='$session_id'  AND status='Pending_to_approve' ) a WHERE a.row >= '$start' and a.row <= '$end'");

}else if ($vms_role=='Approver'){
$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

    SELECT  visitor_gate_pass.*, ROW_NUMBER() OVER (ORDER BY visitor_gate_pass.id DESC) as row  FROM visitor_gate_pass where division_id='$division' AND approver='$session_id' AND status='Pending_to_approve') a WHERE a.row >= '$start' and a.row <= '$end'");

}else if($vms_role=='Security'){

$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

    SELECT  visitor_gate_pass.*, ROW_NUMBER() OVER (ORDER BY visitor_gate_pass.id DESC) as row  FROM visitor_gate_pass where division_id='$division' AND status='issued') a WHERE a.row >= '$start' and a.row <= '$end'");
}

            while($row = sqlsrv_fetch_array($vms_view))
                {

         /*  $approver = $row['approver'];
			
			$sql3 = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id ='$approver'");
			$row3 = sqlsrv_fetch_array($sql3);
            $response['approver_name']= $row3['name'];
			
			$division = $row['division_id'];
            $sql33 = sqlsrv_query($sqlcon,"SELECT * FROM divisions where id ='$division'");
			$row33 = sqlsrv_fetch_array($sql33);
            $response['division_name']= $row33['name'];

            $department = $row['department'];
            $sql333 = sqlsrv_query($sqlcon,"SELECT * FROM departments where id ='$department'");
			$row333 = sqlsrv_fetch_array($sql333);
            $response['department_name']= $row333['department_name'];*/


           $response[] = $row;
  //$response2[]= $response;
                }
echo json_encode($response);
 break;
//Pending Approvel visitor Gatepass End

//Approved/Rejected/Completed Visitor Gatepass Start
case 'vms_view_approved';
 $division = $_REQUEST['division'];
 $vms_role = $_REQUEST['vms_role'];
 $session_id = $_REQUEST['user_id'];
 $start = $_REQUEST["start"];
  $end   = $_REQUEST["end"];
            if($start == "" && $end ==""){
                $start= 1;
                $end =  9999;
            }




if($vms_role=='Requester'){ 

 

$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (


  SELECT  visitor_gate_pass.*,divisions.name as division_name, ROW_NUMBER() OVER (ORDER BY visitor_gate_pass.id DESC) as row  FROM visitor_gate_pass
  INNER JOIN divisions ON visitor_gate_pass.division_id   = divisions.id
  where division_id='$division' AND created_by='$session_id'  AND (status='issued'  OR status='Rejected' OR status='Completed' ) ) a WHERE a.row >= '$start' and a.row <= '$end'");


//$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM visitor_gate_pass where division_id='$division' AND //created_by='$session_id'  AND (status='issued'  OR status='Rejected' OR status='Completed') ORDER BY id //DESC");

}else if ($vms_role=='Approver'){


$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (


   SELECT  visitor_gate_pass.*,divisions.name as division_name, ROW_NUMBER() OVER (ORDER BY visitor_gate_pass.id DESC) as row FROM visitor_gate_pass 
   INNER JOIN divisions ON visitor_gate_pass.division_id   = divisions.id
   where visitor_gate_pass.division_id='$division' AND visitor_gate_pass.approver='$session_id' AND (visitor_gate_pass.status='issued'  OR visitor_gate_pass.status='Rejected' OR visitor_gate_pass.status='Completed')) a WHERE a.row >= '$start' and a.row <= '$end'");

//$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM visitor_gate_pass where division_id='$division' AND //approver='$session_id' AND (status='issued'  OR status='Rejected' OR status='Completed') ORDER BY id //DESC");

}else if($vms_role=='Security'){

$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM ( 

   SELECT  visitor_gate_pass.*, ROW_NUMBER() OVER (ORDER BY visitor_gate_pass.id DESC) as row visitor_gate_pass where division_id='$division' AND (status='Rejected' OR status='Completed') ) a WHERE a.row >= '$start' and a.row <= '$end'");

//$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM visitor_gate_pass where division_id='$division' AND (//status='Rejected' OR status='Completed') ORDER BY id DESC");
}

            while($row = sqlsrv_fetch_array($vms_view))
                {
      
             $response[] = $row;

                }
  echo json_encode($response);
 break;
case 'vms_view_pending_count';

$division = $_REQUEST['division'];
 $vms_role = $_REQUEST['vms_role'];
 $session_id = $_REQUEST['user_id'];

$start = $_REQUEST["start"];
  $end   = $_REQUEST["end"];
            if($start == "" && $end ==""){
                $start= 1;
                $end =  999999;
            }

if($vms_role=='Requester'){ 

$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM visitor_gate_pass where division_id='$division' AND created_by='$session_id'  AND status='Pending_to_approve'");

}else if ($vms_role=='Approver'){



$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM  visitor_gate_pass where division_id='$division' AND approver='$session_id' AND status='Pending_to_approve'");

//$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (SELECT  visitor_gate_pass.*, ROW_NUMBER() OVER (ORDER //BY visitor_gate_pass.id DESC) as row  FROM visitor_gate_pass where division_id='$division' AND //approver='$session_id' AND status='Pending_to_approve') a WHERE a.row >= '$start' and a.row <= '$end'");

}else if($vms_role=='Security'){

$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

    SELECT  visitor_gate_pass.*, ROW_NUMBER() OVER (ORDER BY visitor_gate_pass.id DESC) as row  FROM visitor_gate_pass where division_id='$division' AND status='issued') a WHERE a.row >= '$start' and a.row <= '$end'");
}

$count = 0;
            while($row = sqlsrv_fetch_array($vms_view))
                {
                $count++;
         }
 $response = $count;

echo json_encode($response);
break;

case 'vms_view_approved_count';

 $division = $_REQUEST['division'];
 $vms_role = $_REQUEST['vms_role'];
 $session_id = $_REQUEST['user_id'];
 

if($vms_role=='Requester'){ 


$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM visitor_gate_pass where division_id='$division' AND created_by='$session_id'  AND (status='issued'  OR status='Rejected' OR status='Completed' )");

}else if ($vms_role=='Approver'){



$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM visitor_gate_pass where division_id='$division' AND approver='$session_id' AND (status='issued'  OR status='Rejected' OR status='Completed' ) ");


}else if($vms_role=='Security'){

$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM ( 

   SELECT  visitor_gate_pass.*, ROW_NUMBER() OVER (ORDER BY visitor_gate_pass.id DESC) as row visitor_gate_pass where division_id='$division' AND (status='Rejected' OR status='Completed') ) a WHERE a.row >= '$start' and a.row <= '$end'");

//$vms_view  = sqlsrv_query($sqlcon,"SELECT * FROM visitor_gate_pass where division_id='$division' AND (//status='Rejected' OR status='Completed') ORDER BY id DESC");
}
$count=0;
            while($row = sqlsrv_fetch_array($vms_view))
                {
           $count++;
            
             }
 $response= $count;
  echo json_encode($response);

break;
//Approved/Rejected/Completed Visitor Gatepass End

//Details Page VMS Start 
case 'vms_details';
$id=$_REQUEST['id'];
$user_id =$_REQUEST['user_id'];
$role = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$user_id'");  
$role_1 = sqlsrv_fetch_array($role);
$vms_role=$role_1['vms_roll'];
$div=$role_1['division_id'];

/*$vms_details  = sqlsrv_query($sqlcon,"SELECT visitor_gate_pass.*,divisions.name as division_name,departments.department_name,userlogins.name as approver_name FROM visitor_gate_pass 
INNER JOIN divisions ON visitor_gate_pass.division_id   = divisions.id
INNER JOIN departments ON visitor_gate_pass.department   = departments.id
INNER JOIN userlogins ON visitor_gate_pass.approver   = userlogins.id
where visitor_gate_pass.id='$id'");*/


$vms_details  = sqlsrv_query($sqlcon,"SELECT * FROM visitor_gate_pass Where id='$id'");
while($row = sqlsrv_fetch_array($vms_details))
                {
   //   $response[] = $row;
      /*$approver = $row['approver'];
      $app = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$approver'");  
      $row2 = sqlsrv_fetch_array($app);*/
     // $response['to_meet']=$row2['name'];
	 $response['full_sl'] = $row['full_sl'];
	  $response['visitor_name'] = $row['visitor_name'];
	  $response['visitor_company'] = $row['visitor_company'];
	  $response['visitor_mobile'] = $row['visitor_mobile_no'];
	   $response['visitor_email'] = $row['visitor_email'];
	   $response['visitor_emergency_contact_no'] = $row['visitor_emergency_contact_no'];
	    $approver = $row['approver'];
			
			$sql3 = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id ='$approver'");
			$row3 = sqlsrv_fetch_array($sql3);
            $response['approver_name']= $row3['name'];
			
			$division = $row['division_id'];
            $sql33 = sqlsrv_query($sqlcon,"SELECT * FROM divisions where id ='$division'");
			$row33 = sqlsrv_fetch_array($sql33);
            $response['division_name']= $row33['name'];

            $department = $row['department'];
            $sql333 = sqlsrv_query($sqlcon,"SELECT * FROM departments where id ='$department'");
			$row333 = sqlsrv_fetch_array($sql333);
            $response['department_name']= $row333['department_name'];
			
			$response['from_date'] = $row['from_date'];
			$response['to_date'] = $row['to_date'];
			$response['from_time'] = $row['from_time'];
			$response['to_time'] = $row['to_time'];
			$response['days'] = $row['days'];
			$response['upload_photo'] = $row['upload_photo'];
			$response['any_material'] = $row['any_material'];
			//$response['material_name']=unserialize($row['material_name']);
           // $response['material_identification_no']=unserialize($row['material_identification_no']);
           // $response['returnable']=unserialize($row['returnable']);
            //$response['propose_of_entry']=unserialize($row['propose_of_entry']);
			$response['visitor_any_vehicle'] = $row['visitor_any_vehicle'];
			$response['driving_mode'] = $row['driving_mode'];
			$response['driver_name'] = $row['driver_name'];
			$response['vehicle_no'] = $row['vehicle_no'];
			$response['dl_no'] = $row['dl_no'];
			$response['created_by'] = $row['created_by'];
			$response['approver_remarks'] = $row['approver_remarks'];
			$response['approver_decision'] = $row['approver_decision'];
			$response['approver_datetime'] = $row['approver_datetime'];
			$response['status'] = $row['status'];
			$link = "http://aiplbaradwari.ddns.net:5002//jamipol_vms/public/documents/clm_pics/";
			$response['photo'] = $link.$row['upload_photo'];
          $status=$row['status'];
		  
		  $test = unserialize($row['material_name']);
$test_id = unserialize($row['material_identification_no']);
$returnable = unserialize($row['returnable']);
$propose_of_entry = unserialize($row['propose_of_entry']);
  $count=count($test);

		  
		  $material_details = [];
		  
			$material = [];
		  for ($i=0;$i<$count;$i++)
{
            $material['material_name']=$test[$i];
			
            $material['material_identification_no']=$test_id[$i];
            $material['returnable']=$returnable[$i];
            $material['propose_of_entry']=$propose_of_entry[$i];
			$material_details[] = $material;
			$material = [];	

}
			$response['material_details'] = $material_details;
          $division=$row['division_id'];
         if($vms_role=='Approver' && $status=='Pending_to_approve' && $div==$division ){
           $response['Pending_Role']='Approver';
         }else if($vms_role=='Security' && $status=='issued' && $div==$division ){
              $response['Pending_Role']='Security';
         }else{
            $response['Pending_Role']='Null';
         }
          }

  echo json_encode($response);

break;
//Details Page VMS End 

//VMS Update Approver / Security Start
case 'update_vms_decision';
$id=$_REQUEST['id'];
$pending_role=$_REQUEST['pending_role'];
$decision = $_REQUEST['decision'];
$user_id=$_REQUEST['user_id'];
$date=date('Y-m-d H:i:s');
$remarks=$_REQUEST['remarks'];
$from_date=$_REQUEST['from_date'];
$to_date=$_REQUEST['to_date'];

if($pending_role=='Approver'){
 $query = "UPDATE visitor_gate_pass 
                        SET
                            approver_decision      = '$decision',
                            approver_datetime      = '$date',
                            approver_remarks       = '$remarks',
							from_date              = '$from_date',
							to_date                = '$to_date',
                            status                 =  'issued'
                             WHERE id='$id'";
}else if($pending_role=='Security') {

$query = "UPDATE visitor_gate_pass 
                        SET
                            security_print_id      ='$user_id',
                            security_print_datetime      = '$date',
                            security_print_remarks       = '$remarks',
                            status                   ='Completed'
                             WHERE id='$id'";

}else{
    $query='Null';
}

 $executeQuery = sqlsrv_query($sqlcon,$query);
 if($executeQuery){
                $message['response'] = "Successfully";
                echo json_encode($message); 
            }
            else{
                $message['response'] = "Not Successfully";
                echo json_encode($message);
              }
break;
// VMS Update Approver / Security End 

//CLMS Start

//CLMS Insert Form Start
 case 'clms_workorder':
       
	    $query = "SELECT id,order_code,order_validity FROM work_order"; 
            
            $fetch_query = sqlsrv_query($sqlcon, $query);
            $takeAll=array();
            $toReturn= array();

            while($row=sqlsrv_fetch_array($fetch_query))
            {
                $takeAll['id']=$row['id'];
                $takeAll['order_code']=$row['order_code'];
                $takeAll['order_validity']=$row['order_validity'];
                $toReturn[] = $takeAll;
            }
            echo json_encode($toReturn);
            break;
case 'Clms_insert';
$work_order_no = $_REQUEST['work_order_no'];
$work_order_validity = $_REQUEST['work_order_validity'];
$name = $_REQUEST['name'];
$son_of = $_REQUEST['son_of'];
$gender= $_REQUEST['gender'];
$date_of_birth = $_REQUEST['date_of_birth'];
$blood_group = $_REQUEST['blood_group'];
$address= $_REQUEST['address'];
$mobile_no = $_REQUEST['mobile_no'];
$designation = $_REQUEST['designation'];
$gp_status = $_REQUEST['gp_status'];
$executing_id = $_REQUEST['executing_id'];
//$passport_size_photo = $_REQUEST['passport_size_photo'];
$encoded = $_REQUEST['passport_size_photo'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.png';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$passport_size_photo =$rr . '.png';
fwrite($file, base64_decode($encoded));
fclose($file);

$education = $_REQUEST['education'];
$board_name =  $_REQUEST['board_name'];
//$upload_result = $_REQUEST['upload_result'];

$encoded1 = $_REQUEST['upload_result'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$upload_result =$rr . '.pdf';
fwrite($file, base64_decode($encoded1));
fclose($file);

$experience = $_REQUEST['exprience'];
$skill_type = $_REQUEST['skill_type'];
$uan_no      = $_REQUEST['uan_no'];
//$uan_upload = $_REQUEST['uan_upload'];
$encoded11 = $_REQUEST['uan_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$uan_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded11));
fclose($file);
$identity_proof = $_REQUEST['identity_proof'];
$unique_id_no  = $_REQUEST['unique_id_no'];
//$upload_id_proof_front = $_REQUEST['upload_id_proof_front'];
$encoded12 = $_REQUEST['upload_id_proof_front'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$upload_id_proof_front =$rr . '.pdf';
fwrite($file, base64_decode($encoded12));
fclose($file);
//$upload_id_proof_back   =  $_REQUEST['upload_id_proof_back'];
$encoded13 = $_REQUEST['upload_id_proof_back'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$upload_id_proof_back =$rr . '.pdf';
fwrite($file, base64_decode($encoded13));
fclose($file);

$esic_no = $_REQUEST['esic_no'];
//$esic_upload = $_REQUEST['esic_upload'];
$encoded14 = $_REQUEST['esic_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$esic_upload  =$rr . '.pdf';
fwrite($file, base64_decode($encoded14));
fclose($file);

$medical_examination_date =$_REQUEST['medical_examination_date'];
//$medical_fitness_copy = $_REQUEST['medical_fitness_copy'];
$encoded15 = $_REQUEST['medical_fitness_copy'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$medical_fitness_copy  =$rr . '.pdf';
fwrite($file, base64_decode($encoded15));
fclose($file);

$police_verification_date = $_REQUEST['police_verification_date'];
//$police_verification_copy = $_REQUEST['police_verification_copy'];
$encoded16 = $_REQUEST['police_verification_copy'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$police_verification_copy  =$rr . '.pdf';
fwrite($file, base64_decode($encoded16));
fclose($file);
$passport_no = $_REQUEST['passport_no'];
$encoded18 = $_REQUEST['passport_copy'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$passport_copy =$rr . '.pdf';
fwrite($file, base64_decode($encoded18));
fclose($file);
$passport_validity = $_REQUEST['passport_validity'];
$user_id = $_REQUEST['user_id'];
$created_datetime = date('Y-m-d H:i:s');
$valid_to = date('Y-m-d');
 
 
$medical_exam_date = date("Y-m-d", strtotime($medical_examination_date ."+6 month"));
$police_verification_date1 = date('Y-m-d', strtotime($police_verification_date . '+3 years'));


if($medical_exam_date < $police_verification_date1 && $medical_exam_date < $work_order_validity){
	
     $till_date=$medical_exam_date;
	 
}else if($medical_exam_date > $police_verification_date1 && $work_order_validity > $police_verification_date1){

 $till_date=$police_verification_date1;
 }else{
     $till_date=$work_order_validity;
 }
//Taking Division To Login User 
$user_id1 = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$user_id'");  
$user_div = sqlsrv_fetch_array($user_id1);
$division= $user_div['division_id'];

$transdate = date('Y-m-d');
       $transdate1 = date('m-d');
        $month = date('m', strtotime($transdate));
        $year  = date('Y', strtotime($transdate));
		
// Creating Sl No
$sl_no = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT sl FROM Clms_gatepass WHERE  YEAR(created_datetime)='$year' AND MONTH(created_datetime)='$month' AND division='$division' order by id DESC"));
   if ($sl_no['sl']) {
      $c_no = $sl_no['sl'] + 1;
    } else {
      $c_no = 1; 
    }

$division1 = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT abbreviation FROM divisions WHERE id='$division'"));
$abb=$division1['abbreviation'];
 $full_sl='CLGP'.'/'.$abb.'/'.$transdate1.'/'.$c_no;
if($gp_status){
    $gp_status1 = 'Renew';
}else{
    $gp_status1 = 'New';
}
$clms_insert = "INSERT INTO Clms_gatepass (sl,full_sl,work_order_no,work_order_validity,name,son_of,gender,date_of_birth,blood_group,address,mobile_no,job_role,upload_photo,education,board_name,upload_result,experience,skill_type,uan_no,uan_document,identity_proof,unique_id_no,upload_id_proof,upload_id_proof_back,esic,esic_document,medical_examination_date,upload_fittenss_copy,police_verification_date,police_verification_copy,division,status,created_by,created_datetime,valid_to,valid_till,passport_no,passport_copy,passport_validity,gp_status) VALUES ('$c_no','$full_sl','$work_order_no','$work_order_validity','$name','$son_of','$gender','$date_of_birth','$blood_group','$address','$mobile_no','$designation','$passport_size_photo','$education','$board_name','$upload_result','$experience','$skill_type','$uan_no','$uan_upload','$identity_proof','$unique_id_no','$upload_id_proof_front','$upload_id_proof_back','$esic_no','$esic_upload','$medical_examination_date','$medical_fitness_copy','$police_verification_date','$police_verification_copy','$division','Pending_executing','$user_id','$created_datetime','$valid_to','$till_date','$passport_no','$passport_copy','$pasport_validity','$gp_status1')";
$executeQuery_clms= sqlsrv_query($sqlcon, $clms_insert);

          if($executeQuery_clms){
                $message['response'] = "Successfully";
                echo json_encode($message); 
            }
            else{
                $message['response'] = "Not Successfully";
                echo json_encode($message);
              }
break;
// CLMS Form Insert End

//CLMS View Pending For Approval Start
case 'clms_view_pending';
$session_id = $_REQUEST['user_id'];
$role = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$session_id'");  
$clms_role1 = sqlsrv_fetch_array($role);
 $clms_role = $clms_role1['clm_role'];
  $vms_role = $clms_role1['vms_roll'];
 $division =$clms_role1['division_id'];
 $start = $_REQUEST["start"];
  $end   = $_REQUEST["end"];
            if($start == "" && $end ==""){
                $start= 1;
                $end =  9999;
            }

if($clms_role=='hr_dept'){

$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (


   SELECT Clms_gatepass.*,userlogins.name as vendor_name,ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass 
   INNER JOIN userlogins ON Clms_gatepass.created_by   = userlogins.id 
   where Clms_gatepass.division='$division' AND Clms_gatepass.status='Pending_for_hr' ) a WHERE a.row >= '$start' and a.row <= '$end'");

//$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //status='Pending_for_hr' ORDER BY id DESC");

}else if($clms_role=='Safety_dept'){



$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (
      SELECT Clms_gatepass.*,userlogins.name as vendor_name,ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass 
	   INNER JOIN userlogins ON Clms_gatepass.created_by   = userlogins.id 
	  where Clms_gatepass.division='$division' AND Clms_gatepass.status='Pending_for_safety' ) a WHERE a.row >= '$start' and a.row <= '$end'");

//$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //status='Pending_for_safety' ORDER BY id DESC");
}else if($clms_role=='plant_head'){

$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

   SELECT Clms_gatepass.*,userlogins.name as vendor_name,ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM  Clms_gatepass 
   INNER JOIN userlogins ON Clms_gatepass.created_by   = userlogins.id 
   where Clms_gatepass.division='$division' AND Clms_gatepass.status='Pending_for_plant_head' ) a  WHERE a.row >= '$start' and a.row <= '$end'");


   //$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //status='Pending_for_plant_head' ORDER BY id DESC");

}else if($clms_role=='Executing_agency'){

$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

   SELECT Clms_gatepass.*,userlogins.name as vendor_name, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM  Clms_gatepass 
   INNER JOIN userlogins ON Clms_gatepass.created_by   = userlogins.id
   where Clms_gatepass.division='$division' AND Clms_gatepass.status='Pending_executing' ) a  WHERE a.row >= '$start' and a.row <= '$end'");


   //$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //status='Pending_for_plant_head' ORDER BY id DESC");

}else{


$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (
  SELECT Clms_gatepass.*, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM  Clms_gatepass where division='$division' AND created_by='$session_id' AND (status='Pending_for_hr' OR status='Pending_for_safety' OR status='Pending_for_shift_incharge' OR status='Pending_for_plant_head') ) a HERE a.row >= '$start' and a.row <= '$end'");

    //$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //created_by='$session_id' AND (status='Pending_for_hr' OR status='Pending_for_safety' OR //status='Pending_for_shift_incharge' OR status='Pending_for_plant_head') ORDER BY id DESC");
}

            while($row = sqlsrv_fetch_array($clms_view))
                {

         $response[] = $row;

                }
echo json_encode($response);

break;
//CLMS View Pending For Approval End

//CLMS View Approved/Rejected/Completed Start
case 'clms_view_approved';
$session_id = $_REQUEST['user_id'];
$role = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$session_id'");  
$clms_role1 = sqlsrv_fetch_array($role);
 $clms_role = $clms_role1['clm_role'];
  $vms_role = $clms_role1['vms_roll'];
 $division =$clms_role1['division_id'];
$start = $_REQUEST["start"];
  $end   = $_REQUEST["end"];
            if($start == "" && $end ==""){
                $start= 1;
                $end =  9999;
            }

if($clms_role=='hr_dept'){ 
$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

SELECT Clms_gatepass.*,userlogins.name as vendor_name, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass
INNER JOIN userlogins ON Clms_gatepass.created_by   = userlogins.id 
 where Clms_gatepass.division='$division' AND Clms_gatepass.hr_by='$session_id' ) a WHERE a.row >= '$start' and a.row <= '$end'");


//$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //hr_by='$session_id' ORDER BY id DESC");

}else if($clms_role=='Safety_dept'){


$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

SELECT Clms_gatepass.*,userlogins.name as vendor_name,ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass
INNER JOIN userlogins ON Clms_gatepass.created_by   = userlogins.id 
where Clms_gatepass.division='$division' AND Clms_gatepass.safety_by='$session_id' ) a WHERE a.row >= '$start' and a.row <= '$end'");


//$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //safety_by='$session_id' ORDER BY id DESC");

}else if($clms_role=='plant_head'){


$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

    SELECT Clms_gatepass.*,userlogins.name as vendor_name,ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass 
	INNER JOIN userlogins ON Clms_gatepass.created_by   = userlogins.id 
	where Clms_gatepass.division='$division' AND Clms_gatepass.plant_head_by='$session_id') a WHERE a.row >= '$start' and a.row <= '$end'");

   //$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //plant_head_by='$session_id' ORDER BY id DESC");

}else if($clms_role=='Executing_agency'){


$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

    SELECT Clms_gatepass.*,userlogins.name as vendor_name, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass 
	INNER JOIN userlogins ON Clms_gatepass.created_by = userlogins.id 
	where Clms_gatepass.division='$division' AND Clms_gatepass.pending_excueting_by='$session_id') a WHERE a.row >= '$start' and a.row <= '$end'");

   //$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //plant_head_by='$session_id' ORDER BY id DESC");

}else if ($clms_role=='security' || $vms_role=='Security'){

$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

  SELECT Clms_gatepass.*, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass  where division='$division' AND status='Pending_for_security' ) a WHERE a.row >= '$start' and a.row <= '$end'");

     //$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //status='Pending_for_security' ORDER BY id DESC");
}else{

   $clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

    SELECT Clms_gatepass.*,userlogins.name as vendor_name,ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass  
	INNER JOIN userlogins ON Clms_gatepass.created_by = userlogins.id 
	where Clms_gatepass.division='$division' AND Clms_gatepass.created_by='$session_id' AND (Clms_gatepass.status='Pending_for_security' OR Clms_gatepass.status='Rejected')) a WHERE a.row >= '$start' and a.row <= '$end'");

   // $clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //created_by='$session_id' AND (status='Pending_for_security' OR status='Rejected') ORDER BY id DESC");
}

            while($row = sqlsrv_fetch_array($clms_view))
                {

         $response[] = $row;

                }
echo json_encode($response);

break;
//CLMS View Approved/Rejected/Completed End

case 'clms_view_pending_count';
$session_id = $_REQUEST['user_id'];
$role = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$session_id'");  
$clms_role1 = sqlsrv_fetch_array($role);
 $clms_role = $clms_role1['clm_role'];
  $vms_role = $clms_role1['vms_roll'];
 $division =$clms_role1['division_id'];
 
if($clms_role=='hr_dept'){
	
	
$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND status='Pending_for_hr'");

}else if($clms_role=='Safety_dept'){

$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND status='Pending_for_safety'");

}else if($clms_role=='plant_head'){
 
$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND status='Pending_for_plant_head'");


}else if($clms_role=='Executing_agency'){
	
$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND status='Pending_executing'");


}else if ($clms_role=='security' || $vms_role=='Security'){

     $clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (
       SELECT Clms_gatepass.*, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM  Clms_gatepass where division='$division' AND status='Pending_for_security' ) a WHERE a.row >= '$start' and a.row <= '$end'");
}else{
 
$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (
  SELECT Clms_gatepass.*, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM  Clms_gatepass where division='$division' AND created_by='$session_id' AND (status='Pending_for_hr' OR status='Pending_for_safety' OR status='Pending_for_shift_incharge' OR status='Pending_for_plant_head') ) a HERE a.row >= '$start' and a.row <= '$end'");

}
$count=0;
            while($row = sqlsrv_fetch_array($clms_view))
                {
            $count++;
                }
          $response = $count;      
echo json_encode($response);
break;

case 'clms_view_approved_count';


$session_id = $_REQUEST['user_id'];
$role = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$session_id'");  
$clms_role1 = sqlsrv_fetch_array($role);
 $clms_role = $clms_role1['clm_role'];
  $vms_role = $clms_role1['vms_roll'];
 $division =$clms_role1['division_id'];

if($clms_role=='hr_dept'){ 
/*$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

SELECT Clms_gatepass.*, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass where division='$division' AND hr_by='$session_id' ) a WHERE a.row >= '$start' and a.row <= '$end'");*/

//$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //hr_by='$session_id' ORDER BY id DESC");

$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND hr_by='$session_id'");


}else if($clms_role=='Safety_dept'){

/*$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

SELECT Clms_gatepass.*, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass where division='$division' AND safety_by='$session_id' ) a WHERE a.row >= '$start' and a.row <= '$end'");*/

//$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //safety_by='$session_id' ORDER BY id DESC");

$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND safety_by='$session_id'");

}else if($clms_role=='plant_head'){


/*$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

    SELECT Clms_gatepass.*, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass where division='$division' AND plant_head_by='$session_id') a WHERE a.row >= '$start' and a.row <= '$end'");

   //$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //plant_head_by='$session_id' ORDER BY id DESC");*/
$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND plant_head_by='$session_id'");

}else if($clms_role=='Executing_agency'){


/*$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

    SELECT Clms_gatepass.*, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass where division='$division' AND pending_excueting_by='$session_id') a WHERE a.row >= '$start' and a.row <= '$end'");*/

   //$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //plant_head_by='$session_id' ORDER BY id DESC");
$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND pending_excueting_by='$session_id'");

}else if ($clms_role=='security' || $vms_role=='Security'){

$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

  SELECT Clms_gatepass.*, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass  where division='$division' AND status='Pending_for_security' ) a WHERE a.row >= '$start' and a.row <= '$end'");

     //$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //status='Pending_for_security' ORDER BY id DESC");
}else{

   /*$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM (

    SELECT Clms_gatepass.*, ROW_NUMBER() OVER (ORDER BY Clms_gatepass.id DESC) as row  FROM Clms_gatepass  where division='$division' AND created_by='$session_id' AND (status='Pending_for_security' OR status='Rejected')) a WHERE a.row >= '$start' and a.row <= '$end'");*/

   // $clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND //created_by='$session_id' AND (status='Pending_for_security' OR status='Rejected') ORDER BY id DESC");
$clms_view  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where division='$division' AND created_by='$session_id' AND (status='Pending_for_security' OR status='Rejected')");

}
$count=0;
            while($row = sqlsrv_fetch_array($clms_view))
                {

         $count++;

                }
       $response= $count;
echo json_encode($response);

break;
//CLMS Details Start
case 'clms_details';
$id=$_REQUEST['id'];
$user_id =$_REQUEST['user_id'];
$role = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$user_id'");  
$role_1 = sqlsrv_fetch_array($role);
$vms_role=$role_1['vms_roll'];
$clms_role = $role_1['clm_role'];
$div=$role_1['division_id'];
$link = "http://aiplbaradwari.ddns.net:5002//jamipol_vms/public/documents/clm_pics/";
$vms_details  = sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where id='$id'");
$row = sqlsrv_fetch_array($vms_details);
$response['id']=$row['id'];    
$response['full_sl']=$row['full_sl'];

$vendor=$row['created_by'];
$ven = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$vendor'");  
$vendor_name = sqlsrv_fetch_array($ven);
$response['vendor_name']=$vendor_name['name'];
$response['work_order_no']=$row['work_order_no']; 
$response['work_order_validity']=$row['work_order_validity'];
$response['name']=$row['name']; 
$response['son_of']=$row['son_of'];
$response['gender']=$row['gender'];
$response['date_of_birth']=$row['date_of_birth'];
$response['mobile_no']=$row['mobile_no'];
$response['identity_proof']=$row['identity_proof'];
$response['unique_id_no']=$row['unique_id_no'];
$response['upload_id_proof_front']=$link.$row['upload_id_proof'];
$response['upload_id_proof_back']=$link.$row['upload_id_proof_back'];
$response['education']=$row['education'];
$response['board_name']=$row['boared_name'];
$response['upload_result']=$link.$row['upload_result'];
$response['uan_no']=$row['uan_no'];
$response['uan_document']=$link.$row['uan_document'];
$response['esic']=$row['esic'];
$response['esic_document']=$link.$row['esic_document'];
$response['blood_group']=$row['blood_group'];
$response['medical_examination_date']=$row['medical_examination_date'];
$response['upload_fittenss_copy']=$link.$row['upload_fittenss_copy'];
$response['police_verification_date']=$row['police_verification_date'];
$response['police_verification_copy']=$link.$row['police_verification_copy'];
$response['upload_photo']=$link.$row['upload_photo'];
$response['valid_to']=$row['valid_to'];
$response['valid_till']=$row['valid_till'];
$response['passport_no']=$row['passport_no'];
$response['passport_copy']=$link.$row['passport_copy'];
$response['passport_validity']=$row['passport_validity'];
$response['hr_id']=$row['hr_by'];       
$hr=$row['hr_by'];
$hr_n = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$hr'");  
$hr_na = sqlsrv_fetch_array($hr_n);
$response['hr_name']=$hr_na['name'];
$response['hr_remarks']=$row['hr_remarks'];  
$response['hr_decision']=$row['hr_decision'];
$response['hr_datetime']=$row['hr_datetime'];

$response['pending_excueting_by']=$row['pending_excueting_by'];       
$excuting_by=$row['pending_excueting_by'];
$excuting_qw = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$excuting_by'");  
$excuting = sqlsrv_fetch_array($excuting_qw);
$response['excuting_name']=$excuting['name'];
$response['excuting_remarks']=$row['pending_excuting_remarks'];  
$response['excuting_decision']=$row['pending_excuting_decision'];
$response['excuting_datetime']=$row['pending_eccuting_date'];


$response['safety_id']=$row['safety_by'];
$safety=$row['safety_by'];
$sa_n = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$safety'");  
$sa_na = sqlsrv_fetch_array($sa_n);
$response['safety_name']=$sa_na['name'];
$response['safety_remarks']=$row['safety_remarks'];  
$response['safety_decision']=$row['safety_decision'];
$response['safety_datetime']=$row['safety_datetime'];

$response['safety_training_by']=$row['safety_training_by'];
$safety_training_id=$row['safety_training_by'];
$sa_training = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$safety_training_id'");  
$sa_train = sqlsrv_fetch_array($sa_training);
$response['safety_training_name']=$sa_train['name'];
$response['safety_training_date']=$row['safety_training_date'];  
$response['safety_training_time']=$row['safety_training_time'];
$response['safety_training_decision']=$row['safety_training_decision'];
$response['safety_induction_number']=$row['safety_pass_no'];

$response['plant_head_id']=$row['plant_head_by'];
$plant_head_by=$row['plant_head_by'];
$plan = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$plant_head_by'");  
$plan_head = sqlsrv_fetch_array($plan);
$response['plant_head_name']=$plan_head['name'];
$response['plant_head_remarks']=$row['plant_head_remarks'];  
$response['plant_head_decision']=$row['plant_head_decision'];
$response['plant_head_datetime']=$row['plant_head_datetime'];

$response['status']=$row['status'];
$status=$row['status'];
$division=$row['division'];

         if($clms_role=='hr_dept' && $status=='Pending_for_hr' && $div==$division){
           $response['Pending_Role']='hr_dept';
         }else if($clms_role=='Safety_dept' && $status=='Pending_for_safety' && $div==$division){
              $response['Pending_Role']='Safety_dept';
         }else if($clms_role=='Executing_agency' && $status=='Pending_executing' && $div==$division){
                   $response['Pending_Role']='Executing_agency';
         }else if($clms_role =='plant_head' && $status=='Pending_for_plant_head' && $div==$division){
                 $response['Pending_Role']='plant_head';
            }else{
            $response['Pending_Role']='Null';
         }
         //}

  echo json_encode($response);
break;

//CLMS Details End

//CLMS Approves Update Start 
case 'clms_approve_update';
$id=$_REQUEST['id'];
$pending_role=$_REQUEST['pending_role'];
$decision = $_REQUEST['decision'];
$user_id=$_REQUEST['user_id'];
$date=date('Y-m-d H:i:s');
$remarks=$_REQUEST['remarks'];
$safety_training_by=$_REQUEST['safety_training_by'];
$safety_training_date=$_REQUEST['safety_training_date']; 
//if($safety_training_date==''){
//	$safety_training_date1=='0000-00-00';
//}else{
	
//}
$safety_training_time = $_REQUEST['safety_training_time'];
//Pending_for_safety

if($pending_role=='hr_dept'){
    if($decision=='Approve'){
        $status='Pending_for_safety';
    }else{
        $status='Rejected';
    }
 $query = "UPDATE Clms_gatepass 
                            SET
                            hr_by            = '$user_id',
                            hr_decision      = '$decision',
                            hr_datetime      = '$date',
                            hr_remarks       = '$remarks',
                            status           = '$status'
                            WHERE id='$id'";
}elseif($pending_role=='Executing_agency'){
    if($decision=='Approve'){
        $status='Pending_for_hr';
    }else{
        $status='Rejected';
    }
 $query = "UPDATE Clms_gatepass 
                            SET
                            pending_excueting_by  = '$user_id',
                            pending_excuting_decision      = '$decision',
                            pending_eccuting_date      = '$date',
                            pending_excuting_remarks       = '$remarks',
                            status           = '$status'
                            WHERE id='$id'";
}else if($pending_role=='Safety_dept' && $safety_training_date !='') {


if($decision=='Approve'){
        $status='Pending_for_safety';
    }else{
        $status='Rejected';
    }

$query = "UPDATE Clms_gatepass 
                            SET
                            safety_training_by      ='$user_id',
                            safety_training_date    ='$safety_training_date',
                            safety_training_time    = '$safety_training_date',
                            safety_training_decision = '$decision',
                            status                   ='$status'
                            WHERE id='$id'";

}else if($pending_role=='Safety_dept') {
if($decision=='Approve'){
        $status='Pending_for_plant_head';

    }else{
        $status='Rejected';
    }

$transdate = date('Y-m-d');
$month = date('m', strtotime($transdate));
$year  = date('Y', strtotime($transdate));
$transdate1 = date('mY');


$divv1 =sqlsrv_query($sqlcon,"SELECT * FROM Clms_gatepass where YEAR(safety_datetime)='$year' and MONTH(safety_datetime)='$month'");  
$divv = sqlsrv_fetch_array($divv1);

        if ($divv['safety_no'])
        {
            $v=$divv['safety_no'];
            $v++;
            $serial_no_gp=$v;
        }
        else{
            $serial_no_gp="1"; 
        }


$division1 = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$user_id'");  
$division= sqlsrv_fetch_array($division1);
$DIv=$division['division_id'];
		
$division11 = sqlsrv_query($sqlcon,"SELECT * FROM divisions where id='$DIv'");  
$divv1= sqlsrv_fetch_array($division11);		
		
         
        if ($divv1['id'])
        {
            $v=$divv1['abbreviation'];
           
            $abb=$v;
        }
   
   $full_gp='JAM'.'/'.$abb.'/'.$transdate1.'/'.$serial_no_gp;

$query = "UPDATE Clms_gatepass 
                        SET
                            safety_by      ='$user_id',
                            safety_datetime      = '$date',
                            safety_remarks       = '$remarks',
                            safety_decision      = '$decision',
                            status               ='$status',
							safety_no            ='$serial_no_gp',
                            safety_pass_no		 ='$full_gp'				
                            WHERE id='$id'";



}else if($pending_role=='plant_head') {
if($decision=='Approve'){
        $status='Pending_for_security';
    }else{
        $status='Rejected';
    }
$query = "UPDATE Clms_gatepass 
                        SET
                             plant_head_by      ='$user_id',
                             plant_head_datetime      = '$date',
                             plant_head_remarks       = '$remarks',
                             plant_head_decision      = '$decision',
                             status                   ='$status'
                             WHERE id='$id'";

}else{
    $query='Null';
}

 $executeQuery = sqlsrv_query($sqlcon,$query);
 if($executeQuery){
                $message['response'] = "Successfully";
                echo json_encode($message); 
            }
            else{
                $message['response'] = "Not Successfully";
                echo json_encode($message);
              }
break;
// CLMS Approved Update End

//CLMS END

//SAFETY START
//Safety Insert Start
case 'safety_insert';
$financial_year_month=$_REQUEST['financial_year_month'];
$branch =$_REQUEST['branch'];
$q1 = $_REQUEST['q1'];
//$q1_upload = $_REQUEST['q1_upload'];
$encoded1 = $_REQUEST['q1_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q1_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded1));
fclose($file);

$q2 = $_REQUEST['q2'];
//$q2_upload = $_REQUEST['q2_upload'];

$encoded2 = $_REQUEST['q2_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q2_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded2));
fclose($file);

$q3 = $_REQUEST['q3'];
//$q3_upload = $_REQUEST['q3_upload'];
$encoded3 = $_REQUEST['q3_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q3_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded3));
fclose($file);

$q4 = $_REQUEST['q4'];
//$q4_upload = $_REQUEST['q4_upload'];
$encoded4 = $_REQUEST['q4_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q4_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded4));
fclose($file);

$q5 = $_REQUEST['q5'];
//$q5_upload = $_REQUEST['q5_upload'];
$encoded5 = $_REQUEST['q5_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q5_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded5));
fclose($file);

/*$q6 = $_REQUEST['q6'];
//$q6_upload = $_REQUEST['q6_upload'];

$encoded6 = $_REQUEST['q6_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.png';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q6_upload =$rr . '.png';
fwrite($file, base64_decode($encoded6));
fclose($file);

$q7 = $_REQUEST['q7'];
//$q7_upload = $_REQUEST['q7_upload'];
$encoded7 = $_REQUEST['q7_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.png';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q7_upload =$rr . '.png';
fwrite($file, base64_decode($encoded7));
fclose($file);

$q8 = $_REQUEST['q8'];
//$q8_upload = $_REQUEST['q8_upload'];

$encoded8 = $_REQUEST['q8_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.png';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q8_upload =$rr . '.png';
fwrite($file, base64_decode($encoded8));
fclose($file);

$q9 = $_REQUEST['q9'];
//$q9_upload = $_REQUEST['q9_upload'];

$encoded9 = $_REQUEST['q9_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.png';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q9_upload =$rr . '.png';
fwrite($file, base64_decode($encoded9));
fclose($file);*/


$q10 = $_REQUEST['q10'];
//$q10_upload = $_REQUEST['q10_upload'];

$encoded10 = $_REQUEST['q10_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q10_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded10));
fclose($file);

$q11 = $_REQUEST['q11'];
//$q11_upload = $_REQUEST['q11_upload'];

$encoded11 = $_REQUEST['q11_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q11_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded11));
fclose($file);

$q12 = $_REQUEST['q12'];
//$q12_upload = $_REQUEST['q12_upload'];

$encoded12 = $_REQUEST['q12_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q12_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded12));
fclose($file);

$q13 = $_REQUEST['q13'];
//$q13_upload = $_REQUEST['q13_upload'];
$encoded13 = $_REQUEST['q13_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q13_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded13));
fclose($file);

$q14 = $_REQUEST['q14'];
//$q14_upload = $_REQUEST['q14_upload'];
$encoded14 = $_REQUEST['q14_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q14_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded14));
fclose($file);

$q15 = $_REQUEST['q15'];
//$q15_upload = $_REQUEST['q15_upload'];

$encoded15 = $_REQUEST['q15_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q15_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded15));
fclose($file);

$q16 = $_REQUEST['q16'];
//$q16_upload = $_REQUEST['q16_upload'];

$encoded16 = $_REQUEST['q16_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q16_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded16));
fclose($file);


$q17 = $_REQUEST['q17'];
//$q17_upload = $_REQUEST['q17_upload'];
$encoded17 = $_REQUEST['q17_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.pdf';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q17_upload =$rr . '.pdf';
fwrite($file, base64_decode($encoded17));
fclose($file);

$q18 = $_REQUEST['q18'];
//$q18_upload = $_REQUEST['q18_upload'];
$encoded18 = $_REQUEST['q18_upload'];
$rr =uniqid();
$rand='public/documents/clm_pics/'. $rr . '.png';
$file = fopen('../'.$rand, "wb"); //(you can put jpg, png or any other extension)
$q18_upload =$rr . '.png';
fwrite($file, base64_decode($encoded18));
fclose($file);

$T1 = $_REQUEST['T1'];
$T2 = $_REQUEST['T2'];
$T3 = $_REQUEST['T3'];
$T4 = $_REQUEST['T4'];
$T5 = $_REQUEST['T5'];
$T6 = $_REQUEST['T6'];
$T7 = $_REQUEST['T7'];
$T8 = $_REQUEST['T8'];
$T9 = $_REQUEST['T9'];
$T10 = $_REQUEST['T10'];
$remarks = $_REQUEST['remarks'];
$created_by = $_REQUEST['user_id'];
$created_datetime = date('Y-m-d H:i:s');



$safety_insert = "INSERT INTO safety_data_entry (financial_year,month,division_id,q1,q1_upload,q2,q2_upload,q3,q3_upload,q4,q4_upload,q5,q5_upload,q6,q6_upload,q7,q7_upload,q8,q8_upload,q9,q9_upload,q10,q10_upload,q11,q11_upload,q12,q12_upload,q13,q13_upload,q14,q14_upload,q15,q15_upload,q16,q16_upload,q17,q17_upload,q18,q18_upload,T1,T2,T3,T4,T5,T6,T7,T8,T9,T10,remarks,created_by,created_datetime) VALUES ('$financial_year_month','$financial_year_month','$branch','$q1','$q1_upload','$q2','$q2_upload','$q3','$q3_upload','$q4','$q4_upload','$q5','$q5_upload','$q6','$q6_upload','$q7','$q7_upload','$q8','$q8_upload','$q9','$q9_upload','$q10','$q10_upload','$q11','$q11_upload','$q12','$q12_upload','$q13','$q13_upload','$q14','$q14_upload','$q15','$q15_upload','$q16','$q16_upload','$q17','$q17_upload','$q18','$q18_upload','$T1','$T2','$T3','$T4','$T5','$T6','$T7','$T8','$T9','$T10','$remarks','$created_by','$created_datetime')";

 $executeQuery_safety= sqlsrv_query($sqlcon, $safety_insert);

          if($executeQuery_safety){
                $message['response'] = "Successfully";
                echo json_encode($message); 
            }
            else{
                $message['response'] = "Not Successfully";
                echo json_encode($message);
              }

break;

//Safety Insert End
//Safety Data View Start
case 'safety_data_view';
$session_id = $_REQUEST['user_id'];
$user_sub_type =$_REQUEST['user_sub_type'];
$role = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$session_id'");  
$role_1 = sqlsrv_fetch_array($role);
$div=$role_1['division_id'];
$start = $_REQUEST["start"];
  $end   = $_REQUEST["end"];
            if($start == "" && $end ==""){
                $start= 1;
                $end =  99999;
            }

if($user_sub_type=='3'){
//SELECT * FROM (

 //  SELECT safety_data_entry.*,divisions.name as division_name,  ROW_NUMBER() OVER (ORDER BY safety_data_entry.id DESC) as row  FROM safety_data_entry INNER JOIN divisions ON safety_data_entry.division_id   = divisions.id) a WHERE a.row >= '$start' and a.row <= '$end'
$safety_date  = sqlsrv_query($sqlcon,"SELECT * FROM (

   SELECT safety_data_entry.*,divisions.name as division_name,  ROW_NUMBER() OVER (ORDER BY safety_data_entry.id DESC) as row  FROM safety_data_entry INNER JOIN divisions ON safety_data_entry.division_id   = divisions.id) a WHERE a.row >= '$start' and a.row <= '$end'");

$safety_date  = sqlsrv_query($sqlcon,"SELECT * FROM safety_data_entry");


}else{

$safety_date  = sqlsrv_query($sqlcon,"SELECT * FROM (

    SELECT safety_data_entry.*,divisions.name as division_name,  ROW_NUMBER() OVER (ORDER BY safety_data_entry.id DESC) as row  FROM safety_data_entry INNER JOIN divisions ON safety_data_entry.division_id   = divisions.id where division_id='$div' AND created_by='$session_id') a WHERE a.row >= '$start' and a.row <= '$end'");
//$safety_date  = sqlsrv_query($sqlcon,"SELECT * FROM safety_data_entry where division_id='$div' AND created_by='$session_id'  ORDER BY id DESC LIMIT '$start','$end'");

    //$safety_date  = sqlsrv_query($sqlcon,"SELECT * FROM safety_data_entry where division_id='$div' AND //created_by='$session_id' ORDER BY id DESC");
}



 while($row = sqlsrv_fetch_array($safety_date))
                {
        // $response[] = $row;
		$response['id'] = $row['id'];
       $response['financial_year'] = date('Y',strtotime($row['financial_year']));
	   $response['month'] = date('F',strtotime($row['month']));
	   $division = $row['division_id'];
            $sql33 = sqlsrv_query($sqlcon,"SELECT * FROM divisions where id ='$division'");
        $row33 = sqlsrv_fetch_array($sql33);
        $response['division_name']= $row33['name'];
		$response2[]=$response;
	  
                }
 echo json_encode($response2);

break;
case 'safety_data_view_count';
$session_id = $_REQUEST['user_id'];
$user_sub_type =$_REQUEST['user_sub_type'];
$role = sqlsrv_query($sqlcon,"SELECT * FROM userlogins where id='$session_id'");  
$role_1 = sqlsrv_fetch_array($role);
$div=$role_1['division_id'];

if($user_sub_type=='3'){
	
$safety_date  = sqlsrv_query($sqlcon,"SELECT * FROM  safety_data_entry ORDER BY id DESC");

}else{
$safety_date  = sqlsrv_query($sqlcon,"SELECT * FROM  safety_data_entry WHERE division_id='$div' AND created_by='$session_id' ORDER BY id DESC");

}

$count=0;

 while($row = sqlsrv_fetch_array($safety_date))
                {

           $count++;
            
                }
        $response = $count;
echo json_encode($response);
break;
//Safety Data View End

//Safety Data Daetails Start 
case 'safety_data_details';
$id=$_REQUEST['id'];
$safety_details  = sqlsrv_query($sqlcon,"SELECT * FROM safety_data_entry where id='$id'");
$row = sqlsrv_fetch_array($safety_details);
$response['id']=$row['id'];    
$response['financial_year']=date('Y',strtotime($row['financial_year']));
$response['month']=date('F',strtotime($row['month']));
$division=$row['division_id'];
$div = sqlsrv_query($sqlcon,"SELECT * FROM divisions where id='$division'");  
$div_name = sqlsrv_fetch_array($div);
$response['division_name']=$div_name['name'];
$link = "http://aiplbaradwari.ddns.net:5002//jamipol_vms/public/documents/clm_pics/";
$response['q1']=$row['q1']; 
$response['q1_upload']=$link.$row['q1_upload'];
$response['q2']=$row['q2'];
$response['q2_upload']=$link.$row['q2_upload'];
$response['q3']=$row['q3'];
$response['q3_upload']=$link.$row['q3_upload'];
$response['q4']=$row['q4'];
$response['q4_upload']=$link.$row['q4_upload'];
$response['q5']=$row['q5'];
$response['q5_upload']=$link.$row['q5_upload'];
$response['q6']=$row['q6'];
$response['q6_upload']=$link.$row['q6_upload'];
$response['q7']=$row['q7'];
$response['q7_upload']=$link.$row['q7_upload'];
$response['q8']=$row['q8'];
$response['q8_upload']=$link.$row['q8_upload'];
$response['q9']=$row['q9'];
$response['q9_upload']=$link.$row['q9_upload'];
$response['q10']=$row['q10'];
$response['q10_upload']=$link.$row['q10_upload'];
$response['q11']=$row['q11'];
$response['q11_upload']=$link.$row['q11_upload'];
$response['q12']=$row['q12'];
$response['q12_upload']=$link.$row['q12_upload'];
$response['q13']=$row['q13'];
$response['q13_upload']=$link.$row['q13_upload'];
$response['q14']=$row['q14'];
$response['q14_upload']=$link.$row['q14_upload'];
$response['q15']=$row['q15'];
$response['q15_upload']=$link.$row['q15_upload'];
$response['q16']=$row['q16'];
$response['q16_upload']=$link.$row['q16_upload'];
$response['q17']=$row['q17'];
$response['q17_upload']=$link.$row['q17_upload'];
$response['q18']=$row['q18'];
$response['q18_upload']=$link.$row['q18_upload'];
$response['T1']=$row['T1'];
$response['T2']=$row['T2'];
$response['T3']=$row['T3'];
$response['T4']=$row['T4'];
$response['T5']=$row['T5'];
$response['T6']=$row['T6'];
$response['T7']=$row['T7'];
$response['T8']=$row['T8'];
$response['T9']=$row['T9'];
$response['T10']=$row['T10'];
$response['remarks']=$row['remarks'];

 echo json_encode($response);
break;
//Safety Data Daetails End 

    } //end of switch
}//End of if
else {
    echo "Token Not Matched";
}
?>