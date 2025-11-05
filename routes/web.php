<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('clear', function () {
	$exitCode = Artisan::call('config:cache');
	$exitCode = Artisan::call('config:clear');
	$exitCode = Artisan::call('cache:clear');
	$exitCode = Artisan::call('view:clear');
	$exitCode = Artisan::call('optimize:clear');
	Session::flash('success', 'All Clear');
	echo "DONE";
});

Route::get('404', function () {
	return view('admin.error.404');
});


// Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/', 'LoginController@index')->name('/'); //index page view
//Auth::routes();
Route::get('login/', 'LoginController@index')->name('login'); //index page view
Route::get('captcha', 'LoginController@captcha');
Route::get('/loginPost', 'LoginController@LoginPage')->name('loginPost'); //view to login page
Route::post('/loginPost', 'LoginController@LoginPost')->name('loginPost'); //Successful login
Route::get('/forgotPage', 'RegisterController@forgotPage'); //view register page
Route::post('/forgotPost', 'RegisterController@forgotPost')->name('forgotPost'); //Successful Register
Route::get('/RegisterGatepass', 'GatePassRegisterController@index'); //Successful Register
Route::get('/otpPage', 'LoginController@OTPPage')->name('otpPage'); //view to OTP Page
Route::post('/otpPost', 'LoginController@OTPPost')->name('otpPost'); //Successful OTP Check
Route::get('/logout1', 'LoginController@logout1')->name('logout1'); //logout
Route::resource('vms', 'VMSController');
Route::resource('vms_ifream', 'VMS_ifreamController');
Route::resource('vendor_mis', 'Vendor_misController');
Route::resource('vendor_silo', 'Vendor_siloController');
Route::post('update_return', 'VMS_ifreamController@update_return')->name('vms_ifream.update_return');
Route::post('update_surrender', 'VMS_ifreamController@update_surrender')->name('vms_ifream.update_surrender');
Route::get('vms/edit_return_iframe/{id?}/{user_id}', 'VMS_ifreamController@edit_return')->name('vms_ifream.edit_return');
Route::get('vms/edit_return/{id?}', 'VMSController@edit_return')->name('vms.edit_return');
Route::prefix('vms_ifream')->name('vms_ifream.')->group(function () {
	Route::get('edit_driver_details/{id?}/{user_id}', 'VMS_ifreamController@edit_driver_details')
		->name('edit_driver_details');
});

Route::prefix('vms')->name('vms.')->group(function () {
	Route::get('edit_driver_details/{id?}', 'VMSController@edit_driver_details')
		->name('edit_driver_details');
	Route::get('vms_report/{id?}', 'VMSController@vms_report')->name('vms_report');
	Route::get('vms_dashboard/{id?}', 'VMSController@vms_dashboard')->name('vms_dashboard');
});
Route::get('vendor_mis/mis_report/{id?}', 'Vendor_misController@mis_report')->name('mis_report');
Route::get('vendor_mis/mis_dashboard/{id?}', 'Vendor_misController@mis_dashboard')->name('mis_dashboard');
Route::post('returnsilo', 'Vendor_siloController@return_silo')->name('vendor_silo.returnsilo');

// web.php
Route::get('/vehicle/get/{id}', 'VMSController@getVehicle')->name('vehicle.get');


Route::post('edit_data_update', 'Vendor_misController@edit_data_update')->name('vendor_mis.edit_data_update');
Route::get('vendor_mis/edit_entry/{id?}', 'Vendor_misController@edit_entry')->name('vendor_mis.edit_entry');
Route::get('vendor_mis/edit_data_ifream/{id?}/{user_id}', 'Vendor_misController@edit_data_ifream')->name('vendor_mis.edit_data_ifream');
Route::post('update_data', 'Vendor_misController@update_data')->name('vendor_mis.update_data');
Route::get('vendor_mis/edit_ifream/{id?}/{user_id}', 'Vendor_misController@edit_ifream')->name('vendor_mis.edit_ifream');
Route::get('vendor_mis/create_ifream/{id?}', 'Vendor_misController@create_ifream')->name('vendor_mis.create_ifream');
Route::post('update_return_approval/{vms_id?}', 'VMS_ifreamController@update_return_approval')->name('vms_ifream.update_return_approval');
Route::get('vendor_silo/create_ifream/{id?}', 'Vendor_siloController@create_ifream')->name('vendor_silo.create_ifream');
Route::post('update_driver_details', 'VMS_ifreamController@update_driver_details')->name('vms_ifream.update_driver_details');

Route::get('vendor_silo/edit_ifream/{id?}/{user_id}', 'Vendor_siloController@edit_ifream')->name('vendor_silo.edit_ifream');

Route::get('vms_ifream/{id}/{user_id}', 'VMS_ifreamController@edit')
	->name('vms_ifream.edit');
Route::get('vendor_silo/edit_entry/{id?}', 'Vendor_siloController@edit_entry')->name('vendor_silo.edit_entry');
Route::get('vendor_silo/edit_data_ifream/{id?}/{user_id}', 'Vendor_siloController@edit_data_ifream')->name('vendor_silo.edit_data_ifream');
Route::get('vendor_silo/index_silo/{id?}', 'SiloDailyInspectionController@index_silo_daily')
	->name('vendor_silo.index_silo_daily');
Route::get('vendor_silo/edit_silo_daily/{id?}', 'SiloDailyInspectionController@edit_silo_daily')
	->name('vendor_silo.edit_silo_daily');
Route::get('vendor_silo/edit_data_ifream_silo_daily/{id?}/{user_id}', 'SiloDailyInspectionController@edit_data_ifream_silo_daily')
	->name('vendor_silo.edit_data_ifream_silo_daily');

Route::group(['middleware' => 'auth'], function () {
	Route::post('/logout', 'LoginController@logout')->name('logout');
	Route::get('/', 'LoginController@dashboard')->name('/');
	//Route::get('/' ,'LoginController@vendor_details')->name('/');
	Route::get('/RequestVGatepass', 'GatepassController@index');
	Route::get('/vms_safety', 'GatepassController@indexs');

	Route::get('/CLMSGatepass', 'GatePassCLMSController@index'); //Successful Register

	Route::post('/RequestVGatepassPost', 'GatepassController@RequestVGatepassPost')->name('RequestVGatepassPost'); //Successful Register
	Route::get('/Safety_data_entry', 'SafetyDataEntryController@index');
	Route::get('admin/permit/{text}/{id}', 'ListPermitController@ShowPrint');




	Route::group(['as' => 'admin.', 'middleware' => 'register', 'prefix' => 'admin'], function () {
		Route::post('power-ge/IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wllatting-update', 'ListPermitController@PermitReturnPG')->name('power-getting-update');
		Route::get('view-power-getting/{id?}', 'ListPermitController@viewGetting')->name('viewGetting');
		Route::get('power-cutting-issuer/{id?}', 'ListPermitController@PowerCuttingIssuer')->name('get_powercutting_issuer');
		Route::post('power-cutting-user', 'ListPermitController@powerCutting')->name('powercutting');
		Route::get('view-power-cutting/{id?}', 'ListPermitController@viewPower')->name('viewPower');
		Route::post('gate-pass-import', 'ListPermitController@GatePassImport')->name('gatepass_import');
		Route::get('gatepass_approvals/approve', 'GatepassController@index1')->name('approve.index');
		Route::get('gatepass_approvals/approve_t', 'GatepassController@indext')->name('approve_t.index');
		//Route::get('gatepass_approvals/vms_report','GatepassController@GetReport')->name('vms_report');
//Route::get('gatepass_approvals/vms_report','GatepassController@GetReport')->name('vms_report');
		Route::get('gatepass_approvals/vms_report', 'GatepassController@report')->name('vms_report.index');
		//Route::get('vms_report','GatepassController@GetReport')->name('listall_report.index');
		Route::post('vms_report', 'GatepassController@GetReport')->name('vms_report');
		Route::get('gatepass_approvals/approve_clms', 'GatePassCLMSController@index1')->name('approve_clms.index');
		//Successful Register
		Route::get('gatepass_approvals/approve_clms_t', 'GatePassCLMSController@indext')->name('approve_clms_t.index');
		Route::get('gatepass_approvals/clms_report', 'GatePassCLMSController@report')->name('clms_report.index');
		Route::post('clms_report', 'GatePassCLMSController@GetReport')->name('clms_report');

		Route::get('gatepass_approvals/safety_data_view', 'SafetyDataEntryController@index1')->name('safety_data_view.index');//Successful Register


		//Route::post('/loginPost','LoginController@LoginPost')->name('loginPost'); 


		Route::get('gatepass_approvals/safety_report', 'SafetyDataEntryController@report')->name('safety_report.index');
		Route::post('safety_report', 'SafetyDataEntryController@GetReport')->name('safety_report');

		Route::get('gatepass_approvals/edit/{id?}', 'GatepassController@edit')->name('edit.edit');

		//Successful Register
		Route::get('gatepass_approvals/edit_clms/{id?}', 'GatePassCLMSController@edit')->name('edit_clms.edit');
		//new formate
		Route::get('gatepass_approvals/edit_clms_new/{id?}', 'GatePassCLMSController@edit_new')->name('edit_clms_new.edit');


		//new formate
		Route::get('gatepass_approvals/renew_clms/{id?}', 'GatePassCLMSController@edit_renew')->name('renew_clms.edit');

		Route::get('gatepass_approvals/edit_safety_data/{id?}', 'SafetyDataEntryController@edit')->name('edit_safety_data.edit');

		Route::get('gatepass_approvals/edit_safety_data_draft/{id?}', 'SafetyDataEntryController@edit_draft')->name('edit_safety_data_draft.edit');

		Route::get('users/edit_clms1/{id?}', 'vendorController@edit1')->name('edit_clms1.edit');

		Route::get('users/edit_clms1/{id?}', 'vendorController@edit1')->name('edit_clms1.edit');

		// exit emp 
		Route::get('gatepass_approvals/exit_emp_deatils/{id?}', 'GatePassCLMSController@exit_details')->name('exit_emp_details.edit');


		Route::get('gatepass_approvals/printg/{id?}', 'GatepassController@printg')->name('printg.printg'); //Successful Register
		Route::get('gatepass_approvals/printg_clms/{id?}', 'GatePassCLMSController@printg')->name('printg_clms.printg'); //Successful Register
		Route::get('gate-pass', 'ListPermitController@GatePassView')->name('gate_pass');
		Route::post('work-order-import', 'ListPermitController@WorkOrderimport')->name('work_order_import');
		Route::post('daily-attendence-import', 'GatePassCLMSController@Dailyattendenceimport')->name('daily_attendence_import');
		Route::get('work-order-view', 'ListPermitController@WorkOrderView')->name('work_order_view');
		Route::get('daily-attendence-import', 'GatePassCLMSController@DailyattendenceView')->name('daily_attendence_upload');
		Route::match(['get', 'post'], 'daily-attendence-view', 'GatePassCLMSController@DailyattendenceViewtable')
			->name('daily_attendence_view');
		Route::match(['get', 'post'], 'bonus-calculation', 'GatePassCLMSController@bonus_calculation2')
			->name('bonus_calculation');
		Route::match(['get', 'post'], 'ot-calculation', 'GatePassCLMSController@ot_calculation2')
			->name('ot_calculation');
		Route::post('getuserlist', 'UserController@getListUsers')->name('getuserlist');
		Route::post('renew-issuer', 'ListPermitController@RenewUpdate')->name('renew_issuer');
		Route::get('renew-view/{id?}', 'ListPermitController@RenewView')->name('renew_view');
		Route::post('renew', 'ListPermitController@Renew_Permit')->name('renew');
		Route::get('expired-download', 'ListPermitController@DownloadExpiredGatePass');
		Route::get('shutdown/{id?}', 'UserController@DeleteShutDown')->name('shutdown');
		Route::get('del-gate-pass/{id?}', 'UserController@DeleteGatePass')->name('delgatepass');
		Route::get('del/{id?}', 'UserController@DeleteSupervisior')->name('delsuper');
		Route::post('get-department', 'DepartmentController@DepartmentFilter')->name('getdepartmentlist');
		Route::post('getjoblist', 'JobController@getListJobs')->name('getjoblist');
		Route::get('getpass/{id?}/{name?}', 'PermitController@get')->name('autocomplete_gate_pass');
		Route::get('get_electrical_license_rec/{id?}', action: 'ListPermitController@Receiver_electrical_license')->name('recevier_electrical_license');
		Route::get('voltagelevelrec/{vlevel?}', 'ListPermitController@getvoltageReceiver')->name('sendvoltagelevelreciver');
		Route::get('get_electrical_license/{id?}', 'ListPermitController@Issuer_electrical_license')->name('issuer_electrical_license');
		Route::get('voltagelevel/{vlevel?}', 'ListPermitController@getvoltageIssuer')->name('sendvoltagelevel');
		Route::get('getjob/{divi?}/{dept?}', 'PermitController@GetJob')->name('getjob');
		Route::get('vali-licen/{id?}', 'ListPermitController@ShowValidity_LicenseREC')->name('validity_licenserec');
		Route::get('validity-license/{id?}', 'ListPermitController@ShowValidity_LicenseISS')->name('get_validity_license');
		Route::get('report-view/{id?}', 'ListPermitController@ReportView')->name('report_view');
		Route::get('report-list', 'ListPermitController@GetReport')->name('listall_report');
		Route::get('report', 'ListPermitController@ReportList')->name('report_list');
		Route::get('req-email/{id?}', 'ListPermitController@SendNotifyEmailRequester')->name('expnotify');
		Route::post('update-issuer/{id?}', 'ListPermitController@issuerChangeStore')->name('issuerChangeStore');
		Route::get('change-issuer/{id?}', 'ListPermitController@IssuerChange')->name('issuerChange');
		Route::get('return-requester/{id?}', 'ListPermitController@ReturnRequester')->name('returnRequester');
		Route::get('reset/{id?}', 'UserController@ResetPassword')->name('user.resetPassword');
		Route::get('permit-end-date/{id?}', 'ListPermitController@PermitEndDate')->name('getenddate');
		Route::get('delete-swp/{id?}', 'JobController@DeleteSwpFile')->name('deleteSwpFile');
		Route::get('get-valid-emp/{id?}', 'AreaClearenceController@getvalidEmployee')->name('getvalid_emp');
		Route::post('ls_permit/{id}', 'ListPermitController@PermitReturnUpdate')->name('printReturn');
		Route::get('ls_permit_show/{id}', 'ListPermitController@PermitReturn')->name('lp.return');
		Route::get('user_dept/{id?}', 'UserController@UserDepartment')->name('user.department');
		Route::get('job_dept/{id?}', 'JobController@JobDepartment')->name('job.department');
		Route::get('deletehaz/{id?}', 'ListPermitController@DeleteHazard')->name('deletehaz');
		Route::get('delete/{id?}', 'JobController@Delete')->name('deletedivision');
		Route::get('get-job/{jobid?}/{dir?}', 'ListPermitController@getPermitHazard')->name('get_Hazard');
		Route::get('getHaz/{jobid?}/{dir?}', 'PermitController@getHazard')->name('getHaz');
		Route::get('getIssuer/{id?}', 'PermitController@getIssuer')->name('getIssuer');
		Route::post('pwd_update', 'UserController@UpdatePwd')->name('pwd_upd');
		Route::get('show_password', 'UserController@show_pwd')->name('show_password');
		Route::post('cancel-permit', 'ListPermitController@cancelPermit')->name('cancelpermit');
		Route::get('getvalidity/{id?}', 'ListPermitController@getvalidity')->name('getvalidity');



		//Route::get('/autocompleteSearch', //'GatePassCLMSController@autocompleteSearch')->name('autocompleteSearch');

		Route::get('autocomplete', 'GatePassCLMSController@autocomplete')->name('autocomplete');
		Route::get('autocomplete_silo', 'Vendor_siloController@autocomplete_silo')->name('autocomplete_silo');
		Route::get('exit_emp/{id?}/{message_remarks?}', 'GatePassCLMSController@exit_emp')->name('exit_emp');
		Route::get('/monthly-attendance/{user_id?}/{month?}/{year?}', 'GatePassCLMSController@getMonthlyAttendance')->name('getMonthlyAttendance');
		Route::post('/monthly-attendance-update/{day?}/{newStatus?}/{empPno?}/{month?}/{year?}/{ot_hours?}', 'GatePassCLMSController@updateAttendance')->name('updateAttendance');
		Route::get('/monthly-attendance-check/{day?}/{newStatus?}/{empPno?}/{month?}/{year?}', 'GatePassCLMSController@updateAttendance_check')->name('updateAttendance_check');

		Route::get('autoworkorder/{id?}', 'GatePassCLMSController@autoworkorder')->name('autoworkorder');
		Route::get('autoworkorder_silo/{id?}/{uid?}', 'Vendor_siloController@autoworkorder_silo')->name('autoworkorder_silo');
		//Route::get('getvalidity/{id?}','GatePassCLMSController@getvalidity')->name('getvalidity');
		Route::get('getSixDirectionalView/{id?}', 'PermitController@getSixDirectionalView')->name('getSixDirectionalView');
		Route::get('getSwpNumber/{id?}', 'PermitController@getSwpNumber')->name('getSwpNumber');
		Route::get('depart/{id?}', 'PermitController@getDepartment')->name('departmentGet');


		Route::get('department_vendor_mis/{id?}', 'Vendor_misController@getDepartment')->name('departmentGet_vendor_mis');
		Route::get('plant_vendor_mis/{id?}', 'Vendor_misController@getPlant')->name('PlantGet_vendor_mis');
		Route::get('inclusion_vendor_silo/{id?}', 'Vendor_siloController@getinclusion')->name('inclusionGet_vendor_silo');
		Route::get('vendor_vendor_mis/{id?}', 'Vendor_misController@getvendor')->name('vendorGet_vendor_mis');
		Route::get('vendors_by_plant/{plantId}', 'Vendor_misController@getVendorsByPlant')->name('vendorsByPlant');
		// Route::get('work_order/{id?}','GatepassController@getworkorder')->name('work_order');
		Route::get('worker/{id?}', 'GatePassCLMSController@getworker')->name('workerGet');
		Route::get('skill_rate/{id?}', 'GatePassCLMSController@getskillrate')->name('skill_rateGet');

		Route::get('depart/{id?}', 'GatepassController@getDepartment')->name('departmentGet');
		Route::get('approver/{id?}', 'GatepassController@getapprover')->name('approverGet');
		Route::get('getSixDirectional/{id?}', 'JobController@getSixDirectional')->name('getSixDirectionals');
		Route::get('/dashboard', 'LoginController@dashboard')->name('dashboard');
		Route::get('/vendor_details', 'LoginController@vendor_details')->name('vendor_details');
		Route::post('update_vms', 'GatepassController@update_vms')->name('gatepassv.update_vms'); //Successful Register
		Route::post('update_security', 'GatepassController@update_security')->name('gatepassv.update_security'); //Successful Register//
		Route::post('update1', 'GatePassCLMSController@update')->name('gatepassv.update1'); //Successful Register
		Route::post('update_safety', 'SafetyDataEntryController@update1')->name('gatepass_safety.update1'); //Successful Register
		Route::post('update', 'vendorController@update')->name('gatepassven.update'); //Successful Register
		Route::post('exit_update1', 'GatePassCLMSController@exit_update')->name('exit.exit_update1'); //exit employee action
		// Route::get('gatepass_approvals', 'SettingsController@edit')->name('admin.gatepass_approvals.edit');
		Route::match(['get', 'post'], 'update_empid/{id?}/{empid?}/{shift?}', 'GatePassCLMSController@update_empid')->name('update_empid');
		// web.php
		Route::post('update_empid_bulk', 'GatePassCLMSController@updateEmpIdBulk')->name('update_empid_bulk');





		Route::post('vms_report/filter', 'VMSController@filter')->name('vms.filter');
		// web.php
		Route::post('vms/export', 'VMSController@export')->name('vms.export');


		// routes/web.php
		Route::post('vms/filter-json', 'VMSController@filterJson')->name('vms.filterJson');


		Route::post('vendor_mis/filter-json', 'Vendor_misController@filterJson')->name('vendor_mis.filterJson');

		Route::post('vms/filter-json_dashboard', 'VMSController@filterJson_dashboard')->name('vms.filterJson_dashboard');
		Route::post('vendo_mis/filter-json_dashboard', 'Vendor_misController@filterJson_dashboard')->name('vendor_mis.filterJson_dashboard');



		Route::get('/vendor_clms_pending_list', 'vendorController@vendor_clms_pending_list')->name('vendor_clms_pending_list');
		Route::get('/vms/list', 'VMSController@getVmsList')->name('vms.list');
		Route::get('/vendor_mis/list', 'Vendor_misController@getVmsList')->name('vendor_mis.list');
		Route::resource('work-order', 'WorkOrderController');
		Route::resource('job', 'JobController');
		Route::resource('user', 'UserController');
		Route::resource('division', 'DivisionController');
		Route::resource('division_new', 'DivisionnewController');
		Route::resource('request_permit', 'PermitController');

		Route::resource('gatepass_request_permit', 'GatepassController');
		Route::resource('vendor', 'vendorController');


		Route::resource('gatepass_clms_permit', 'GatePassCLMSController');
		Route::resource('safety_data_entry', 'SafetyDataEntryController');
		//Route::post('updatesettings', 'SettingsController')->name('updateSetting');

		//Route::resource('gatepassv','GatepassController');
		//Route::resource('gatepassv','GatepassController')->except(['show']);

		Route::resource('list_permit', 'ListPermitController');
		//Route::resource('approve','GatepassController');
		Route::resource('department', 'DepartmentController');
		Route::resource('skill', 'SkillController');
		Route::resource('vendor_attendance', 'VendorAttendanceController');
		Route::resource('area_cls', 'AreaClearenceController');
		Route::resource('power_shutdown', 'PowerShutdownController');
		Route::resource('vendor_esic_details', 'VendorEsicChallanController');
		Route::resource('vendor_pf_details', 'VendorPfChallanController');
		Route::resource('settings_master', 'SettingsController');
		Route::resource('vendor_ecm', 'VendorECMController');
		Route::resource('vendor_hyr', 'VendorHYRController');
		Route::resource('vendor_holiday', 'VendorholidayController');
		Route::resource('silo_master', 'Silo_masterController');
	});

});

Route::resource('gatepass_register_permit', 'GatePassRegisterController');
Route::resource('silo_daily_inspection', 'SiloDailyInspectionController');
Route::post('/check_otp', 'GatePassRegisterController@check_otp2')->name('check_otp'); //Email Check and otp send

