<?php 
use App\Department;
use App\UserLogin;
use App\Division;

$gatepassv = DB::table('Clms_gatepass')->where('id', $id)->first();
//echo $id;
//exit;
@$department_p = Department::where('id', @$gatepassv->department)->first();
@$division_p = Division::where('id', @$gatepassv->division_id)->first();
@$approver = UserLogin::where('id', @$gatepassv->created_by)->first();
@$approver_security = UserLogin::where('id', @$gatepassv->security_print_id)->first();
//@$work = DB::table('work_order')->where('id',@$gatepassv->work_order_no)->first();
@$approver = UserLogin::where('id', Session::get('user_idSession'))->first();

@$workorder_code = DB::table('work_order')->where('vendor_code', @$approver->vendor_code)->get();

?>

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="">Contractor Gatepass Renew</a></li>
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

        <!--<form method="POST" action="{{route('RequestVGatepassPost')}}">-->
        <form action="{{route('admin.gatepass_clms_permit.store')}}" method="POST" autocomplete="off"
            enctype="multipart/form-data">
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
                <input type="hidden" class="form-control" value="Renew" name="gp_status" readonly>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Code<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" value="{{ @$approver->vendor_code}}" name="visitor_name"
                            readonly>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="Workorder" class="col-sm-2 col-form-label">Order No<span style="color:red;font-size: 20px;">
                            *</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="Workorder" name="work_order" required
                            value="{{$gatepassv->work_order_no}}">
                    </div>

                    <div class="col-sm-2" style="display:none;"><a class="btn btn-info btn-xm" id="">Check Validity</a>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Work Order Validity<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control validity" readonly name="work_order_validity"
                            id="order_validity" required value="{{$gatepassv->work_order_validity}}">
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
                                    <option value="{{@$user->id}}" @if($user->id == $gatepassv->pending_excueting_by) {{'selected'}}
                                    @endif>{{@$user->name}}</option>
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


            <!-- <link href="{{URL::to('public/css/sweetalert.css')}}" rel="stylesheet">
            <script type="text/javascript" src="{{URL::to('public/js/app.js')}}"> </script>
            <script type="text/javascript" src="{{URL::to('public/js/sweetalert.js')}}"> </script>
            <script type="text/javascript"
                src="{{URL::to('node_modules/jquery-datetimepicker/jquery.datetimepicker.js')}}"> </script>
          
            <script type="text/javascript" src="{{URL::to('public/js/dataTables.buttons.min.js')}}"> </script>
            <script type="text/javascript" src="{{URL::to('public/js/jszip.min.js')}}"> </script>
            <script type="text/javascript" src="{{URL::to('public/js/buttons.html5.min.js')}}"> </script>
            <script type="text/javascript" src="{{URL::to('public/js/all.js')}}"> </script>
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
            <link rel="stylesheet"
                href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.22.0/autocomplete.jquery.min.js"
                integrity="sha512-sYSJW8c3t/hT4R6toey7NwQmlrPMTqvDk10hsoD8oaeXUZRexAzrmpp5kVlTfy6Ru7b1+Tte2qBrRE7FOX1vgA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src='https://code.jquery.com/jquery-latest.min.js'></script>
            <link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet">
            <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

            <!-- jQuery UI JS -->
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

            <!-- jQuery UI CSS (for styling the autocomplete dropdown) -->
            <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <script type="text/javascript">
                var path = "{{ route('admin.autocomplete')}}";

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
            <fieldset class="border p-4">
                <legend class="w-auto">Personal Information</legend>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Name<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" name="name" value="{{@$gatepassv->name}}" readonly>
                        <input type="hidden" class="form-control rec" name="job_role" value="{{@$gatepassv->job_role}}"
                            readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Son/Daughter/Wife of<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" name="son_of" value="{{@$gatepassv->son_of}}" readonly>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Gender<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" name="gender" value="{{@$gatepassv->gender}}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Caste<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" name="caste" value="{{@$gatepassv->caste}}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Date of Birth<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control rec" name="dob" value="{{@$gatepassv->date_of_birth}}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Blood Group<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">

                        <input type="text" class="form-control rec" name="blood_group" value="{{@$gatepassv->blood_group}}"
                            readonly>

                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Permanent Address<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <textarea class="form-control rec" id="permanent_address" name="address" rows="3" required
                            value="">{{@$gatepassv->address}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="present_address" class="col-sm-2 col-form-label">
                        Present Address <span style="color:red;font-size: 20px;">*</span>
                    </label>
                    <div class="col-sm-10">
                        <textarea class="form-control rec mb-2" id="present_address" name="present_address" rows="3"
                            required>{{@$gatepassv->present_address}}</textarea>

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
                        <input type="text" class="form-control rec" name="mobile_no" value="{{@$gatepassv->mobile_no}}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Passport Size Photo<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control rec" name="upload_photo">
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
                                    <option value="{{@$skill->id}}" @if($gatepassv->skill_type == $skill->id) {{'selected'}} @endif>
                                        {{@$skill->skill_name}}
                                    </option>
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
                    <label for="form-control-label" class="col-sm-2 col-form-label">Employee P-No<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rec" name="employeep_no" value="{{$gatepassv->emp_pno}}"
                            required readonly>
                        <input type="hidden" class="form-control rec" name="employeep_no_sl"
                            value="{{$gatepassv->emp_pno_sl}}" required readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Department<span
                            style="color:red;font-size: 20px;">*</span></label>
                    <div class="col-sm-10">

                        <select class="form-control rec" id="department" name="department_id" required>
                            <option>--Select--</option>
                            @if($departments->count() > 0)
                                @foreach($departments as $division_id => $divisionDepartments)
                                    @foreach($divisionDepartments as $department)
                                        <option value="{{ $department->id }}" @if($gatepassv->department_id == $department->id) selected
                                        @endif>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            @endif


                        </select>
                    </div>
                </div>
    </div>
    </fieldset>




    <fieldset class="border p-4">
        <legend class="w-auto">Education & Experience</legend>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Education<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="education" value="{{@$gatepassv->education}}" readonly>

            </div>
        </div>

        <?php 
                                                                                                                                                                                                                                                                                    if ($gatepassv->education != 'Below-Matric') {
                                                                                                                                                                                                                                                                                    ?>


        <div class="form-group row" id="board_name">
            <label for="form-control-label" class="col-sm-2 col-form-label">Board / University Name<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="board_name" value="{{@$gatepassv->board_name}}" readonly>
            </div>
        </div>
        <div class="form-group row" id="upload_result">
            <label for="form-control-label" class="col-sm-2 col-form-label">Upload Result <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="hidden" class="form-control rec" name="upload_result" value="{{@$gatepassv->upload_result}}">
                <a class="btn" href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_result}}"
                    target="_blank">
                    <i class="fa fa-download"></i> View File </a>
            </div>
        </div>
        <?php
    }
                                                                                                                                                                                                                                                                                    ?>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Experience<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="experience" value="{{@$gatepassv->experience}}" readonly>
            </div>
        </div>

    </fieldset>
    <fieldset class="border p-4">
        <legend class="w-auto">Identity Proof </legend>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Identity Proof<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="identity_proof" value="{{@$gatepassv->identity_proof}}"
                    readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Unique ID No<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="unique_id_no" value="{{@$gatepassv->unique_id_no}}"
                    readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Upload ID Proof Front<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="hidden" class="form-control rec" name="upload_unique_id"
                    value="{{@$gatepassv->upload_id_proof}}">
                <a class="btn" href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_id_proof}}"
                    target="_blank">
                    <i class="fa fa-download"></i> View File </a>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Upload ID Proof Back<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="hidden" class="form-control rec" name="upload_unique_id_back"
                    value="{{@$gatepassv->upload_id_proof_back}}">
                <a class="btn" href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_id_proof_back}}"
                    target="_blank">
                    <i class="fa fa-download"></i> View File </a>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Identification Mark<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="id_mark" value="{{@$gatepassv->id_mark}}" readonly />
            </div>
        </div>

    </fieldset>

    <fieldset class="border p-4">
        <legend class="w-auto">EPF & ESIC </legend>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">UAN / PF<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="uan_no" value="{{@$gatepassv->uan_no}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">UAN Document<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="hidden" class="form-control rec" name="uan_copy" value="{{$gatepassv->upload_pf_copy}}"
                    accept="image/*" capture="camera">

                <a class="btn" href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_pf_copy}}"
                    target="_blank">
                    <i class="fa fa-download"></i> View File </a>
            </div>
        </div>
        <input type="hidden" value="{{$gatepassv->esic_type}}" name="esic_type">
        @if($gatepassv->esic_type == 'ESIC')
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">ESIC<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control rec" name="esic_no" value="{{@$gatepassv->esic}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">EISC Document<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="hidden" class="form-control rec" name="esic_document" value="{{@$gatepassv->esic_document}}">


                    <a class="btn" href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->esic_document}}"
                        target="_blank">
                        <i class="fa fa-download"></i> View File </a>

                </div>
            </div>
        @elseif($gatepassv->esic_type == 'WCP')

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
            <div class="form-group row" id="wcpvalidity">
                <label for="form-control-label" class="col-sm-2 col-form-label">Workman Compensation Doc<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control rec" name="wcp_document" readonly value="{{@$approver->wcp_doc}}"
                        id="wcp_validity">
                    <a href="https://wps.jamipol.com/public/documents/clm_pics/{{$approver->wcp_doc}}" target="_blank">
                        <button class="btn" type="button"><i class="fa fa-eye"></i> View Document</button> </a>
                </div>
            </div>
        @endif
    </fieldset>

    <fieldset class="border p-4">
        <legend class="w-auto">Medical & Police Verification Details</legend>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Medical Examination Date<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="date" class="form-control rec" name="medical_exam_date" max="2100-12-31"
                    value="{{@$gatepassv->medical_examination_date}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Medical Fitness Copy (By Government
                Approved)<span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-8">
                <input type="hidden" class="form-control rec" name="upload_fitness1"
                    value="{{@$gatepassv->upload_fittenss_copy}}">
                <input type="file" class="form-control rec" name="upload_fitness">
            </div>
            <a class="btn" href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_fittenss_copy}}"
                target="_blank">
                <i class="fa fa-download"></i> View File </a>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label"> Valid Passport (Yes / No)<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <select class="form-control rec" name="" id="valid_passport" required>
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
                <input type="file" class="form-control pc" name="passport_copy" accept="application/pdf" id="pc" />
            </div>
        </div>
        <div class="form-group row" id="police_verification_date">
            <label for="form-control-label" class="col-sm-2 col-form-label">Police Verification Date<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="date" class="form-control rec pvv" name="police_verification_date" required max="2100-12-31"
                    value="{{@$gatepassv->police_verification_date}}" id="pvv">
            </div>

        </div>
        <div class="form-group row" id="police_verification_copy">
            <label for="form-control-label" class="col-sm-2 col-form-label">Police Verification Copy<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-8">
                <input type="file" class="form-control rec pcc" name="police_verification_copy" accept="application/pdf" /
                    value="{{@$gatepassv->police_verification_copy}}" id="pcc">
            </div>
            <a class="btn" href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->police_verification_copy}}"
                target="_blank">
                <i class="fa fa-download"></i> View File </a>
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
                Renew
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
        function fetchSkillRate() {
            $('#skill_rate').val('0'); // reset
            var skill_id = $('#skill_type').val();
            if (!skill_id) return; // exit if no skill selected

            $.ajax({
                type: 'GET',
                url: "{{ route('admin.skill_rateGet') }}/" + skill_id,
                dataType: "json",
                success: function (data) {
                    if (data && data.length > 0) {
                        $('#skill_rate').val(data[0].skill_rate); // ✅
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }
        $(document).ready(function () {
            // Run on change
            $('#skill_type').on('change', fetchSkillRate);

            // Run once on page load
            fetchSkillRate();
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
        $("#eduacation_id").on('change', function () {
            var modeval = $(this).val();
            // alert(modeval);
            if (modeval == 'Below-Matric') {
                $('#board_name').hide();
                $('#upload_result').hide();
            }
            else if (modeval == 'Matric' || modeval == 'Deploma' || modeval == 'Intermediate' || modeval == 'Graduate') {
                $('#board_name').show();
                $('#upload_result').show();
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
@endsection