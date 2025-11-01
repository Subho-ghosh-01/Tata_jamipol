<?php 
use App\Division;
?>


@extends('admin.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">View Bonus Calculation</li>
    <!-- DataTables CSS -->



@endsection
@if(!$attendanceTaken)
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('#attendanceNoteModal').modal('hide');
        });
    </script>

    <div class="modal fade" id="attendanceNoteModal" tabindex="-1" role="dialog" aria-labelledby="attendanceNoteLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content animate__animated animate__fadeInDown">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="attendanceNoteLabel">
                        <i class="fas fa-exclamation-triangle"></i> Alert
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center font-weight-bold">
                    Attendance for {{ \Carbon\Carbon::parse($today)->format('d-m-Y') }} has not been Uploaded.
                </div>
            </div>
        </div>
    </div>
@endif


@section('content')
    <div class="container py-5">

        <div class="row justify-content-right">

            <h1 class="h2">Bonus View
                <small style="font-size: 0.7em;">(Date Between
                    <span style="color: #3cb1e7;">{{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }}</span>
                    And
                    <span style="color: #3cb1e7;">{{ \Carbon\Carbon::parse($toDate)->format('d-m-Y') }}</span>)
                </small>
            </h1>





            <form class="form-inline" autocomplete="off" action="{{ route('admin.bonus_calculation') }}" method="POST">
                @csrf

                <div class="form-group mx-sm-2 mb-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" name="fromdate" class="form-control" placeholder="From Date" id="start_date"
                            required>
                    </div>
                </div>

                <div class="form-group mb-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" name="todate" class="form-control" placeholder="To Date" id="end_date" required>
                    </div>
                </div>




                <div class="form-group mx-sm-2 mb-2">
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Find Report
                    </button>
                </div>
                <div class="form-group mx-sm-2 mb-2">
                    <a href="{{ route('admin.bonus_calculation') }}" class="btn btn-info d-block text-start"
                        style="max-width: fit-content;">
                        <i class="fas fa-sync"></i> Refresh
                    </a>
                </div>

                
            </form>



            <div class="col-lg-12">




                <table id="attendanceTable" class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Division Name</th>
                            <th>PNo</th>
                            <th>Name</th>
                           
                            <th>Bonus</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php 
                        $extra_hours = 0;
                        @endphp
                        @forelse($attendanceLogs as $row)
                            @php

                                $pnouser = DB::table('Clms_gatepass')->select('skill_rate')->where('emp_pno', $row->PNo)->first();
                                $days = $pnoStats[$row->PNo]->total_days ?? 0;
                                $extra_hours = $pnoStats[$row->PNo]->total_extra_hours ?? 0;

                                $skill_rate = $pnouser->skill_rate ?? 0;
                                $basic_plus_da = $skill_rate * $days ?? 0;
                                $pf = $basic_plus_da * 0.12;
                                $esic = $basic_plus_da * 0.0075;
                                $net = $basic_plus_da - ($pf + $esic);
                                $Bonus = $net * 8.33 / 100;
                                $division_name = Division::Where('id', $row->division_id)->first();
                               // $extra_hours += $row->extra_hours;
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$division_name->name}}</td>
                                <td>{{ $row->PNo }}</td>
                                <td>{{ $row->Name }}</td>
                               
                                <td>₹{{ number_format($Bonus, 2) }} </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="text-center no-records-row">
                                    <div class="no-data-container">
                                        <svg width="64" height="64" fill="#dc3545" viewBox="0 0 16 16">
                                            <path
                                                d="M8 0a8 8 0 1 0 8 8A8.01 8.01 0 0 0 8 0zM8 14.5A6.5 6.5 0 1 1 14.5 8 6.507 6.507 0 0 1 8 14.5z" />
                                            <path d="M7.002 5h2v5h-2zm0 6h2v2h-2z" />
                                        </svg>
                                        <div class="mt-2">No bonus records found.</div>
                                    </div>
                                </td>
                            </tr>

                            <style>
                                .no-data-container,
                                {
                                animation: fadeIn 1s ease-in-out;
                                color: #dc3545;
                                font-weight: 500;
                                font-size: 1.1rem;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                padding: 1rem;
                                }

                                @keyframes fadeIn {
                                    from {
                                        opacity: 0;
                                        transform: scale(0.95);
                                    }

                                    to {
                                        opacity: 1;
                                        transform: scale(1);
                                    }
                                }
                            </style>



                        @endforelse
                    </tbody>
                </table>





@endsection


            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />


            @section('scripts')

                <script>
                    $(document).ready(function () {
                        $('#attendanceTable').DataTable({
                            responsive: true,
                            pageLength: 10,
                            lengthMenu: [5, 10, 25, 50, 100],
                            language: {
                                searchPlaceholder: "Search records...",
                                search: "",
                            },
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend: 'excelHtml5',
                                    title: 'Attendance Report',
                                    text: '<i class="fas fa-file-excel"></i> Export Excel',
                                    className: 'btn btn-success'
                                }
                            ],
                        });
                    });

                </script>
                <script
                    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
                    integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
                    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <link rel="stylesheet"
                    href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
                    integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw=="
                    crossorigin="anonymous" referrerpolicy="no-referrer" />
                <script>

                </script>
                <script>
                    $(document).ready(function () {
                        $('#start_date').datetimepicker({
                            format: 'Y/m/d'
                        });
                        $('#end_date').datetimepicker({
                            format: 'Y/m/d'
                        });
                    });
                </script>
            @endsection