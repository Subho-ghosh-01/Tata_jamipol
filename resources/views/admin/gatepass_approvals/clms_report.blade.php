@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Reports</a></li>
@endsection

@if(Session::get('user_sub_typeSession') == 0)
    return redirect('admin/dashboard');
@else
    @section('content')
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">CLMS Reports</h1>
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

        <form class="form-inline" autocomplete=off action="{{route('admin.clms_report')}}" method="POST">

            @csrf
            <div class="form-group mb-2">
                <select class="form-control" name="divi_id" onchange="getDepartment(this,this.value)">
                    <option value="">ALL Division</option>
                    @if($divisions->count() > 0)
                        @foreach($divisions as $division)
                            <option value="{{$division->id}}">{{$division->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group mb-2" style="display: none;">
                <select class="form-control" name="dept_id" id="department_id">
                    <option value="">ALL Department</option>
                </select>
            </div>
            <div class="form-group mb-2" hidden>
                <select class="form-control" name="vendor_id" id="vendor_id">
                    <option value="">ALL Vendor</option>
                    @if($vendors->count() > 0)
                        @foreach($vendors as $vendor)
                            <option value="{{$vendor->id}}" @if($vendor->id == Session::get('user_idSession')) {{ 'selected' }}@endif>
                                {{$vendor->name}}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group mx-sm-2 mb-2">
                <input type="text" name="fromdate" class="form-control" placeholder="From Date" id="start_date" required>
            </div>
            <div class="form-group mb-2">
                <input type="text" name="todate" class="form-control" placeholder="To Date" id="end_date" required>
            </div>
            <div class="form-group mx-sm-2 mb-2">
                <input type="submit" name="submit" class="btn btn-primary" value="Find Report">
            </div>
        </form>

        <div class="table-responsive">
            @if($report)
                <?php        $from = $_REQUEST['fromdate'];
                    $to = $_REQUEST['todate'];
                    $div = $_REQUEST['divi_id'];
                    $v_id = $_REQUEST['vendor_id'];
                                                                                                                                                                                                                                                                            ?>
                <a href="https://wps.jamipol.com/print_report.php?&from=<?= $from ?>&to=<?= $to ?>&division=<?= $div ?>&vendor_id=<?=$v_id ?>"
                    target="_blank"><input type="button" value="Export To Excel" class="btn btn-outline-primary"></a>

            @endif
            <table id="example" class="display table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sl No</th>
                        <th>Vendor Name</th>
                        <th>Name</th>
                        <th>Work Order No</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($report)
                        @php $count = 1 @endphp
                        @foreach($report as $reports)
                            @php
                                @$approver = DB::table('userlogins')->where('id', $reports->created_by)->first();
                            @endphp
                            @php
                                @$work = DB::table('work_order')->where('id', $reports->work_order_no)->first();
                            @endphp

                            <tr>
                                <td>{{$count++}}</td>
                                <td>{{$reports->full_sl}}</td>
                                <td>{{@$approver->name}}</td>
                                <td>{{$reports->name}}</td>
                                <td>{{@$reports->work_order_no}}</td>
                                <td>@if($reports->status == "Pending_for_shift_incharge")
                                    {{"Pending To Shift Incharge"}}
                                @elseif($reports->status == "Pending_for_hr")
                                        {{"Pending To HR"}}
                                    @elseif($reports->status == "Pending_for_safety")
                                        {{"Pending To Safety"}}
                                    @elseif($reports->status == "Pending_for_plant_head")
                                        {{"Pending To Plant Head"}}
                                    @elseif($reports->status == "Pending_for_security")
                                        {{"Gatepass Approved"}}
                                    @elseif($reports->status == "Rejected")
                                        {{"Rejected"}}
                                    @endif
                                </td>


                                <td><a class="btn btn-info btn-sm"
                                        href="{{route('admin.edit_clms.edit', \Crypt::encrypt($reports->id))}}" title="Edit">Details</a>

                                </td>
                            </tr>

                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    @endsection
@endif

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
        integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
        integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        $('#start_date').datetimepicker({
            format: 'Y/m/d'
        });
        $('#end_date').datetimepicker({
            format: 'Y/m/d'
        });
        $(document).ready(function () {
            $('#example').DataTable();
        });
        function getDepartment(th, divisionID) {
            if (divisionID != "") {
                $("#department_id").html('<option value="ALL">ALL</option>');
                if (divisionID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.job.department')}}/" + divisionID,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            for (var i = 0; i < data.length; i++) {
                                $('#department_id').append('<option value="' + data[i].id + '" >' + data[i].department_name + '</option>');
                            }
                        }
                    });
                } else {
                    $('#department_id').html('<option value="">Select Department</option>');
                }
            }
        }
    </script>
@endsection