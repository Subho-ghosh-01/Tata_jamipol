<?php 
use App\Department;
use App\UserLogin;
use App\ChangeRequest;

?>

@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.approve_clms.index')}}">List Of Contractor's GatePass</a></li>
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">List of Contractor's GatePass </h1>
    </div>


    @if(Session::get('clm_role') == 'Shift_incharge' || Session::get('clm_role') == 'hr_dept' || Session::get('clm_role') == 'Safety_dept' || Session::get('clm_role') == 'plant_head' || Session::get('clm_role') == 'Executing_agency')
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                    aria-selected="true">Pending For Approval</a>
            </li>

            <li class="nav-item">
                <a class="nav-link " id="home1-tab" data-toggle="tab" href="#home1" role="tab" aria-controls="home"
                    aria-selected="true">Approve/Rejected</a>
            </li>

            <li class="nav-item">
                <a class="nav-link " id="home2-tab" data-toggle="tab" href="#home2" role="tab" aria-controls="home"
                    aria-selected="true">Exit Employee List</a>
            </li>



        </ul>

    @else
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                    aria-selected="true">@if(Session::get('clm_role') == 'security')
                        Gatepass Approved
                    @elseif(Session::get('user_sub_typeSession') == 3)
                        ALL Gatepass
                        @else My Gatepass
                    @endif</a>
            </li>
            @if(Session::get('clm_role') != 'security')
                <li class="nav-item">
                    <a class="nav-link " id="home-tab" data-toggle="tab" href="#home1" role="tab" aria-controls="home"
                        aria-selected="true">
                        @if(Session::get('user_sub_typeSession') == 3) Approved/Rejected
                        @else
                            Rejected Gatepass
                        @endif
                    </a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link " id="home2-tab" data-toggle="tab" href="#home2" role="tab" aria-controls="home"
                    aria-selected="true">Exit Employee List</a>
            </li>

            <li class="nav-item">
                <a class="nav-link " id="home3-tab" data-toggle="tab" href="#home3" role="tab" aria-controls="home"
                    aria-selected="true">Change Shift</a>
            </li>
        </ul>
    @endif
    <form action="{{ route('admin.approve.index')}}" method="POST" enctype="multipart/form-data">

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="my-permit">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>GP No</th>
                                <th>Vendor Name</th>
                                <th>Name</th>
                                <th>Work Order No</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($gatepasss->count() > 0)
                                <?php    $count = 1;  ?>
                                @foreach($gatepasss as $gatepass)

                                    @php
                                        @$approver = UserLogin::where('id', @$gatepass->created_by)->first();
                                    @endphp
                                    @php
                                        @$work = DB::table('work_order')->where('id', @$gatepass->work_order_no)->first();

                                        if ($gatepass->return_status == 'Pending_exit') {
                                            $dd = "style=display:none";
                                            $pp = "Employee Exited";
                                        } else {
                                            $dd = '';
                                            $pp = '';
                                        }
                                    @endphp



                                    <tr
                                        style="{{ $gatepass->return_status == 'Pending_exit' ? 'background-color: #f8d7da;' : '' }}">

                                        <td>{{$count++}}</td>
                                        <td>{{$gatepass->full_sl}}</td>
                                        <td>{{@$approver->name}}</td>
                                        <td>{{$gatepass->name}}</td>
                                        <td>{{$gatepass->work_order_no}}</td>
                                        <td>@if($gatepass->status == "Pending_for_shift_incharge")
                                            {{"Pending From Shift Incharge"}}
                                        @elseif($gatepass->status == "Pending_for_hr")
                                                {{"Pending From HR Dept"}}
                                            @elseif($gatepass->status == "Pending_for_safety")
                                                {{"Pending From Safety Dept"}}
                                            @elseif($gatepass->status == "Pending_for_plant_head")
                                                {{"Pending From Plant Head"}}
                                            @elseif($gatepass->status == "Pending_for_security")
                                                {{"Gatepass Approved"}}
                                            @elseif($gatepass->status == "Pending_executing")
                                                {{"Pending For Executing Department"}}
                                            @elseif($gatepass->status == "Rejected")
                                                {{"Rejected"}}
                                            @endif
                                        </td>


                                        <td>
                                            @if($gatepass->created_datetime >= '2024-03-27')
                                                <h5 style="color:red">{{$pp ?? ''}}</h5>
                                                <a class="btn btn-info btn-sm"
                                                    href="{{route('admin.edit_clms_new.edit', \Crypt::encrypt($gatepass->id))}}"
                                                    title="Edit">Details </a>
                                                <button type="button" {{$dd ?? ''}} class="btn btn-danger btn-sm exit-emp-btn"
                                                    data-id="{{ @$gatepass->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#exitEmployeeModal">
                                                    Exit
                                                </button>





                                            @else
                                                <a class="btn btn-info btn-sm"
                                                    href="{{route('admin.edit_clms.edit', \Crypt::encrypt($gatepass->id))}}"
                                                    title="Edit">Details</a>
                                            @endif
                                            @if($gatepass->status == "Pending_for_security" && ($gatepass->created_by == Session::get('user_idSession') or Session::get('user_idSession') == '6'))
                                                <a class="btn btn-info btn-sm"
                                                    href="{{route('admin.renew_clms.edit', \Crypt::encrypt($gatepass->id))}}"
                                                    title="Edit" {{$dd ?? ''}}>Renew</a>
                                            @endif
                                            @if($gatepass->status == "Pending_for_security" && (Session::get('clm_role') == 'security' || Session::get('vms_role') == 'Security'))
                                                <a class="btn btn-info btn-sm"
                                                    href="{{route('admin.printg_clms.printg', \Crypt::encrypt($gatepass->id))}}"
                                                    title="Edit" target="_blank">Print</a>
                                            @endif

                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-emp_pno="{{ @$gatepass->emp_pno }}" data-emp_name="{{@$gatepass->name}}"
                                                data-bs-target="#myModal" id="attendance">
                                                Check Attendance
                                            </button>

                                            <!-- Modal -->


                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade " id="home1" role="tabpanel" aria-labelledby="home1-tab">
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="my-permit1">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>GP No</th>
                                <th>Vendor Name</th>
                                <th>Name</th>
                                <th>Work Order No</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($gatepassss->count() > 0)
                                <?php    $count = 1;?>
                                @foreach($gatepassss as $gatepasss)

                                    @php
                                        @$approver = UserLogin::where('id', @$gatepasss->created_by)->first();
                                    @endphp
                                    @php
                                        @$work = DB::table('work_order')->where('id', @$gatepasss->work_order_no)->first();
                                    @endphp
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{$gatepasss->full_sl}}</td>
                                        <td>{{@$approver->name}}</td>
                                        <td>{{$gatepasss->name}}</td>
                                        <td>{{$gatepasss->work_order_no}}</td>
                                        <td>@if($gatepasss->status == "Pending_for_shift_incharge")
                                            {{"Pending From  Shift Incharge"}}
                                        @elseif($gatepasss->status == "Pending_for_hr")
                                                {{"Pending From HR Dept"}}
                                            @elseif($gatepasss->status == "Pending_for_safety")
                                                {{"Pending From Safety Dept"}}
                                            @elseif($gatepasss->status == "Pending_for_plant_head")
                                                {{"Pending From Plant Head"}}
                                            @elseif($gatepasss->status == "Pending_for_security")
                                                {{"Gatepass Approved"}}
                                            @elseif($gatepasss->status == "Rejected")
                                                {{"Rejected"}}
                                            @endif
                                        </td>


                                        <td>

                                            @if($gatepasss->status == "Pending_for_security" && $gatepasss->created_by == Session::get('user_idSession'))
                                                <a class="btn btn-info btn-sm"
                                                    href="{{route('admin.renew_clms.edit', \Crypt::encrypt($gatepasss->id))}}"
                                                    title="Edit">Renew</a>
                                            @endif
                                            @if($gatepasss->created_datetime >= '2024-03-27')
                                                <a class="btn btn-info btn-sm"
                                                    href="{{route('admin.edit_clms_new.edit', \Crypt::encrypt($gatepasss->id))}}"
                                                    title="Edit">Details </a>
                                            @else
                                                <a class="btn btn-info btn-sm"
                                                    href="{{route('admin.edit_clms.edit', \Crypt::encrypt($gatepasss->id))}}"
                                                    title="Edit">Details</a>
                                            @endif
                                            @if($gatepasss->status == "Pending_for_security" && (Session::get('clm_role') == 'security' || Session::get('vms_role') == 'Security'))
                                                <a class="btn btn-info btn-sm"
                                                    href="{{route('admin.printg.printg', \Crypt::encrypt($gatepasss->id))}}"
                                                    title="Edit">Print</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!--exit employee list-->
            <div class="tab-pane fade " id="home2" role="tabpanel" aria-labelledby="home2-tab">
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="my-permit2">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>GP No</th>
                                <th> Name</th>
                                <th>Vendor Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($gatepassss_exit->count() > 0)
                                <?php    $count = 1;?>
                                @foreach($gatepassss_exit as $gatepasss_exit)
                                    @php
                                        $approver = UserLogin::find($gatepasss_exit->created_by);
                                        $work = DB::table('work_order')->where('id', $gatepasss_exit->work_order_no)->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $gatepasss_exit->full_sl ?? '-' }}</td>
                                        <td>{{ $gatepasss_exit->name ?? '-' }}</td>
                                        <td>{{ $approver->name ?? '-' }}</td> {{-- Approver Name --}}

                                        <td>
                                            @if($gatepasss_exit->return_status == 'Pending_exit')
                                                <p style="color: red;">Exit Processing...</p>
                                            @elseif($gatepasss_exit->return_status == 'completed_exit')
                                                <p style="color:green;">Completed</p>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('admin.exit_emp_details.edit', \Crypt::encrypt($gatepasss_exit->id)) }}"
                                                title="Details">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <style>
                /* Green styled checkbox */
                .form-check-input {
                    width: 100px;
                    height: 18px;
                    border: 2px solid #28a745;
                    /* Bootstrap green */
                    border-radius: 4px;
                    background-color: white;
                    cursor: pointer;
                    transition: all 0.2s ease-in-out;
                }

                .form-check-input:checked {
                    background-color: #28a745;
                    border-color: #28a745;
                }

                .form-check-input:checked::before {
                    content: "✔";
                    display: block;
                    text-align: center;
                    color: white;
                    font-size: 14px;
                    font-weight: bold;
                    line-height: 14px;
                }
            </style>
            <!-- shift changes -->
            <div class="tab-pane fade " id="home3" role="tabpanel" aria-labelledby="home3-tab">
                <div class="table-responsive">

                    <button type="button" id="bulkUpdateBtn" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal"
                        data-bs-target="#updateEmployeeidModal" style="display:none;">
                        Update Selected Shift
                    </button>

                    <table class="table table-striped table-sm" id="my-permit3">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all" class="select-all form-check-input">
                                    <strong>All</strong>
                                </th>
                                {{-- Master Checkbox
                                --}}
                                <th>Sl No</th>
                                <th>GP No</th>
                                <th>Emp Id</th>
                                <th>Name</th>
                                <th>Vendor Name</th>

                                <th>Shift</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($gatepasss_shift->count() > 0)
                                <?php    $count = 1;?>
                                @foreach($gatepasss_shift as $gatepass_shift)
                                    @php
                                        $approver = UserLogin::find($gatepass_shift->created_by);
                                        $work = DB::table('work_order')->where('id', $gatepass_shift->work_order_no)->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input row-checkbox" name="selected_ids[]"
                                                value="{{ $gatepass_shift->id }}">
                                        </td>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $gatepass_shift->full_sl ?? '-' }}</td>
                                        <td>{{ $gatepass_shift->emp_pno ?? '-'   }}</td>
                                        <td>{{ $gatepass_shift->name ?? '-' }}</td>
                                        <td>{{ $approver->name ?? '-' }}</td> {{-- Approver Name --}}

                                        <td>
                                            {{ $gatepass_shift->shift ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>


        </div>


    </form>
    <!-- Bootstrap 5 CSS -->

    <!-- Bootstrap 5 JS Bundle (includes Popper.js) -->







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('select-all');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');

            selectAllCheckbox.addEventListener('change', function () {
                rowCheckboxes.forEach(cb => cb.checked = this.checked);
            });
        });
    </script>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        Attendance of <strong id="emp_name_set" class="text-primary"></strong>
                        <select id="monthSelect" class="form-select form-select-sm d-inline-block w-auto">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                </option>
                            @endfor
                        </select> <select id="yearSelect"
                            class="form-select form-select-sm d-inline-block
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            w-auto ms-1">
                            @for ($y = 2020; $y <= date('Y'); $y++)
                                <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </h5>
                    <div class="d-flex gap-1">
                        <button id="refreshBtn" class="btn btn-outline-primary btn-sm"
                            style="width: 100px;">Refresh</button>

                        <button class="btn btn-outline-secondary btn-sm" style="width: 100px;"
                            data-bs-dismiss="modal">Close</button>
                    </div>

                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                    <!-- Legend -->
                    <div class="mb-3 p-1 border rounded bg-light shadow-sm small fw-bold text-dark">
                        <strong class="d-block mb-2 text-uppercase" style="font-size: 7px;">📘 Nomenclature of
                            Symbols:</strong>
                        <span class="text-success">P</span> = Present,&nbsp;
                        <span class="text-danger">Ab</span> = Absent,&nbsp;
                        <span class="text-warning">OFF</span> = Offday,&nbsp;
                        <span class="text-primary">PL</span> = Privilege Leave,&nbsp;
                        <span class="text-primary">CL</span> = Casual Leave,&nbsp;
                        <span class="text-primary">FL</span> = Flexi Leave,&nbsp;
                        <span class="text-primary">FLP</span> = Flexi Paid,&nbsp;
                        <span class="text-primary">MP</span> = Missed Punch,&nbsp;

                    </div>



                    <div id="loader" class="text-center my-2" style="display: none;">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <span class="ms-2 text-muted">Loading attendance...</span>
                    </div>
                    <!-- Calendar Placeholder -->
                    <div id="calendarContainer" class="table-responsive"></div>

                    <!-- Footer Note -->
                    <div class="text-primary small mt-3">
                        <strong>📌 Instructions:</strong><br>
                        <ul class="mb-1 ps-3">
                            <li>This is a <strong>Vendor Employee Attendance</strong> list.</li>
                            <li>You can modify the attendance status (e.g., change <code>Ab</code> to <code>PL</code>,
                                <code>CL</code>, etc.).
                            </li>
                            <li><span class="text-danger fw-bold">⚠️ Once changed, you cannot modify the same date
                                    again.</span></li>
                        </ul>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <style>
        select.form-select:disabled {
            color: #212529;
            opacity: 1 !important;
            -webkit-text-fill-color: #212529;
        }

        #attendance_message {
            font-size: 14px;
            font-weight: bold;
            color: #dc3545;
            /* Bootstrap red */
        }
    </style>


    <!-- JavaScript to Handle Dynamic Calendar -->
    <script>
        function generateCalendar(month, year, user_id) {
            $('#calendarContainer').html();
            $('#loader').show(); // Show loader
            var path = "{{ route('admin.getMonthlyAttendance') }}/" + user_id + '/' + month + '/' + year
            // Call your Laravel API
            $.ajax({
                url: path,
                type: "GET",
                dataType: "json",

                success: function (response) {
                    const attendanceData = response.status || {};
                    const holidays = response.holidays || [];

                    const daysInMonth = new Date(year, month, 0).getDate();
                    const startDay = new Date(year, month - 1, 1).getDay();
                    let html = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <table class="table table-bordered text-center align-middle small" style="table-layout: fixed;">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <thead class="table-light">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <th>SUN</th><th>MON</th><th>TUE</th><th>WED</th><th>THU</th><th>FRI</th><th>SAT</th>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </thead>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <tbody>`;
                    let dayCounter = 1;

                    for (let week = 0; week < 6 && dayCounter <= daysInMonth; week++) {
                        html += "<tr>";
                        for (let day = 0; day < 7; day++) {
                            if ((week === 0 && day < startDay) || dayCounter > daysInMonth) {
                                html += "<td></td>";
                            } else {
                                const status = attendanceData[dayCounter] || "";
                                const isSunday = (day === 0);
                                const isDayOff = holidays.includes(dayCounter) || status === 'Off';

                                let tdStyle = 'background: #e8f1f8;'; // default
                                let isDisabled = '';
                                let ishidden = '';
                                if (status === 'P') {
                                    tdStyle = 'background: #d4edda;'; // light green
                                    isDisabled = 'disabled';
                                    ishidden = "";

                                } else if (status === 'Ab') {
                                    tdStyle = 'background: #ffe6e6;'; // light red (Absent)
                                    ishidden = "hidden";
                                } else if (status === 'dayoff') {
                                    tdStyle = 'background: #ffcccc;'; // strong red (Day Off)
                                    isDisabled = 'disabled';
                                    ishidden = "";
                                } else if (status === 'Missed Pun') {
                                    tdStyle = 'background: #ffe6e6;'; // light red (Absent)
                                    ishidden = "hidden";
                                } else {
                                    isDisabled = 'disabled';
                                }


                                html += `<td style="${tdStyle}">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="text-end text-black fw-bold" style="color:#000; font-weight:700;font-size: 15px;" >${dayCounter} </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <select class="form-select form-select-sm mb-1 attendance-select" data-day="${dayCounter}" ${isDisabled}>
                                                                                                                                                                                                                                                                    <option value="P" ${status === 'P' ? 'selected' : ''} ${ishidden}>P</option>
                                                                                                                                                                                                                                                                    <option value="ABSENT" ${status === 'Ab' ? 'selected' : ''}>Ab</option>
                                                                                                                                                                                                                                                                    <option value="PL" ${status === 'PL' ? 'selected' : ''}>PL</option>
                                                                                                                                                                                                                                                                    <option value="CL" ${status === 'CL' ? 'selected' : ''}>CL</option>
                                                                                                                                                                                                                                                                    <option value="FL" ${status === 'FL' ? 'selected' : ''}>FL</option>
                                                                                                                                                                                                                                                                    <option value="FLP" ${status === 'FLP' ? 'selected' : ''}>FLP</option>
                                                                                                                                                                                                                                                                    <option value="SPL" ${status === 'SPL' ? 'selected' : ''}>SPL</option>
                                                                                                                                                                                                                                                                    <option value="dayoff" ${status === 'dayoff' ? 'selected' : ''}>OFF</option>
                                                                                                                                                                                                                                                                     <option value="Missed Pun" ${status === 'Missed Pun' ? 'selected' : ''} ${ishidden}>MP</option>
                                                                                                                                                                                                                                                                </select>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <div class="attendance-message mt-1 text-danger fw-bold small"></div>   <button class="btn btn-sm btn-success d-none update-btn" data-day="${dayCounter}" style="font-size: 11px;">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        Update
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      </button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </td>`;
                                dayCounter++;
                            }
                        }
                        html += "</tr>";
                    }

                    html += "</tbody></table>";
                    $("#calendarContainer").html(html);
                    $('#loader').hide(); // Hide loader on success or error
                },
                error: function () {
                    message = "";
                    $('#calendarContainer').html(message);
                    Swal.fire({
                        title: '<h4 class="mb-2">🔍 Filed Fetch Attendance</h4>',
                        html: `


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="text-start small">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <p class="fw-bold mb-1">Please check the following before proceeding:</p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <ul class="mb-0 ps-3">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <li><strong>Check Employee PNo:</strong> Ensure it's correct and active.</li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <li><strong>Upload Attendance:</strong> Confirm attendance is uploaded for selected month/year.</li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <li><strong>Refresh Calendar:</strong> Click "Refresh Att" after selecting filters.</li>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `,
                        icon: 'info',
                        confirmButtonText: 'Got it!',
                        width: 600,
                        padding: '1em',
                        backdrop: true,
                        customClass: {
                            popup: 'text-start'
                        },

                    });

                }
            });
        }

        $(document).ready(function () {
            // Default calendar load for first user (or use a hidden value)
            const defaultUserId = $('#refreshBtn').data('emp_pno'); // or set from a hidden field
            //generateCalendar(parseInt($('#monthSelect').val()), parseInt($('#yearSelect').val()), defaultUserId);

            // Trigger on dropdown or refreshBtn or any `.attendance-btn`
            $('#monthSelect, #yearSelect').on('change', function () {
                const month = parseInt($('#monthSelect').val());
                const year = parseInt($('#yearSelect').val());
                const user_id = $('#refreshBtn').data('emp_pno'); // current selected user
                generateCalendar(month, year, user_id);
            });

            // Button click - pass emp_pno to calendar
            $('#attendance, #refreshBtn').on('click', function () {
                const month = parseInt($('#monthSelect').val());
                const year = parseInt($('#yearSelect').val());
                const user_id = $(this).data('emp_pno');
                const user_name = $(this).data('emp_name');
                // Save the clicked user on the #refreshBtn so dropdown change can reuse it
                $('#refreshBtn').data('emp_pno', user_id);
                $('#emp_name_set').html(user_name);


                generateCalendar(month, year, user_id);
            });
        });
        $(document).on('change', '.attendance-select', function () {
            const $parentTd = $(this).closest('td');
            const day = $(this).data('day');
            const month = parseInt($('#monthSelect').val());
            const year = parseInt($('#yearSelect').val());
            const empPno = $('#refreshBtn').data('emp_pno');
            const newStatus = $(this).val();
            var path = "{{ route('admin.updateAttendance_check') }}/" + day + "/" + newStatus + "/" + empPno + "/" + month + "/" + year;

            $.ajax({
                url: path,
                type: 'GET',
                success: function (res) {
                    // Clear previous message
                    $parentTd.find('.attendance-message').html(''); // clear message
                    // Show update button for this cell
                    $parentTd.find('.update-btn').removeClass('d-none');
                },
                error: function (xhr) {
                    // Optional: parse error message from backend
                    const msg = xhr.responseJSON?.message || "No leave pending.";
                    $parentTd.find('.attendance-message').html(msg);
                    $parentTd.find('.update-btn').addClass('d-none');
                }
            });

        });

        $(document).on('click', '.update-btn', function () {
            const day = $(this).data('day');
            const newStatus = $(this).siblings('select').val();
            const empPno = $('#refreshBtn').data('emp_pno');
            const month = parseInt($('#monthSelect').val());
            const year = parseInt($('#yearSelect').val());
            var path = "{{ route('admin.updateAttendance') }}/" + day + "/" + newStatus + "/" + empPno + "/" + month + "/" + year;
            // 🔄 Send update via AJAX (pseudo example)
            $.ajax({
                url: path,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    Swal.fire({
                        title: '✅ Updated!',
                        text: 'Attendance updated successfully.',
                        html: `Attendance updated for <b>${res.date}</b>.`,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#refreshBtn').trigger('click'); // 🔁 Trigger refresh
                        }
                    });

                },
                error: function () {
                    Swal.fire('❌ Error', 'Update failed.', 'error');
                }
            });

            // Optional: Hide the button again after update
            $(this).addClass('d-none');
        });


    </script>


    <!-- Exit Modal -->
    <!-- Exit Employee Modal -->
    <div class="modal fade" id="exitEmployeeModal" tabindex="-1" aria-labelledby="exitEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">



                <!-- Header -->
                <div class="modal-header bg-danger text-white border-0 rounded-top-4">
                    <h5 class="modal-title d-flex align-items-center" id="exitEmployeeModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                            class="bi bi-exclamation-triangle me-2" viewBox="0 0 16 16">
                            <path
                                d="M7.938 2.016a.13.13 0 0 1 .125 0l6.857 11.856c.027.047.04.102.04.156a.264.264 0 0 1-.264.264H1.304a.264.264 0 0 1-.264-.264.265.265 0 0 1 .04-.156L7.938 2.016zm.062-.984a1.13 1.13 0 0 0-.999.584L.144 13.472A1.13 1.13 0 0 0 1.13 15h13.74a1.13 1.13 0 0 0 .986-1.528L8.999 1.616a1.13 1.13 0 0 0-1-.584z" />
                            <path
                                d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm.93-4.481a.5.5 0 0 1 .992 0l-.35 3.5a.5.5 0 0 1-.992 0l-.35-3.5z" />
                        </svg>
                        Confirm Exit
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>

                <!-- Body -->
                <div class="modal-body text-center py-4">
                    <p class="fs-5 fw-semibold text-danger mb-2">Are you sure you want to exit this employee?</p>
                    <p class="text-muted mb-0">This action is irreversible. Please confirm your decision.</p>
                </div>
                <!-- Loader -->
                <div id="loaderOverlay"
                    style="
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    display: none;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    position: fixed;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    z-index: 9999;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    top: 50%;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    left: 50%;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    background: rgba(255,255,255,0.6);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 ">
                    <div class="spinner-border text-danger" role="status"></div>
                </div>

                <label class="label-red" for="message">Reason for Exit</label>
                <textarea id="message" class="textarea-red" placeholder="Write your reason here..."></textarea>
                <!-- Footer -->
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        No, Cancel the Process
                    </button>
                    <input type="hidden" id="exit_emp_id">
                    <button type="button" class="btn btn-danger px-4" id="exit_emp">
                        Yes, Exit the Employee
                    </button>

                </div>

            </div>
        </div>
    </div>
    <style>
        .modal.fade .modal-dialog {
            transform: scale(0.95);
            transition: transform 0.3s ease-out;
        }

        .modal.fade.show .modal-dialog {
            transform: scale(1);
        }
    </style>

    <style>
        .textarea-red {
            width: 70%;
            min-height: 100px;
            padding: 12px;
            border: 2px solid #d32f2f;
            /* Deep red border */
            background-color: #ffebee;
            /* Light red background */
            color: #b71c1c;
            /* Dark red text */
            border-radius: 8px;
            font-size: 16px;
            resize: vertical;
            transition: border-color 0.3s ease;
            display: block;
            margin: 0 auto;
            /* Centers the textarea horizontally */
        }


        .textarea-red:focus {
            outline: none;
            border-color: #b71c1c;
            /* Even deeper red on focus */
            box-shadow: 0 0 0 4px rgba(244, 67, 54, 0.2);
            /* subtle red glow */
        }

        .label-red {
            font-weight: bold;
            color: #b71c1c;
            margin-bottom: 6px;
            display: block;
            margin-left: 70px;
        }
    </style>
    <!-- change emp_id shift-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <!-- Update Employee Modal -->




    <!-- Update Employee Modal -->
    <div class="modal fade" id="updateEmployeeidModal" tabindex="-1" aria-labelledby="updateEmployeeidModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div style="border: none; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); overflow: hidden;"
                class="modal-content">

                <!-- Header -->
                <div style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border: none; padding: 1.5rem 2rem;"
                    class="modal-header">
                    <h5 style="font-weight: 600; font-size: 1.25rem;color:white"
                        class="modal-title d-flex align-items-center" id="updateEmployeeidModalLabel">
                        Update Shift <p id="emp_name"></p>
                    </h5>
                    <button type="button"
                        style="background: none; border: none; color: white; opacity: 0.8; font-size: 1.2rem;"
                        class="btn-close" data-bs-dismiss="modal" aria-label="Close" onmouseover="this.style.opacity='1'"
                        onmouseout="this.style.opacity='0.8'"></button>
                </div>

                <!-- Body -->
                <div style="padding: 2rem; background: #ffffff;" class="modal-body">
                    <form id="updateEmployeeidForm">
                        <input type="hidden" id="update_emp_id">
                        <!-- Employee ID Input -->
                        <div class="mb-4" style="display:none">
                            <label style="color: #374151; font-weight: 600; margin-bottom: 0.5rem;" for="emp_id"
                                class="form-label">
                                Employee ID
                            </label>
                            <div class="input-group">
                                <input type="text" readonly
                                    style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease; color: #000308;"
                                    class="form-control" id="emp_id" placeholder="Enter Employee ID" required
                                    onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 0.2rem rgba(99, 102, 241, 0.25)'"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'" name="empid">
                            </div>
                        </div>

                        <!-- Shift Assignment -->
                        <div class="mb-4">
                            <label style="color: #374151; font-weight: 600; margin-bottom: 0.5rem;" for="shift_name"
                                class="form-label">
                                Shift Assignment
                            </label>
                            <div class="input-group">
                                <select
                                    style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease; width: 100%; color: #000201;"
                                    class="form-select" id="shift_name" required
                                    onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 0.2rem rgba(99, 102, 241, 0.25)'"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'" name="shift">
                                    <option value="" selected disabled>Choose a shift...</option>
                                    <option value="A-SH">A-SH (06:00 - 14:00)</option>
                                    <option value="B-SH">B-SH (14:00 - 22:00)</option>
                                    <option value="C-SH">C-SH (22:00 - 06:00 +1)</option>
                                    <option value="G-S2">G-S2 (09:00 - 18:00)</option>
                                    <option value="G-S3">G-S3 (08:00 - 16:30)</option>
                                    <option value="G-S4_">G-S4_ (10:00 - 18:30)</option>
                                    <option value="G-S5">G-S5 (09:00 - 17:30)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Info Alert -->
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <div>
                                <strong>Note:</strong> Please update the correct shift for ongoing days.
                                If not updated properly, it may result in shift mismatch, marking the employee as
                                absent,
                                and salary deductions may occur.
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Simple Loader -->
                <!-- Simple Centered Loader -->
                <div id="loaderOverlay1"
                    style="position:fixed;top:50%;left:40%;width:100%;height:100%;display:flex;justify-content:center;align-items:center;z-index:9999;display:none;">
                    <div
                        style="width:60px;height:60px;border:6px solid #f3f3f3;border-top:6px solid #3498db;border-radius:50%;animation:spin 1s linear infinite;">
                    </div>
                </div>

                <!-- Add this in <style> or <head> -->
                <style>
                    @keyframes spin {
                        0% {
                            transform: rotate(0deg);
                        }

                        100% {
                            transform: rotate(360deg);
                        }
                    }
                </style>


                <!-- Show/Hide JS -->
                <script>
                    function showLoader() {
                        document.getElementById('loaderOverlay1').style.display = 'flex';
                    }
                    function hideLoader() {
                        document.getElementById('loaderOverlay1').style.display = 'none';
                    }
                </script>

                <!-- Footer -->
                <div style="padding: 1.5rem 2rem; background: #f9fafb; border: none;"
                    class="modal-footer justify-content-between">
                    <button type="button"
                        style="border-radius: 12px; padding: 0.75rem 2rem; font-weight: 600; transition: all 0.3s ease; border: 2px solid #e5e7eb; color: #6b7280; background: white;"
                        class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        onmouseover="this.style.background='#f3f4f6'; this.style.borderColor='#d1d5db'; this.style.color='#374151'; this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.background='white'; this.style.borderColor='#e5e7eb'; this.style.color='#6b7280'; this.style.transform=''">
                        Cancel
                    </button>
                    <button type="button"
                        style="border-radius: 12px; padding: 0.75rem 2rem; font-weight: 600; transition: all 0.3s ease; border: none; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white;"
                        class="btn btn-primary" id="updateEmployeeBtn"
                        onmouseover="this.style.background='linear-gradient(135deg, #5856eb 0%, #7c3aed 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(99, 102, 241, 0.3)'"
                        onmouseout="this.style.background='linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)'; this.style.transform=''; this.style.boxShadow=''">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loader -->







@endsection
@section('scripts')


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">


        $("#exit_emp").on("click", function () {
            var id = $('#exit_emp_id').val();
            var message_remarks = $('#message').val();
            var path = "{{ route('admin.exit_emp') }}/" + id + '/' + message_remarks
            $("#loaderOverlay").show();
            $.ajax({
                url: path,
                type: "GET",
                dataType: "json",

                success: function (data) {
                    $("#loaderOverlay").hide();
                    Swal.fire({
                        icon: data.success ? 'success' : 'error',
                        title: data.success ? 'Success' : 'Error',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: true,  // show OK button
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                            // Reload page or redirect here
                            location.reload();
                            // or: window.location.href = '/some-url';
                        }
                    });
                },
                error: function () {
                    alert("Failed to fetch data.");
                }
            });
        });

        $(document).on("click", ".exit-emp-btn", function () {
            var id = $(this).data('id');

            // Show loader
            $("#loaderOverlay").show();

            // Simulate slight delay to show loader effect (optional)
            setTimeout(function () {
                // Set the ID in modal inputs
                $('#exit_emp_id').val(id);


                // Hide loader after ID is set
                $("#loaderOverlay").hide();
            }, 300); // Adjust delay as needed
        });

    </script>
    <script type="text/javascript">
        $("#updateEmployeeBtn").on("click", function () {
            var ids = $('#update_emp_id').val().split(','); // split to array
            var empid = $('#emp_id').val();
            var shift = $('#shift_name').val();

            $("#loaderOverlay1").show();

            $.ajax({
                url: "{{ route('admin.update_empid_bulk') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: ids,
                    empid: empid,
                    shift: shift
                },
                dataType: "json",
                success: function (data) {
                    $("#loaderOverlay1").hide();
                    Swal.fire({
                        icon: data.success ? 'success' : 'error',
                        title: data.success ? 'Success' : 'Error',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: true,
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }
                    });
                },
                error: function () {
                    $("#loaderOverlay1").hide();
                    alert("Failed to update data.");
                }
            });
        });

        $(document).on("click", ".update_empid-btn", function () {
            var name = $(this).data('name');
            var id = $(this).data('id');
            var empid = $(this).data('emp_id');
            var shift = $(this).data('shift');
            // Show loader

            $("#loaderOverlay1").show();

            // Simulate slight delay to show loader effect (optional)
            setTimeout(function () {

                // Set the ID in modal inputs
                $('#update_emp_id').val(id);
                $('#emp_id').val(empid);
                $('#shift_name').val(shift);


                // Hide loader after ID is set
                $("#loaderOverlay1").hide();
            }, 300); // Adjust delay as needed
        });

    </script>

    <script>
        $(document).ready(function () {
            $('#my-permit').DataTable();
        });
        $(document).ready(function () {
            $('#my-permit1').DataTable();
        });
        $(document).ready(function () {
            $('#my-permit2').DataTable();
        });
        $(document).ready(function () {
            $('#my-permit3').DataTable();
        });
    </script>
    <script>
        let selectedIds = [];

        $(document).ready(function () {
            let table = $('#my-permit3').DataTable(); // initialize or get reference

            // Select/Deselect All Checkboxes Across Pages
            $('#select-all').on('change', function () {
                const isChecked = $(this).is(':checked');

                // Loop through all rows using DataTables API
                table.rows().every(function () {
                    const row = this.node();
                    const checkbox = $(row).find('.row-checkbox');
                    const id = checkbox.val();

                    checkbox.prop('checked', isChecked);

                    if (isChecked) {
                        if (!selectedIds.includes(id)) {
                            selectedIds.push(id);
                        }
                    } else {
                        selectedIds = selectedIds.filter(item => item !== id);
                    }
                });

                toggleBulkButton();
            });

            // Individual Checkbox Selection
            $(document).on('change', '.row-checkbox', function () {
                const id = $(this).val();
                if ($(this).is(':checked')) {
                    if (!selectedIds.includes(id)) {
                        selectedIds.push(id);
                    }
                } else {
                    selectedIds = selectedIds.filter(item => item !== id);
                    $('#select-all').prop('checked', false); // uncheck master if one is unchecked
                }

                toggleBulkButton();
            });

            // Show/hide the bulk update button
            function toggleBulkButton() {
                if (selectedIds.length > 0) {
                    $('#bulkUpdateBtn').show();
                } else {
                    $('#bulkUpdateBtn').hide();
                }
            }

            // On modal open, set selected IDs
            $('#bulkUpdateBtn').on('click', function () {
                $('#update_emp_id').val(selectedIds.join(','));
            });
        });

    </script>



@endsection