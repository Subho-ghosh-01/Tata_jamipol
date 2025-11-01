<?php 
use App\Division;
use App\UserLogin;
use App\Department;
//use Session;



@$approver = UserLogin::where('id', Session::get('user_idSession'))->first();
@$workorder_code = DB::table('work_order')->where('vendor_code', @$approver->vendor_code)->get();

@$labour_capacity = $approver->lobour_capacity ?? 0;

$gp_count = DB::table('Clms_gatepass')->where('gp_status', 'New')->where('created_by', Session::get('user_idSession'))->count();


?>

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="">Contractor Gatepass</a></li>
@endsection
@section('content')
    @extends('admin.app')


    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success text-center">
                {{ session('message')}}
            </div>
        @endif




        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if($labour_capacity <= $gp_count)
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <style>
                .blurred {
                    filter: blur(3px);
                    pointer-events: none;
                    opacity: 0.6;
                    transition: all 0.5s ease;
                    /* Smooth blur */
                }
            </style>

            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'warning', // 🔥 Using 'warning' with golden yellow color
                        title: '⚠️ Labour Capacity Reached!',
                        text: 'You cannot add more gatepasses. Your limit is full.',
                        background: '#fff8e1', // Light golden background
                        color: '#6d4c41',       // Elegant brown text
                        confirmButtonColor: '#ff6f00', // Vibrant orange button
                        confirmButtonText: 'Okay, Got it!',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        backdrop: `
                                                                                                                                                    rgba(0,0,0,0.7)
                                                                                                                                                    left top
                                                                                                                                                    no-repeat
                                                                                                                                                `,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    }).then(function () {
                        // Disable all form elements
                        document.querySelectorAll('input, select, textarea, button').forEach(function (element) {
                            element.setAttribute('disabled', 'disabled');
                        });

                        // Blur the form (only if your form has id="myForm")
                        var form = document.getElementById('myForm');
                        if (form) {
                            form.classList.add('blurred');
                        }
                    });
                });
            </script>
        @endif



        <!--<form method="POST" action="{{route('RequestVGatepassPost')}}">-->
        <form action="{{route('admin.gatepass_clms_permit.store')}}" method="POST" autocomplete="off"
            enctype="multipart/form-data" id="quickForm">
            @csrf


            <fieldset class="border p-4">
                <legend class="w-auto">Vendor & Work Order Details</legend>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Name<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" value="{{ @$approver->name}}" name="visitor_mobile"
                            readonly>

                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Code<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" value="{{ @$approver->vendor_code}}" name="visitor_name"
                            readonly required>
                    </div>
                </div>

                <div class="form-group row" style="display:none;">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Work Order Code<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control rec " id="" name="">
                            <option value="">Select Workorder</option>
                            @if($workorder_code->count() > 0)
                                @foreach(@$workorder_code as $work_order)
                                    <option value="{{@$work_order->order_code}}">{{@$work_order->order_code}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="Workorder" class="col-sm-2 col-form-label">Order No<span style="color:red;font-size: 20px;">
                            *</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="Workorder" name="work_order" required>
                    </div>

                    <div class="col-sm-2" style="display:none;"><a class="btn btn-info btn-xm" id="">Check Validity</a>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Work Order Validity<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control validity" readonly name="work_order_validity"
                            id="order_validity" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Executing Agency <span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control rec " id="" name="excuting_agency" required>
                            <option value="">Select Executing Agency</option>
                            @if($users->count() > 0)
                                @foreach(@$users as $user)
                                    <option value="{{@$user->id}}">{{@$user->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Labour License No</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="email" value="{{$approver->lobour_license_no}}"
                            readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Labour License Validity</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" name="email" value="{{$approver->labour_license_validity}}"
                            readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Contract Owner's Person Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="email" value="{{$approver->md_name}}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Contract Owner's Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="contract_email" value="{{$approver->email}}"
                            readonly>
                    </div>
                </div>
            </fieldset>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.22.0/autocomplete.jquery.min.js"
                integrity="sha512-sYSJW8c3t/hT4R6toey7NwQmlrPMTqvDk10hsoD8oaeXUZRexAzrmpp5kVlTfy6Ru7b1+Tte2qBrRE7FOX1vgA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src='https://code.jquery.com/jquery-latest.min.js'></script>
            <link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet">

            <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

            <!-- jQuery (required before jQuery UI) -->
            <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

            <!-- jQuery UI JS -->
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

            <!-- jQuery UI CSS (for styling the autocomplete dropdown) -->
            <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

            <script type="text/javascript">
                var path = "{{ route('admin.autocomplete') }}";

                $("#Workorder").autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: path,
                            type: 'GET',
                            dataType: "json",
                            data: {
                                search: request.term
                            },
                            success: function (data) {
                                response(data.map(item => {
                                    return item.order_code
                                }));
                                // alert(data);
                                //  console.log(ui.item);
                                //$("#Workorder").val(data);


                            }
                        });
                    },

                });
            </script>
            <script>


            </script>


            <script type="text/javascript">
                $("#Workorder").blur(function (e) {
                    if ($(this).val() != "") {
                        var cid = $(this).val();
                        // alert(cid);
                        $.ajax({
                            type: "GET",
                            url: "{{route('admin.autoworkorder')}}/" + cid,
                            contentType: 'application/json',
                            data: { cid: $(this).val() },
                            dataType: "json",
                            success: function (data) {
                                //  alert(data);
                                $("#order_validity").val(data);
                            }
                        })
                    }
                })

            </script>
            <!--<script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            $("#getvalidity").on("click", function (e)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    var o_ID = $("#Workorder").val();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    // alert(o_ID);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $.ajax({
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        type:'GET',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        url:"{{route('admin.getvalidity')}}/" + o_ID,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        contentType:'application/json',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        dataType:"json",
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        success:function(data){
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             //console.log(data);                   
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            $("#order_validity").val(data.date);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    });
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                });

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script>-->

            <fieldset class="border p-4">
                <legend class="w-auto">Personal Information of Contractor Employee</legend>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Name<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" name="name"
                            onkeydown="return /[a-z, ]/i.test(event.key)" onblur="if (this.value == '') {this.value = '';}"
                            onfocus="if (this.value == '') {this.value = '';}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Son/Daughter/Wife of<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" name="son_of"
                            onkeydown="return /[a-z, ]/i.test(event.key)" onblur="if (this.value == '') {this.value = '';}"
                            onfocus="if (this.value == '') {this.value = '';}" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Gender<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control rec" name="gender" required>
                            <option>--Select--</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Caste<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control rec" name="caste" required>
                            <option>--Select--</option>
                            <option value="ST">ST</option>
                            <option value="SC">SC</option>
                            <option value="OBC">OBC</option>
                            <option value="General">General</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Date of Birth (Age Should be 18 years or
                        above)<span style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control rec" name="dob" id="dob" required max="2100-12-31">

                    </div>
                </div>


                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Blood Group<span
                            style="color:red;font-size: 20px;">*</span></label>
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
                    <label for="permanent_address" class="col-sm-2 col-form-label">
                        Permanent Address <span style="color:red;font-size: 20px;">*</span>
                    </label>
                    <div class="col-sm-10">
                        <textarea class="form-control rec" id="permanent_address" name="address" rows="3"
                            required></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="present_address" class="col-sm-2 col-form-label">
                        Present Address <span style="color:red;font-size: 20px;">*</span>
                    </label>
                    <div class="col-sm-10">
                        <textarea class="form-control rec mb-2" id="present_address" name="present_address" rows="3"
                            required></textarea>

                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" id="sameAddressCheckbox">
                            <label class="form-check-label" for="sameAddressCheckbox">Same as Permanent Address</label>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Mobile Number<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <!--<input type="text" class="form-control rec" name="mobile_no" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength="10" minlength="8" />-->
                        <!-- <input type="number" class="form-control rec" name="mobile_no"/>-->
                        <input class="form-control" id="emergencno" name="mobile_no" onChange={handleChange}
                            defaultValue={props.updateuser.emergencyNo} type="tel" maxLength="10" minLength="10" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Designation<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control rec" name="job_role" required>
                            <option>--Select--</option>
                            <option value="Site Incharge">Site Incharge</option>
                            <option value="Apprentice">Apprentice</option>
                            <option value="Army Guard">Army Guard</option>
                            <option value="ASST.MANAGER">ASST.MANAGER</option>
                            <option value="Bagging &amp; Stiching Operator">Bagging &amp; Stiching Operator</option>
                            <option value="Assignment Manager">Assignment Manager</option>
                            <option value="Belt Jointer">Belt Jointer</option>
                            <option value="BLASTER">BLASTER</option>
                            <option value="BREAKER OPERATOR">BREAKER OPERATOR</option>
                            <option value="carpenter">carpenter</option>
                            <option value="Chemist">Chemist</option>
                            <option value="Civil Work">Civil Work</option>
                            <option value="Cook">Cook</option>
                            <option value="CRANE OPERATOR">CRANE OPERATOR</option>
                            <option value="Cutter Cum Welder">Cutter Cum Welder</option>
                            <option value="DOZER OPERATOR">DOZER OPERATOR</option>
                            <option value="Driver">Driver</option>
                            <option value="Electrician">Electrician</option>
                            <option value="EXCAVATOR OPERATOR">EXCAVATOR OPERATOR</option>
                            <option value="Fireman">Fireman</option>
                            <option value="Fitter">Fitter</option>
                            <option value="Gas-Cutter">Gas-Cutter</option>
                            <option value="Head Guard">Head Guard</option>
                            <option value="Helper">Helper</option>
                            <option value="HMV DRIVER">HMV DRIVER</option>
                            <option value="HR OFFICE">HR OFFICE</option>
                            <option value="Inplant Trainee">Inplant Trainee</option>
                            <option value="Inspector">Inspector</option>
                            <option value="Intern">Intern</option>
                            <option value="Labour">Labour</option>
                            <option value="Lashing of Wire Rod Coil">Lashing of Wire Rod Coil</option>
                            <option value="LOADER OPERATOR">LOADER OPERATOR</option>
                            <option value="MACHANIC">MACHANIC</option>
                            <option value="Manager">Manager</option>
                            <option value="Mason">Mason</option>
                            <option value="Mazdoor">Mazdoor</option>
                            <option value="MINES FOREMAN">MINES FOREMAN</option>
                            <option value="MINES MANAGER">MINES MANAGER</option>
                            <option value="MINING MATE">MINING MATE</option>
                            <option value="Mining Sardar">Mining Sardar</option>
                            <option value="Office Assistant">Office Assistant</option>
                            <option value="OFFICER">OFFICER</option>
                            <option value="Operator">Operator</option>
                            <option value="Painter">Painter</option>
                            <option value="Proprietor">Proprietor</option>
                            <option value="Reza">Reza</option>
                            <option value="Rigger">Rigger</option>
                            <option value="Road Fitter">Road Fitter</option>
                            <option value="Safety Officer">Safety Officer</option>
                            <option value="Safety Supervisor">Safety Supervisor</option>
                            <option value="Security Guard">Security Guard</option>
                            <option value="Senior Officer">Senior Officer</option>
                            <option value="Service-Engineer">Service-Engineer</option>
                            <option value="Shift Operation &amp; Maintenance">Shift Operation &amp; Maintenance</option>
                            <option value="Shift Supervisor">Shift Supervisor</option>
                            <option value="Site In-Charge">Site In-Charge</option>
                            <option value="Store Keeper">Store Keeper</option>
                            <option value="Supervisor">Supervisor</option>
                            <option value="Sweeper">Sweeper</option>
                            <option value="Technician">Technician</option>
                            <option value="Village Co-Ordinator">Village Co-Ordinator</option>
                            <option value="Visual Assistant">Visual Assistant</option>
                            <option value="Waiter">Waiter</option>
                            <option value="Welder">Welder</option>

                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Passport Size Photo (Max.2 MB only jpg &
                        jpeg)<span style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control rec" name="upload_photo" required accept="image/*" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Skill Type<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control rec" name="skilled_type" id="skill_type" required>
                            <option>--Select--</option>
                            @if($skills->count() > 0)
                                @foreach(@$skills as $skill)
                                    <option value="{{@$skill->id}}">{{@$skill->skill_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Skill Rate<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" name="skill_rate" id="skill_rate" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Department<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">

                        <select class="form-control rec" id="department" name="department_id" required>
                            <option>--Select--</option>
                            @if($departments->count() > 0)

                                @foreach(@$departments as $department)
                                    <option value="{{@$department->id}}">{{@$department->department_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </fieldset>
            <fieldset class="border p-4">
                <legend class="w-auto">Education & Experience</legend>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Education<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">

                        <select class="form-control rec eduacation_id" id="eduacation_id" name="education" required>
                            <option>--Select--</option>
                            <option value="Below-Matric">Below-Matric</option>
                            <option value="Matric">Matric</option>
                            <option value="Diploma">Diploma</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Graduate">Graduate</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="board_name" style="display: none;">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Board / University Name<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" name="board_name">
                    </div>
                </div>
                <div class="form-group row" id="upload_result" style="display: none;">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Upload Result (Max.2 MB)<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control rec" name="upload_result" accept="application/pdf" />
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Experience<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" name="experience" required>
                    </div>
                </div>

            </fieldset>
    </div>
    <div class="form-group row" style="display: none;">
        <label for="form-control-label" class="col-sm-2 col-form-label">Any Critical Disease<span
                style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec" name="any_disease">
                <option>--Select--</option>
                <option value="None">None</option>
                <option value="Heart-Problem">Heart-problem</option>
                <option value="Hypertension">Hypertension</option>
                <option value="Diabetes">Diabetes</option>
            </select>
        </div>
    </div>
    <!--<fieldset class="border p-4">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <legend class="w-auto">UAN </legend>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="form-group row">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label for="form-control-label" class="col-sm-2 col-form-label">UAN / PF<span style="color:red;font-size: 20px;">*</span></label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="col-sm-10">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <input type="text" class="form-control rec" name="uan_no" required maxlength="12"> 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="form-group row">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label for="form-control-label" class="col-sm-2 col-form-label">UAN Document (Max.2 MB)<span style="color:red;font-size: 20px;">*</span></label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="col-sm-10">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <input type="file" class="form-control rec" name="uan_copy"  accept="application/pdf" / required>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              </fieldset>-->



    <fieldset class="border p-4">
        <legend class="w-auto">Identity Proof </legend>


        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Identity Proof<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <select class="form-control rec" name="identity_proof" id="identity_proof" required>
                    <option>--Select--</option>
                    <option value="Aadhar">Aadhar</option>
                    {{-- <option value="PAN">PAN</option>
                    <option value="DRIVING">DRIVING</option>
                    <option value="VOTER-NO">VOTER-NO</option> --}}
                </select>
            </div>
        </div>

        <div class="form-group row" id="addhar">
            <label for="form-control-label" class="col-sm-2 col-form-label">Unique ID No<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <!--<input type="text" class="form-control rec cb" name="unique_id_no"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"maxlength="12"  />-->

                <input type="tel" class="form-control rec cb" name="unique_id_no" maxlength="12" minlegth="12" />
            </div>
        </div>

        <div class="form-group row" id="voter" style="display:none;">
            <label for="form-control-label" class="col-sm-2 col-form-label">Unique ID No<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="unique_id_no1" maxlength="10">
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Upload ID Proof Front (Max.2 MB)<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="file" class="form-control rec" name="upload_unique_id" accept="application/pdf" />
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Upload ID Proof Back (Max.2 MB)<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="file" class="form-control rec" name="upload_unique_id_back" accept="application/pdf" />
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Identification Mark<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="id_mark" />
            </div>
        </div>


    </fieldset>
    <fieldset class="border p-4">
        <legend class="w-auto">EPF & ESIC / Workmen Compensation Policy</legend>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Please Select ESIC/WCP<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <select class="form-control rec" name="esic_type" id="esic_type">
                    <option value="">--Select--</option>
                    <option value="ESIC">ESIC</option>
                    <option value="WCP">Workman Compensation Policy </option>
                </select>
            </div>
        </div>

        <div class="form-group row" id="esicno">
            <label for="form-control-label" class="col-sm-2 col-form-label">ESIC<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="esic_no" maxlength="20" required value="" id="esic_no">
            </div>
        </div>
        <div class="form-group row" id="esicdoc">
            <label for="form-control-label" class="col-sm-2 col-form-label">ESIC Document (Max.2 MB)<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="file" class="form-control rec" name="esic_document" required value="" id="esic_doc">

            </div>
        </div>
        <div class="form-group row" id="wcpno">
            <label for="form-control-label" class="col-sm-2 col-form-label">Workman Compensation No<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="wcp_no" readonly value="{{@$approver->wcp_no}}"
                    id="wcp_no">
            </div>
        </div>

        <div class="form-group row" id="wcpvalidity">
            <label for="form-control-label" class="col-sm-2 col-form-label">Workman Compensation Validity<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="date" class="form-control rec" name="wcp_validity" readonly
                    value="{{@$approver->wcp_validity}}" id="wcp_validity">
            </div>
        </div>

        <div class="form-group row" id="wcpvalidity_doc">
            <label for="form-control-label" class="col-sm-2 col-form-label">Workman Compensation Doc<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="wcp_document" readonly value="{{@$approver->wcp_doc}}"
                    id="wcp_validity">
                <a href="https://wps.jamipol.com/public/documents/clm_pics/{{$approver->wcp_doc}}" target="_blank">
                    <button class="btn" type="button"><i class="fa fa-eye"></i> View Document</button> </a>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">UAN / PF<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="uan_no" required maxlength="12">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">UAN Document (Max.2 MB)<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="file" class="form-control rec" name="uan_copy" accept="application/pdf" / required>
            </div>
        </div>
    </fieldset>
    <div class="form-group row" style="display:none">
        <label for="form-control-label" class="col-sm-2 col-form-label">Insurance valid from<span
                style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="date" class="form-control rec" name="ins_valid_from">
        </div>
    </div>
    <div class="form-group row" style="display:none">
        <label for="form-control-label" class="col-sm-2 col-form-label">Insurance valid To<span
                style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="date" class="form-control rec" name="ins_valid_to">
        </div>
    </div>
    <div class="form-group row" style="display:none">
        <label for="form-control-label" class="col-sm-2 col-form-label">Upload Insurance(Max.500 KB)<span
                style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="file" class="form-control rec" name="upload_ins">
        </div>
    </div>
    <fieldset class="border p-4">
        <legend class="w-auto">Medical & Police Verification Details</legend>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Medical Examination Date<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="date" class="form-control rec" name="medical_exam_date" id="medical_exam_date" required
                    max="2100-12-31">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Medical Fitness Copy (By Government Approved)
                (Max.2 MB)<span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="file" class="form-control rec" name="upload_fitness" required accept="application/pdf" />
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label"> Valid Passport (Yes / No)<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <select class="form-control rec" name="valid_passport" id="valid_passport" required>
                    <option>--Select--</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
        </div>

        <div class="form-group row" id="passport_no">
            <label for="form-control-label" class="col-sm-2 col-form-label">Passport No<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control pn" name="passport_no" id="pn">
            </div>
        </div>
        <div class="form-group row" id="passport_validity">
            <label for="form-control-label" class="col-sm-2 col-form-label">Passport Validity<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="date" class="form-control pv" name="passport_validity" id="pv" />
            </div>
        </div>
        <div class="form-group row" id="passport_copy">
            <label for="form-control-label" class="col-sm-2 col-form-label">Passport Copy <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="file" class="form-control pc" name="passport_copy" accept="image/*" id="pc" />
            </div>
        </div>
        <div class="form-group row" id="police_verification_date">
            <label for="form-control-label" class="col-sm-2 col-form-label">Police Verification Date<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control pvv" name="police_verification_date" max="2100-12-31" id="pvv">
            </div>
        </div>
        <div class="form-group row" id="police_verification_copy">
            <label for="form-control-label" class="col-sm-2 col-form-label">Police Verification Copy (Max.2 MB)<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="file" class="form-control pcc" name="police_verification_copy" accept="application/pdf"
                    id="pcc" />
            </div>
        </div>
    </fieldset>







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




    <br>
    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                Request
            </button>
        </div>
    </div>

    </form>
    </div>
    </div>
    </div>
    </div>
    </div>
    <br>
@endsection
@section('scripts')
    <script>
        $('#skill_type').on('change', function () {
            $('#skill_rate').val('0');
            var skill_id = $('#skill_type').val();

            $.ajax({
                type: 'GET',
                url: "{{ route('admin.skill_rateGet') }}/" + skill_id,
                dataType: "json",
                success: function (data) {
                    // console.log(JSON.stringify(data));
                    // Assuming `data.skill_rate` contains the value you need
                    $('#skill_rate').val(data[0].skill_rate); // ✅
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        });
    </script>

    <script>
        const permanent = document.getElementById('permanent_address');
        const present = document.getElementById('present_address');
        const checkbox = document.getElementById('sameAddressCheckbox');

        checkbox.addEventListener('change', function () {
            if (this.checked) {
                present.value = permanent.value;
                present.readOnly = true;
            } else {
                present.value = '';
                present.readOnly = false;
            }
        });

        // Optional: If user edits permanent address after checkbox is checked, update present too
        permanent.addEventListener('input', function () {
            if (checkbox.checked) {
                present.value = permanent.value;
            }
        });
    </script>

    <script type="text/javascript">


        $("#dob").change(function () {
            // alert('ok');

            var today = new Date();
            var birthDate = new Date($('#dob').val());
            //alert(birthDate);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            if (age < 18) {
                alert('Age should not be less than 18 years');
                window.location.reload();
            }
            // alert(age);
            //return $('#age').html(age+' years old');
        });
    </script>

    <script type="text/javascript">


        $("#pvv").change(function () {
            //  alert('ok');
            var date = new Date($('#pvv').val());
            var today = new Date();
            var current_year = today.getFullYear();
            var pvv = date.getFullYear() + 3;
            var current_year_to = today.getFullYear() + 3;
            //var pvm = date.getMonth() + 3;
            //var pvd = date.getDate() + 3;
            // alert(current_year_to);

            // alert(current_year);

            if (current_year <= pvv && current_year <= current_year_to) {

            } else {
                alert('Police Verification Expire');
            }

        });
    </script>

    <script type="text/javascript">


        $("#medical_exam_date").change(function () {
            // alert('ok');
            var date = new Date($('#medical_exam_date').val());
            var today = new Date();
            var current_date = today.getDate();
            var pvv = date.getDate() + 180;;
            var current_date_to = today.getDate() + 180;

            // alert(current_date_to);
            if (current_date <= pvv && current_date <= current_date_to) {

            } else {
                alert('Expired Medical Fitness Validity');
            }

        });
    </script>

    <script type="text/javascript">
        $("#eduacation_id").on('change', function () {
            var modeval = $(this).val();
            // alert(modeval);
            if (modeval == 'Below-Matric') {
                $('#board_name').hide();
                $('#upload_result').hide();
            }
            else if (modeval == 'Matric' || modeval == 'Diploma' || modeval == 'Intermediate' || modeval == 'Graduate') {
                $('#board_name').show();
                $('#upload_result').show();
            }
        });



    </script>
    <script type="text/javascript">
        $("#identity_proof").on('change', function () {
            var modeval = $(this).val();
            //  alert(modeval);
            if (modeval == 'Aadhar') {
                $('#addhar').show();
                $('#voter').hide();
                $('.cb').prop('required', true);
                $('.cb').prop('maxLength', 12);
                $('.cb').prop('minLength', 12);
            }
            else {
                $('#addhar').hide();
                $('#voter').show();
                $('.cb').prop('required', false);
            }
        });



    </script>
    <script type="text/javascript">
        $("#esic_type").on('change', function () {
            var modeval = $(this).val();
            //  alert(modeval);
            if (modeval == 'ESIC') {
                $('#esicno').show();
                $('#esicdoc').show();
                $('#wcpno').hide();
                $('#wcpvalidity').hide();
                $('#wcpvalidity_doc').hide();
                $('#esic_no').prop('required', true);
                $('#esic_doc').prop('required', true);
                $('#esic_no').prop('maxLength', 20);
                $('#wcp_no').prop('required', false);
                $('#wcp_validity').prop('required', false);
            }
            else {
                $('#esicno').hide();
                $('#esicdoc').hide();
                $('#wcpno').show();
                $('#wcpvalidity').show();
                $('#wcpvalidity_doc').show();
                $('#esic_no').prop('required', false);
                $('#esic_doc').prop('required', false);
                $('#esic_no').prop('maxLength', 0);
                $('#wcp_no').prop('required', true);
                $('#wcp_validity').prop('required', true);
            }
        });



    </script>
    <script type="text/javascript">
        $("#valid_passport").on('change', function () {
            var modeval = $(this).val();
            // alert(modeval);
            if (modeval == 'Yes') {
                //alert('ok');
                $('#passport_no').show();
                $('#passport_validity').show();
                $('#passport_copy').show();
                $('#police_verification_date').hide();
                $('#police_verification_copy').hide();
                //$('.cb').prop('required', true);

            }
            else {
                $('#passport_no').hide();
                $('#passport_validity').hide();
                $('#passport_copy').hide();
                $('#police_verification_date').show();
                $('#police_verification_copy').show();
            }


            if (modeval == 'Yes') {
                // alert('ok');
                $('#pn').prop('required', true);
                $('#pn').prop('required', true);
                $('#pv').prop('required', true);
                $('#pc').prop('required', true);
                $('#pvv').prop('required', false);
                $('#pcc').prop('required', false);

            } else {

                $('#pn').prop('required', false);
                $('#pv').prop('required', false);
                $('#pc').prop('required', false);
                $('#pvv').prop('required', true);
                $('#pcc').prop('required', true);
            }



        });

    </script>
    <!--<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>-->
    <!--<script type="text/javascript">

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              $('#quickForm').validate({
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                rules: {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  mobile_no: {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    required: true,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    minlength: 10,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    maxlength: 10
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  },
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  terms: {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    required: true
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  },
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                },
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                messages: {

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  mobile_no: {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    required: "Please provide a Mobile Number ",
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    minlength: "Your Mobile Number must be at least 10 digit long"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  },
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  terms: "Please accept our terms"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                },
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                errorElement: 'span',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                errorPlacement: function (error, element) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  error.addClass('invalid-feedback');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  element.closest('.form-group').append(error);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                },
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                highlight: function (element, errorClass, validClass) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  $(element).addClass('is-invalid');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                },
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                unhighlight: function (element, errorClass, validClass) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  $(element).removeClass('is-invalid');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              });

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script> -->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>-->



    <script type="text/javascript">

        $(document).ready(function () {

            $.validator.addMethod("minAge", function (value, element, min) {
                var today = new Date();
                var birthDate = new Date(value);
                var age = today.getFullYear() - birthDate.getFullYear();

                if (age > min + 1) {
                    return true;
                }

                var m = today.getMonth() - birthDate.getMonth();

                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                return age >= min;
            }, "You are not old enough!");

            $("#quickForm").validate({
                rules: {
                    dob: {
                        required: true,
                        minAge: 18
                    }
                },
                messages: {
                    dob: {
                        required: "Please enter you date of birth.",
                        minAge: "You must be at least 18 years old!"
                    }
                }
            });

        });

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
        integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
        integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        $('#pvv').datetimepicker({
            format: 'Y/m/d'
        });
    </script>
@endsection