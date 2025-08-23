<?php 
use App\Division;
?>

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="">Request Visitor Gatepass</a></li>
@endsection
@section('content')
    @extends('admin.app')
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

    <div class="card-body" id="Form_vms">
        @if (session()->has('message'))
            <div class="alert alert-success text-center">
                {{ session('message')}}
            </div>
        @endif
        <!--<form method="POST" action="{{route('RequestVGatepassPost')}}">-->
        <form action="{{route('admin.gatepass_request_permit.store')}}" method="POST" autocomplete="off"
            enctype="multipart/form-data" onsubmit="return validateForm()">
            @csrf

            <div class=" form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Visitor Mobile No<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control rec" name="visitor_mobile" required
                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                        maxlength="10" />
                </div>
            </div>


            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Visitor Name<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control rec" name="visitor_name"
                        onkeydown="return /[a-z, ]/i.test(event.key)" onblur="if (this.value == '') {this.value = '';}"
                        onfocus="if (this.value == '') {this.value = '';}" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Visitor's Company<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control rec" name="visitor_company" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Visitor Email<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="email" class="form-control rec" name="visitor_email" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Visitor’s Emergency Contact No<span
                        style="color:red;font-size: 20px;"></span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control rec" name="visitor_emergency_contact_no"
                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                        maxlength="10" />
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Blood Group<span
                        style="color:red;font-size: 20px;"></span></label>
                <div class="col-sm-10">
                    <select class="form-control rec" name="blood_group" required>
                        <option>--Select--</option>
                        <option value="o_negative">O Negative</option>
                        <option value="o_positive">O Positive</option>
                        <option value="a_neahtive">A Negative</option>
                        <option value="a_positive">A Positive</option>
                        <option value="b_negative">B Negative</option>
                        <option value="b_positive">B Positive</option>
                        <option value="ab_negative">AB Negative</option>
                        <option value="ab_positive">AB Positive</option>

                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Upload Photo(MAX:2MB TYPE:JPG,JPEG,PNG)<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="file" class="form-control rec" name="upload_photo" required accept="image/*">
                </div>
            </div>
            <div class="form-group row">
                <label for="id_proof_type" class="col-sm-2 col-form-label">
                    Proof of Identity <span style="color:red;font-size: 20px;">*</span>
                </label>
                <div class="col-sm-10">
                    <select name="id_proof_type" id="id_proof_type" class="form-control" required onchange="setIdLength()">
                        <option value="">-- Select ID Proof --</option>
                        <option value="Aadhar Card">Aadhar Card</option>
                        <option value="Driving License">Driving License</option>
                        <option value="PAN Card">PAN Card</option>
                        <option value="Passport">Passport</option>
                        <option value="Voter ID">Voter ID</option>
                    </select>
                </div>
            </div>


            <div class="form-group row">
                <label for="id_number" class="col-sm-2 col-form-label">
                    Unique Identification Number <span style="color:red;font-size: 20px;">*</span>
                </label>
                <div class="col-sm-10">
                    <input type="text" name="id_number" id="id_number" class="form-control" required
                        placeholder="Unique Identification Number ">
                    <small id="id_length_info" style="color: #666;"></small>
                    <div id="id_error" style="color:red; font-size:14px;"></div>
                </div>
            </div>

            <script>
                const idRules = {
                    "Aadhar Card": 12,
                    "PAN Card": 10,
                    "Driving License": 16,
                    "Voter ID": 10,
                    "Passport": 8
                };

                function setIdLength() {
                    const type = document.getElementById("id_proof_type").value;
                    const idField = document.getElementById("id_number");
                    const infoText = document.getElementById("id_length_info");
                    const errorText = document.getElementById("id_error");

                    if (idRules[type]) {
                        idField.maxLength = idRules[type];
                        infoText.textContent = `Required Length: ${idRules[type]} characters`;
                    } else {
                        idField.removeAttribute("maxlength");
                        infoText.textContent = "";
                    }

                    errorText.textContent = "";
                }

                function validateForm() {
                    const type = document.getElementById("id_proof_type").value;
                    const idField = document.getElementById("id_number");
                    const idValue = idField.value.trim();
                    const errorText = document.getElementById("id_error");

                    if (idRules[type] && idValue.length !== idRules[type]) {
                        errorText.textContent = `ID number must be exactly ${idRules[type]} characters long.`;
                        idField.focus();
                        return false; // prevent form submission
                    }

                    errorText.textContent = "";
                    return true;
                }
            </script>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Division<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <select class="form-control" id="divisionID" name="division_id" required>
                        <option value="null"> Select Division</option>
                        @if($divisions->count() > 0)
                            @foreach($divisions as $division)
                                <option value="{{$division->id}}">{{$division->name}}</option>
                            @endforeach
                        @endif
                    </select>

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

                    <script>
                        $('#divisionID').on('change', function () {
                            var division_ID = $(this).val();
                            //alert(division_ID);
                            $("#departmentID").html('<option value="null">--Select--</option>');

                            if (divisionID) {
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: 'GET',
                                    url: "{{route('admin.departmentGet')}}/" + division_ID,
                                    contentType: 'application/json',
                                    dataType: "json",
                                    success: function (data) {
                                        console.log(data);
                                        for (var i = 0; i < data.length; i++) {
                                            $("#departmentID").append('<option value="' + data[i].id + '" >' + data[i].department_name + '</option>');
                                        }
                                    }
                                });

                            } else {
                                $('#departmentID').html('<option value="null">Select Division first</option>');
                            }
                        });
                    </script>
                </div>
            </div>

            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Department<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <select class="form-control" id="departmentID" name="department_id" required>
                        <option value="null"> Select Department</option>
                    </select>

                </div>
            </div>


            <script>
                $('#departmentID').on('change', function () {
                    var department_ID = $(this).val();
                    //alert(department_ID);
                    $("#approverID").html('<option value="null">--Select--</option>');

                    if (divisionID) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'GET',
                            url: "{{route('admin.approverGet')}}/" + department_ID,
                            contentType: 'application/json',
                            dataType: "json",
                            success: function (data) {
                                console.log(data);
                                for (var i = 0; i < data.length; i++) {
                                    $("#approverID").append('<option value="' + data[i].id + '" >' + data[i].name + '</option>');
                                }
                            }
                        });

                    } else {
                        $('#approverID').html('<option value="null">Select Department first</option>');
                    }
                });
            </script>

            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Approver<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <select class="form-control" id="approverID" name="approver_id" required>
                        <option value="null"> Select Approver</option>
                    </select>
                </div>
            </div>


            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Select Days<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <select class="form-control" id="select_days" name="days1" required>
                        <option value=""> Select Days</option>
                        <option value="Single"> Single</option>
                        <option value="Multiple">Multiple (maximum 5 days)</option>
                    </select>
                </div>
            </div>

            <script type="text/javascript">
                $("#select_days").on('change', function () {
                    var modeval = $(this).val();
                    //alert(modeval);
                    if (modeval == 'Single') {
                        // $('#s1').show(); 
                        $('#m1').hide();
                        $('#m2').show();
                    }
                    else {
                        $('#m1').show();
                        $('#m2').show();
                        //$('#s1').hide();   
                    }
                }); 
            </script>




            <script>

                $(function () {
                    var dtToday = new Date();
                    var month = dtToday.getMonth() + 1;
                    var day = dtToday.getDate();
                    var year = dtToday.getFullYear();
                    if (month < 10)
                        month = '0' + month.toString();
                    if (day < 10)
                        day = '0' + day.toString();

                    var minDate = year + '-' + month + '-' + day;

                    $('#from_date').attr('min', minDate);
                    $('#to_date').attr('min', minDate);
                    $('#from_date1').attr('min', minDate);
                });
            </script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
            <div class="form-group row" id="s1" style="display:none">
                <label for="form-control-label" class="col-sm-2 col-form-label">Date<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input id="from_date" type="date" class="form-control" name="from_date" autocomplete="off">
                </div>
            </div>
            <div class="form-group row" id="m2">
                <label for="form-control-label" class="col-sm-2 col-form-label">From Date<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input id="from_date1" type="date" class="form-control" name="from_date" autocomplete="off">
                </div>
            </div>

            <div class="form-group row" id="m1" style="display: none;">
                <label for="form-control-label" class="col-sm-2 col-form-label">To Date<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input id="to_date" type="date" class="form-control" name="to_date" autocomplete="off">
                </div>
            </div>

            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">From Time<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input id="from_time" type="time" class="form-control" name="from_time" autocomplete="off"
                        min="08:00:00" max="20:00:00" required>
                    <input id="days" type="hidden" class="form-control" name="days" autocomplete="off" value="5">
                </div>
            </div>

            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">To Time<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input id="to_time" type="time" class="form-control" name="to_time" autocomplete="off" min="09:00"
                        max="18:00" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Any material/equipment/laptop carried
                    along?<span style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="radio" class="btn-check decision1" name="any_material" id="success-outlined"
                        autocomplete="off" value="Yes">
                    <label class="btn btn-outline" for="success-outlined">Yes</label>

                    <input type="radio" class="btn-check decision1" value="No" name="any_material" id="danger-outlined"
                        autocomplete="off">
                    <label class="btn btn-outline-" for="danger-outlined">No</label>
                </div>
            </div>

            <script type="text/javascript">
                $(".decision1").on('change', function () {
                    var modeval = $(this).val();
                    //alert(modeval);
                    if (modeval == 'Yes') {
                        $('#Mname').show();

                    }
                    else {
                        $('#Mname').hide();

                    }
                });

                ; (function ($, window, document, undefined) {
                    $("#from_date1").on("change", function () {
                        var date = new Date($("#from_date1").val()),
                            days = parseInt($("#days").val(), 10);


                        var modeval2 = $("#from_date1").val();

                        if (!isNaN(date.getTime())) {
                            date.setDate(date.getDate() + days);
                            $('#to_date').attr('min', modeval2);

                            $('#to_date').attr('max', date.toInputFormat());
                        } else {
                            alert("Invalid Date");
                        }
                    });


                    //From: http://stackoverflow.com/questions/3066586/get-string-in-yyyymmdd-format-from-js-date-object
                    Date.prototype.toInputFormat = function () {
                        var yyyy = this.getFullYear().toString();
                        var mm = (this.getMonth() + 1).toString(); // getMonth() is zero-based
                        var dd = this.getDate().toString();
                        return yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]); // padding
                    };
                })(jQuery, this, document);


            </script>

            <div class="form-group row" id="Mname" style="display:none;">
                <label for="form-control-label" class="col-sm-2 col-form-label">Material Details<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-9">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Material Name</th>
                                <th>Material Identification No</th>
                                <th>Returnable/Non Returnable</th>
                                <th>Purpose of Material Entry</th>

                            </tr>
                        </thead>
                        <tbody id="append_gatepass">
                            <tr class="gatepass" id="gatepass">
                                <td><input type="text" class="form-control" name="material_name[]" value=""></td>
                                <td><input type="text" class="form-control" name="material_idenrification_no[]" value="">
                                </td>
                                <td>

                                    <select class="form-control" id="returnable" name="returnable[]" autocomplete="off">
                                        <option value="null">--select--</option>
                                        <option value="Returnable">Returnable</option>
                                        <option value="Non Returnable">Non Returnable</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="purpose_of_material_entry[]" value="">
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

            <script>

                $("#btn-add").on("click", function (e) {
                    var count = $(".remove_tr").length + 1;
                    // console.log(count);
                    $('#append_gatepass').append(`<tr class="gatepass">
                                                                                                            <td><input type="text" class="form-control" name="material_name[]"></td>
                                                                                                            <td><input type="text" class="form-control" name="material_idenrification_no[]"></td>
                                                                                                            <td><select class="form-control" id="returnable"  name="returnable[]" autocomplete="off" >
                                                                                                                 <option value="null">--select--</option>
                                                                                                                          <option value="Returnable">Returnable</option>
                                                                                                                          <option value="Non Returnable">Non Returnable</option>
                                                                                                               </select></td>
                                                                                                            <td><input type="text" class="form-control" name="purpose_of_material_entry[]"></td> 


                                                                                                        </tr>`);
                });

                $("#btn-remove").on("click", function (e) {
                    if ($('.gatepass').length > 1) {
                        $(".gatepass:last").remove();
                    }
                });
            </script>


            <div class="form-group row" style="display:none">
                <label for="form-control-label" class="col-sm-2 col-form-label">Material Name<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input id="material_name" type="text" class="form-control" name="" placeholder="Material Name"
                        autocomplete="off">
                </div>
            </div>

            <div class="form-group row" style="display:none">
                <label for="form-control-label" class="col-sm-2 col-form-label">Material Identification No<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input id="material_idenrification_no" type="text" class="form-control" name=""
                        placeholder="Material Identification No" autocomplete="off">
                </div>
            </div>
            <div class="form-group row" style="display:none">
                <label for="form-control-label" class="col-sm-2 col-form-label">Returnable/Non Returnable<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <select class="form-control" id="returnable" name="" autocomplete="off">
                        <option value="null">--select--</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>
            <div class="form-group row" style="display:none">
                <label for="form-control-label" class="col-sm-2 col-form-label">Purpose of Material Entry<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input id="purpose_of_material_entry" type="text" class="form-control" name=""
                        placeholder="Purpose of Material Entry" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Is Visitor Coming by any vehicle?<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="radio" class="btn-check decision2" name="any_vehicle" id="success-outlined"
                        autocomplete="off" value="Yes">
                    <label class="btn btn-outline" for="success-outlined">Yes</label>

                    <input type="radio" class="btn-check decision2" value="No" name="any_vehicle" id="danger-outlined"
                        autocomplete="off">
                    <label class="btn btn-outline-" for="danger-outlined">No</label>
                </div>
            </div>

            <script type="text/javascript">

                $(".decision2").on('change', function () {
                    var modeval = $(this).val();
                    //alert(modeval);
                    if (modeval == 'Yes') {
                        $('#Dmode').show();

                    }
                    else {
                        $('#Dmode').hide();
                        $('#V_no').hide();
                        $('#DL_No').hide();
                        $('#driver_NAME').hide();
                    }
                }); 
            </script>


            <div class="form-group row" id="Dmode" style="display:none">
                <label for="form-control-label" class="col-sm-2 col-form-label">Driving Mode<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <select class="form-control" id="driving_mode" name="driving_mode" autocomplete="off">
                        <option value="null">--select--</option>
                        <option value="self">Self</option>
                        <option value="Driver">Driver</option>
                    </select>
                </div>
            </div>

            <script type="text/javascript">

                $("#driving_mode").on('change', function () {
                    var modeval = $(this).val();
                    //alert(modeval);
                    if (modeval == 'self') {
                        $('#Dmode').show();
                        $('#V_no').show();
                        $('#DL_No').show();
                        $('#driver_NAME').hide();

                    }
                    else {
                        $('#driver_NAME').show();
                        $('#Dmode').show();
                        $('#V_no').show();
                        $('#DL_No').show();

                    }
                }); 
            </script>


            <div class="form-group row" id="driver_NAME" style="display:none">
                <label for="form-control-label" class="col-sm-2 col-form-label">Driver Name<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input id="driver_name" type="text" class="form-control" name="driver_name"
                        placeholder=" Enter Driver Name" autocomplete="off">
                </div>
            </div>

            <div class="form-group row" id="V_no" style="display:none">
                <label for="form-control-label" class="col-sm-2 col-form-label">Vehicle No<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input id="vehicle_no" type="text" class="form-control" name="vehicle_no"
                        placeholder=" Enter Vehicle No" autocomplete="off">
                </div>
            </div>
            <div class="form-group row" id="DL_No" style="display:none">
                <label for="form-control-label" class="col-sm-2 col-form-label">DL No<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input id="dl_no" type="text" class="form-control" name="dl_no" placeholder="Driving licence No"
                        autocomplete="off">
                </div>
            </div>
            <div>
                <th class="col-md-4 col-form-label text-md-right">
                    <label class="col-md-2"></label><input class="form-check-input" type="checkbox" id="" name="ok"
                        value="yes" required>
                    I confirm and declare that the information given above is true and correct to the best of my knowledge.
                    In case the visitors are found working in the plant, I will be responsible for suitable discipilinary
                    action as per company's regulation.


                </th>
            </div>

            <div class="form-group row mb-1">
                <br><br>

                <div class="col-md-8 offset-md-5 ">
                    <button type="submit" class="btn btn-primary" id="Button_submit" onclick="topFunction()">
                        Submit
                    </button>
                </div>
            </div>

        </form>


    </div>
    <br>
    <!--<script type="text/javascript">
                                                                                        $("#check_id").click(function () {
                                                                                            $("#Form_vms").hide();
                                                                                            $("#button_show").hide();
                                                                                        });
                                                                                        $("#button_show").click(function () {
                                                                                            $("#video").show();
                                                                                           $("#video2").show();
                                                                                           $("#video3").show();
                                                                                           $("#question").show();
                                                                                           $("#question2").show();
                                                                                           $("#question3").show();
                                                                                           $("#question4").show();
                                                                                           $("#question5").show();
                                                                                           $("#question6").show();
                                                                                           $("#question7").show();
                                                                                           $("#question8").show();
                                                                                           $("#question9").show();

                                                                                        });
                                                                                        $("#Button_submit").click(function () {
                                                                                            $("#Form_vms").show();
                                                                                            $("#button_show").css("display", "none");
                                                                                        });
                                                                                        $("#Button_submit").click(function () {
                                                                                            $("#video").hide();
                                                                                           $("#video2").hide();

                                                                                        });
                                                                                        </script>  -->

    <script>
        // Get the button
        let mybutton = document.getElementById("myBtn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function () { scrollFunction() };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }



    </script>

@endsection