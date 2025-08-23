<?php
use App\Division;
use App\Department;
use App\UserLogin;
?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.user.index')}}">List Of Users</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add User</li>
@endsection
@if(Session::get('user_sub_typeSession') == 2)
    return redirect('admin/dashboard');
@else
    @section('content')

        <!-- ALTER TABLE userlogins 
                                                                    ADD 
                                                                        power_cutting VARCHAR (50) NULL,
                                                                        power_getting VARCHAR (50) NULL,
                                                                        confined_space VARCHAR (50) NULL; -->

        <form action="{{route('admin.user.store')}}" method="post" autocomplete="off">
            @csrf
            <div class="form-group-row">
                <div class="col-sm-12">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group-row">
                <div class="col-sm-12" style="text-align:center;">
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message')}}
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Name<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control rec" name="name">
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Employee P.No./Vendor User Name<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control rec" name="vendor_code" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Email<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="email" class="form-control rec" name="email">
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">WPS<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <select class="form-control rec" name="wps_user" id="wps_user">
                        <option value="">--Select--</option>
                        <option value="Yes">Yes</option>
                        <option value="No"> No</option>
                    </select>
                </div>
            </div>
            <div class="form-group row" id="user_type" style="display:none">
                <label for="form-control-label" class="col-sm-2 col-form-label"> User Type<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <select class="form-control " id="u_type" name="user_type">
                        <option value="">Select User Type</option>
                        <option value="1">Employee</option>
                        <option value="2">Vendor</option>
                    </select>
                </div>
            </div>


            <link href="{{URL::to('public/css/sweetalert.css')}}" rel="stylesheet">
            <script type="text/javascript" src="{{URL::to('public/js/app.js')}}"> </script>
            <script type="text/javascript" src="{{URL::to('public/js/sweetalert.js')}}"> </script>
            <script type="text/javascript"
                src="{{URL::to('node_modules/jquery-datetimepicker/jquery.datetimepicker.js')}}"> </script>
            <script type="text/javascript" src="{{URL::to('public/js/app.js')}}"> </script>
            <script type="text/javascript" src="{{URL::to('public/js/dataTables.buttons.min.js')}}"> </script>
            <script type="text/javascript" src="{{URL::to('public/js/jszip.min.js')}}"> </script>
            <script type="text/javascript" src="{{URL::to('public/js/buttons.html5.min.js')}}"> </script>
            <script type="text/javascript" src="{{URL::to('public/js/all.js')}}"> </script>
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
            <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
            <div id="Employee">
                <div class="form-group row" id="sub_sub_type" style="display:none;">
                    <label for="form-control-label" class="col-sm-2 col-form-label">User Sub-Type</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="user_sub_type" id="user_sub_type">
                            <option value="1">Admin</option>
                            <option value="2"> User</option>
                            <option value="3">Super Admin</option>
                            <!--<option value="4">Security </option>-->
                        </select>
                    </div>
                </div>
                <script type="text/javascript">
                    $("#wps_user").on('change', function () {
                        var modeval = $(this).val();
                        //alert(modeval);
                        if (modeval == 'Yes') {
                            $('#user_type').show();
                            $('#sub_sub_type').show();
                            $('#ATPC').show();
                            $('#ATPS').show();
                            $('#ATCS').show();
                            $('#ELEC').show();
                        }
                        else {
                            $('#user_type').hide();
                            $('#sub_sub_type').hide();
                            $('#ATPC').hide();
                            $('#ATPS').hide();
                            $('#ATCS').hide();
                            $('#ELEC').hide();
                        }
                    }); 
                </script>



                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">CLMS<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control rec" name="clms" id="clms">
                            <option value="">--Select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No"> No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="CLMS_role" style="display:none">
                    <label for="form-control-label" class="col-sm-2 col-form-label">CLMS Role<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control " name="clms_role">
                            <option value="">--Select--</option>
                            <option value="Shift_incharge">Shift Incharge</option>
                            <option value="hr_dept">HR Dept</option>
                            <option value="Safety_dept"> Safety Dept</option>
                            <option value="plant_head"> Plant Head</option>
                            <option value="security"> Security</option>
                            <option value="Executing_agency"> Executing Agency</option>
                            <option value="Account_dept"> Account Dept</option>
                        </select>
                    </div>
                </div>

                <script type="text/javascript">
                    $("#clms").on('change', function () {
                        var modeval = $(this).val();
                        var modeval2 = $('#u_type').val();
                        if (modeval == 'Yes' && modeval2 == '1') {
                            $('#CLMS_role').show();
                            $('#clms_admin').show();
                            $('#clms_limit').show();
                        }
                        else {
                            $('#CLMS_role').hide();
                            $('#clms_admin').hide();
                            $('#clms_limi').hide();
                        }
                    }); 
                </script>
                <script type="text/javascript">
                    $("#u_type").on('change', function () {
                        var modeval = $('#clms').val();
                        var modeval2 = $(this).val();
                        if (modeval == 'Yes' && modeval2 == '1') {
                            $('#CLMS_role').show();
                            $('#clms_admin').show();
                            $('#clms_limit').show();
                        }
                        else {
                            $('#CLMS_role').hide();
                            $('#clms_admin').hide();
                            $('#clms_limi').hide();
                        }
                    }); 
                </script>



                <div class="form-group row" id="clms_admin" style="display:none;">
                    <label for="form-control-label" class="col-sm-2 col-form-label">CLMS Admin<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control " name="clms_admin">
                            <option value="">--Select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>


                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">VMS<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control rec" name="vms" id="VMS">
                            <option value="">--Select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No"> No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="VMS_role" style="display:none">
                    <label for="form-control-label" class="col-sm-2 col-form-label">VMS Role<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control " name="vms_role">
                            <option value="">--Select--</option>
                            <option value="Approver">Approver</option>
                            <option value="Security">Security</option>
                            <option value="Requester">Requester</option>
                        </select>
                    </div>
                </div>
                <script type="text/javascript">
                    $("#VMS").on('change', function () {
                        var modeval = $(this).val();
                        if (modeval == 'Yes') {
                            $('#VMS_role').show();
                            $('#vms_admin').show();
                        }
                        else {
                            $('#VMS_role').hide();
                            $('#vms_admin').hide();
                        }
                    }); 
                </script>
                <div class="form-group row" id="vms_admin" style="display:none;">
                    <label for="form-control-label" class="col-sm-2 col-form-label">VMS Admin<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control " name="vms_admin">
                            <option value="">--Select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>


                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Safety Statistics<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control rec" name="safety" id="safety_id">
                            <option value="">--Select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="safety_role_id" style="display:none;">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Safety Statistics Role<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control " name="safety_role">
                            <option value="">--Select--</option>
                            <option value="data_entry">Data Entry</option>
                            <option value="data_view">Data View</option>
                        </select>
                    </div>
                </div>

                <script type="text/javascript">
                    $("#safety_id").on('change', function () {
                        var modeval = $(this).val();
                        if (modeval == 'Yes') {
                            $('#safety_role_id').show();
                            $('#safety_admin').show();
                        }
                        else {
                            $('#safety_role_id').hide();
                            $('#safety_admin').hide();
                        }
                    }); 
                </script>
                <div class="form-group row" id="safety_admin" style="display:none;">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Safety Statistics Admin<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control " name="safety_admin">
                            <option value="">--Select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>


                        </select>
                    </div>
                </div>


                <!-- VENDOR -->
                <div style="display:none;" id="sup">
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Code<span
                                style="color:red;font-size: 20px;">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="vendor_name_code">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Division<span
                                style="color:red;font-size: 20px;">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-control" id="" name="vendor_division_id">
                                <option value="">Select The Division</option>
                                @if($divisions->count() > 0)
                                    @foreach($divisions as $division)
                                        <option value="{{$division->id}}">{{$division->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Vendor ABB<span
                                style="color:red;font-size: 20px;">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="vendor_abb">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Supervisor Name<span
                                style="color:red;font-size: 20px;">*</span></label>
                        <div class="col-sm-9" id="append_sup">
                            <input type="text" class="form-control remove_tr" name="supervisor[]" id="supervisor_id">&nbsp;
                        </div>
                        <div class="col-sm-1">
                            <button type="button" id="add_sup" class="btn btn-primary btn-sm">+</button>
                            <button type="button" id="sub_sup" class="btn btn-danger btn-sm">-</button>
                        </div>
                    </div>
                    <!-- after go live-->
                    <div class="form-group row" style="display:none">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Gate Pass Details<span
                                style="color:red;font-size: 20px;">*</span></label>
                        <div class="col-sm-9">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Gate Pass No.</th>
                                        <th>Designation</th>
                                        <th>Age</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody id="append_gatepass">
                                    <tr class="gatepass" id="gatepass">
                                        <td><input type="text" class="form-control" name="employee[]" value=""></td>
                                        <td><input type="text" class="form-control" name="gatepass[]" value=""></td>
                                        <td><input type="text" class="form-control" name="designation[]" value=""></td>
                                        <td><input type="text" class="form-control" name="age[]" value=""></td>
                                        <td><input type="date" class="form-control start_date" name="expirydate[]" value="">
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-1" style="">
                            <button type="button" id="btn-add" class="btn btn-primary btn-sm">+</button>&nbsp;
                            <button type="button" id="btn-remove" class="btn btn-danger btn-sm">-</button>
                        </div>
                    </div>
                    <!--  after go live-->
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Electrical Supervisory? <span
                                style="color:red;font-size: 20px;">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-control" name="ElectricalVendor"
                                onChange="ElectricalSupervisoryVendor(this.value)">
                                <option value="">Select </option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                    <!-- Supervisor details -->
                    <div style="display: none;" id="Electrical-Vendor">
                        <div class="form-group row">
                            <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Power Shutdowns:<span
                                    style="color:red;font-size: 20px;">*</span></label>
                            <div class="col-sm-11">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Supervisor Name</th>
                                            <th>Electrical Supervisory License number</th>
                                            <th>License Number Validity date</th>
                                            <th>Competent for Voltage level</th>
                                            <th>Issue Power Clearance</th>
                                            <th>Receive Power Clearance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataview">
                                        <tr class="appendrow">
                                            <td><input type="text" class="form-control" name="supervisor_ven[]"></td>
                                            <td><input type="text" class="form-control" name="electrical_license_ven[]"></td>
                                            <td><input type="date" class="form-control" name="license_validity_ven[]"></td>
                                            <td>
                                                <table style="width: 180px;">
                                                    <tr>
                                                        <td><span>132KV</span></td>
                                                        <td><input type="radio" name="v132kv_ven[0]" checked value="yes">&nbsp;
                                                            Yes
                                                            <input type="radio" name="v132kv_ven[0]" value="no">&nbsp; No
                                                        </td>
                                                    <tr>
                                                    <tr>
                                                        <td><span>33KV</span></td>
                                                        <td><input type="radio" name="v33kv_ven[0]" checked value="yes">&nbsp;
                                                            Yes
                                                            <input type="radio" name="v33kv_ven[0]" value="no">&nbsp; No
                                                        </td>
                                                    <tr>
                                                        <td><span>11KV</span></td>
                                                        <td><input type="radio" name="v11kv_ven[0]" checked value="yes">&nbsp;
                                                            Yes
                                                            <input type="radio" name="v11kv_ven[0]" value="no">&nbsp; No
                                                        </td>
                                                    <tr>
                                                        <td><span>LT</span></td>
                                                        <td><input type="radio" name="vlt_ven[0]" checked value="yes">&nbsp; Yes
                                                            <input type="radio" name="vlt_ven[0]" value="no">&nbsp; No
                                                        </td>
                                                    <tr>
                                                </table>
                                            </td>
                                            <td>
                                                <label class="form-check-label">
                                                    <input type="radio" class="" name="issue_power_ven[0]" checked
                                                        value="yes">&nbsp; Yes
                                                </label>
                                                <label class="form-check-label">
                                                    <input type="radio" class="" name="issue_power_ven[0]" value="no">&nbsp; No
                                                </label>
                                            </td>
                                            <td>
                                                <label class="form-check-label">
                                                    <input type="radio" class="" name="rec_power_ven[0]" checked
                                                        value="yes">&nbsp; Yes
                                                </label>
                                                <label class="form-check-label">
                                                    <input type="radio" class="" name="rec_power_ven[0]" value="no">&nbsp; No
                                                </label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-1" style="">
                                <button type="button" id="btn-add-vendor" class="btn btn-primary btn-sm">+</button>&nbsp;
                                <button type="button" id="btn-remove-vendor" class="btn btn-danger btn-sm">-</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">


                    $('#u_type').on('change', function () {
                        var modeval = $(this).val();
                        if (modeval == '1') {
                            //if($('#u_type').val() == 1) {
                            //  $('#Employee').show(); 
                            $('#sup').hide();
                            $('#DEPT').show();
                            $('#DIVISION').show();

                        } else {
                            // $('#Employee').hide(); 
                            $('#sup').show();
                            $('#DEPT').hide();
                            $('#DIVISION').hide();
                            $('#ATPC').hide();
                            $('#ATPS').hide();
                            $('#ATCS').hide();

                            $('#ELEC').hide();

                        }

                    });
                </script>
                <!-- EMP -->


                <link href="{{URL::to('public/css/sweetalert.css')}}" rel="stylesheet">
                <script type="text/javascript" src="{{URL::to('public/js/app.js')}}"> </script>
                <script type="text/javascript" src="{{URL::to('public/js/sweetalert.js')}}"> </script>
                <script type="text/javascript"
                    src="{{URL::to('node_modules/jquery-datetimepicker/jquery.datetimepicker.js')}}"> </script>
                <script type="text/javascript" src="{{URL::to('public/js/app.js')}}"> </script>
                <script type="text/javascript" src="{{URL::to('public/js/dataTables.buttons.min.js')}}"> </script>
                <script type="text/javascript" src="{{URL::to('public/js/jszip.min.js')}}"> </script>
                <script type="text/javascript" src="{{URL::to('public/js/buttons.html5.min.js')}}"> </script>
                <script type="text/javascript" src="{{URL::to('public/js/all.js')}}"> </script>
                <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
                <link rel="stylesheet"
                    href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
                <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


                <script type="text/javascript">

                    $("#user_sub_type").on('change', function () {
                        var modeval = $(this).val();
                        //alert(modeval);
                        if (modeval == '4') {
                            $('#ATPC').hide();
                            $('#ATPS').hide();
                            $('#ATCS').hide();
                            $('#ELEC').hide();
                        }
                        else {
                            $('#ATPC').show();
                            $('#ATPS').show();
                            $('#ATCS').show();
                            $('#ELEC').show();
                        }
                    }); 
                </script>
                <div class="form-group row" id="ATPC" style="display: none;">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Authority to Issue Power Cutting<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control" name="power_cutting">
                            <option value="">--Select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="ATPS" style="display: none;">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Authority to Issue Power Getting<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control" name="power_getting">
                            <option value="">--Select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="ATCS" style="display: none;">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Authority to Issue Confined Space<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control" name="confined_space">
                            <option value="">--Select--</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

                <!--------------------End Code for Multiple Division,Department,Section Buttom ------------------------->
                @if(Session::get('user_sub_typeSession') == 3)
                <div class="form-group row" id="DIVISION">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Division<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control" id="division_id" name="division_id">
                            <option value="null">Select The Division</option>
                            @if($divisions->count() > 0)
                            @foreach($divisions as $division)
                            <option value="{{$division->id}}">{{$division->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="DEPT">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Departments<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control" id="department_id" name="department_id">
                            <option value="null">Select Department</option>
                        </select>
                    </div>
                </div>
                @else
                <?php 
                                                                            $division = Division::where('id', Session::get('user_DivID_Session'))->get();
            $department = Department::where('division_id', $division[0]->id)->get();
                                                                        ?>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Division<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control" name="division_id">
                            @if($division->count() > 0)
                            @foreach($division as $div)
                            <option value="{{$div->id}}">{{$div->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Departments<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control" name="department_id">
                            @foreach($department as $depar)
                            <option value="{{@$depar->id}}">{{@$depar->department_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                <div class="form-group row" id="ELEC">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Electrical Supervisory?<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control" name="ElectricalSup" onChange="ElectricalSupervisoryEmployee(this.value)">
                            <option value="">Select</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                </div>

                <div style="display: none" id="Electrical_Yes">
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Electrical Supervisory License
                            number<span style="color:red;font-size: 20px;">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="electrical_license_emp">&nbsp;
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">License Number Validity date<span
                                style="color:red;font-size: 20px;">*</span></label>
                        <div class="col-sm-10" id="">
                            <input type="date" class="form-control" name="license_validity_emp" id="edate">&nbsp;
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Competent for Voltage Level</label>
                        <div class="col-sm-10">
                            <table style="width: 150px;">
                                <tr>
                                    <td><span>132KV</span></td>
                                    <td><input type="radio" name="v133kv_emp" checked value="yes">&nbsp; Yes
                                        <input type="radio" name="v133kv_emp" value="no">&nbsp; No
                                    </td>
                                <tr>
                                <tr>
                                    <td><span>33KV</span></td>
                                    <td><input type="radio" name="v33kv_emp" checked value="yes">&nbsp; Yes
                                        <input type="radio" name="v33kv_emp" value="no">&nbsp; No
                                    </td>
                                <tr>
                                    <td><span>11KV</span></td>
                                    <td><input type="radio" name="v11kv_emp" checked value="yes">&nbsp; Yes
                                        <input type="radio" name="v11kv_emp" value="no">&nbsp; No
                                    </td>
                                <tr>
                                    <td><span>LT</span></td>
                                    <td><input type="radio" name="vlt_emp" checked value="yes">&nbsp; Yes
                                        <input type="radio" name="vlt_emp" value="no">&nbsp; No
                                    </td>
                                <tr>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Issue Power Clearance</label>
                        <div class="form-check col-sm-10">
                            <input type="radio" class="" name="issue_power" checked value="yes">&nbsp; Yes
                            <input type="radio" class="" name="issue_power" value="no">&nbsp; No
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Receive Power Clearance</label>
                        <div class="form-check col-sm-10">
                            <input type="radio" class="" name="rec_power" checked value="yes">&nbsp; Yes
                            <input type="radio" class="" name="rec_power" value="no">&nbsp; No
                        </div>
                    </div>
                </div>

            </div>
            <!-- END EMP -->
            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    <input type="submit" name="submit" class="btn btn-primary" value="Add User"
                        onclick="return form_validate();">
                </div>
            </div>
        </form>
    @endsection
@endif
@section('scripts')
    <script>
        /* $(function() {
             $('#Employee').show(); 
             $('#u_type').change(function(){
                 if($('#u_type').val() == 1) {
                     $('#Employee').show(); 
                     $('#sup').hide(); 

                 } else {
                     $('#Employee').hide(); 
                     $('#sup').show(); 

                 } 
             });
         });*/





        // get the Department data
        $('#division_id').on('change', function () {
            var division_ID = $(this).val();
            $("#department_id").html('<option value="">--Select--</option>');
            if (division_ID) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "{{route('admin.user.department')}}/" + division_ID,
                    contentType: 'application/json',
                    dataType: "json",
                    success: function (data) {
                        // console.log(data);
                        // $("#sectionID").html('<option value="0">--Select--</option>');
                        for (var i = 0; i < data.length; i++) {
                            $("#department_id").append('<option value="' + data[i].id + '" >' + data[i].department_name + '</option>');
                        }
                    }
                });
            } else {
                $('#department_id').html('<option value="null">Select Department</option>');
            }
        });

        //add supervisor
        $("#add_sup").on("click", function (e) {
            var count = $(".remove_tr").length + 1;
            $('#append_sup').append(`<input type="text" class="form-control remove_tr" name="supervisor[]" id="supervisor_id">&nbsp;`);
        });
        //Remove Confined To Click 
        $("#sub_sup").on("click", function (e) {
            if ($('.remove_tr').length > 1) {
                $(".remove_tr:last").remove();
            }
        });


        //gate pass Details to add
        $("#btn-add").on("click", function (e) {
            var count = $(".remove_tr").length + 1;
            // console.log(count);
            $('#append_gatepass').append(`<tr class="gatepass">
                                                    <td><input type="text" class="form-control" name="employee[]"></td>
                                                    <td><input type="text" class="form-control" name="gatepass[]"></td>
                                                    <td><input type="text" class="form-control" name="designation[]"></td>
                                                    <td><input type="text" class="form-control" name="age[]"></td> 
                                                    <td><input type="date" class="form-control start_date" name="expirydate[]"  value=""></td>

                                                </tr>`);
        });

        //Remove Top Click
        $("#btn-remove").on("click", function (e) {
            if ($('.gatepass').length > 1) {
                $(".gatepass:last").remove();
            }
        });


        //Append code
        $("#btn-add-vendor").on("click", function (e) {
            var count = $(".appendrow").length + 1;
            count = count - 1;
            // i++;
            // console.log(count);
            $('#dataview').append(`<tr class="appendrow">
                                            <td><input type="text" class="form-control" name="supervisor_ven[]"></td>
                                            <td><input type="text" class="form-control" name="electrical_license_ven[]"></td>
                                            <td><input type="date" class="form-control" name="license_validity_ven[]"></td>
                                            <td><table style="width: 180px;">
                                                    <tr><td><span>132KV</span></td>
                                                        <td><input type="radio" name="v132kv_ven[`+ count + `]" checked value="yes">&nbsp; Yes
                                                        <input type="radio" name="v132kv_ven[`+ count + `]" value="no">&nbsp; No
                                                        </td>
                                                    <tr>
                                                    <tr><td><span>33KV</span></td>
                                                        <td><input type="radio" name="v33kv_ven[`+ count + `]" checked value="yes">&nbsp; Yes
                                                        <input type="radio" name="v33kv_ven[`+ count + `]" value="no">&nbsp; No
                                                        </td>
                                                    <tr><td><span>11KV</span></td>
                                                        <td><input type="radio" name="v11kv_ven[`+ count + `]" checked value="yes">&nbsp; Yes
                                                        <input type="radio" name="v11kv_ven[`+ count + `]" value="no">&nbsp; No
                                                        </td>
                                                    <tr><td><span>LT</span></td>
                                                        <td><input type="radio" name="vlt_ven[`+ count + `]" checked value="yes">&nbsp; Yes
                                                        <input type="radio" name="vlt_ven[`+ count + `]" value="no">&nbsp; No
                                                        </td>
                                                    <tr>
                                                </table>
                                            </td>
                                            <td>
                                                <label class="form-check-label">
                                                    <input type="radio" class="" name="issue_power_ven[`+ count + `]"  checked value="yes">&nbsp; Yes
                                                </label>
                                                <label class="form-check-label">
                                                    <input type="radio" class=""  name="issue_power_ven[`+ count + `]" value="no">&nbsp; No  
                                                </label>
                                            </td>
                                            <td>
                                                <label class="form-check-label">
                                                    <input type="radio" class="" name="rec_power_ven[`+ count + `]" checked value="yes">&nbsp; Yes
                                                </label>
                                                <label class="form-check-label">
                                                    <input type="radio" class=""  name="rec_power_ven[`+ count + `]" value="no">&nbsp; No  
                                                </label>
                                            </td>
                                        </tr>`);
        });

        //Remove 
        $("#btn-remove-vendor").on("click", function (e) {
            if ($('.appendrow').length > 1) {
                $(".appendrow:last").remove();
            }
        });
        function ElectricalSupervisoryEmployee(items) {
            if (items != "") {
                if (items == 'yes') {
                    $("#Electrical_Yes").show();
                }
                else if (items == 'no') {
                    $("#Electrical_Yes").hide();
                }
            }
        }
        function ElectricalSupervisoryVendor(items) {
            if (items != "") {
                if (items == 'yes') {
                    $("#Electrical-Vendor").show();
                }
                else if (items == 'no') {
                    $("#Electrical-Vendor").hide();
                }
            }
        }
    </script>
@endsection