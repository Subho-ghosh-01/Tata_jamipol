<?php
use App\Division;
use App\UserLogin;
?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Vendor Half Yearly Return</a></li>
@endsection
@if(Session::get('user_sub_typeSession') == 5)
    return redirect('admin/dashboard');
@else
    @section('content')
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Vendor Half Yearly Return</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.vendor_hyr.create') }}"
                    class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center px-3 shadow-sm upload-btn"
                    id="uploadBtn">
                    <i class="fas fa-upload me-2 upload-icon" id="uploadIcon"></i>
                    <i class="fas fa-spinner fa-spin me-2 d-none" id="spinnerIcon"></i>&nbsp;
                    <span id="uploadText"> Upload Document's</span>
                </a>
                <style>
                    .upload-btn i {
                        transition: all 0.3s ease;
                    }
                </style>


                <style>
                    .upload-btn:hover .upload-icon {
                        animation: bounceUpload 0.6s;
                    }

                    @keyframes bounceUpload {
                        0% {
                            transform: translateY(0);
                        }

                        30% {
                            transform: translateY(-5px);
                        }

                        60% {
                            transform: translateY(2px);
                        }

                        100% {
                            transform: translateY(0);
                        }
                    }
                </style>


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

        <div class="table-responsive ">
            <table class="table table-striped table-sm" id="listall">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Vendor Name</th>
                        <th>Wage Month</th>
                        <th>From 24</th>
                        <th>Status</th>
                        <th>Uploaded Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($hyr_lists->count() > 0)
                        <?php        $count = 1; ?>
                        @foreach($hyr_lists as $hyr_list)

                            @php
                                $vendorname = UserLogin::where('id', $hyr_list->vendor_id)->first();

                                // Determine the status based on the value in 'status'
                                if ($hyr_list->status == 'Pending_with_hr') {
                                    $status = '<i class="fas fa-user-clock text-warning me-1"></i> Pending With HR Team';
                                } elseif ($hyr_list->status == 'completed') {
                                    $status = '<i class="fas fa-check-circle text-success me-1"></i> Completed';
                                } elseif ($hyr_list->status == 'reject') {
                                    $status = '<i class="fas fa-times-circle text-danger me-1"></i> Rejected';
                                } else {
                                    $status = '<i class="fas fa-exclamation-circle text-danger me-1"></i> Something Went Wrong';
                                }

                            @endphp

                            <tr style="white-space: nowrap;">
                                <td>{{$count++}}</td>
                                <td>{{$vendorname->name}}</td>
                                <td>{{date('F-Y ', strtotime($hyr_list->month))}}</td>
                                <td><a href="../{{$hyr_list->doc1}}" target="_blank" class="btn btn-success btn-sm">View /
                                        Download</a>
                                </td>
                                <td>{!! $status !!}</td>
                                <td>{{date('d-m-Y H:i:s', strtotime(@$hyr_list->created_date))}} </td>
                                <td>
                                    <a href="{{ route('admin.vendor_hyr.edit', \Crypt::encrypt($hyr_list->id)) }}"
                                        class="btn btn-sm btn-outline-primary d-inline-flex align-items-center" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="View Full Details">
                                        <i class="fas fa-eye me-1"></i> &nbsp;Details
                                    </a>

                                </td>
                            </tr>
                        @endforeach


                    @else
                        <tr>
                            <td colspan="8" style="text-align:center; padding: 20px;">
                                <div
                                    style="display: flex; flex-direction: column; align-items: center; justify-content: center; color: #dc3545;">
                                    <!-- SVG Icon (a simple warning or search icon) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 0 24 24" width="48"
                                        fill="#dc3545">
                                        <path d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             6.5 6.5 0 109.5 16c1.61 0 3.09-.59 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             4.23-1.57l.27.28v.79l5 4.99L20.49 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             19l-4.99-5zm-6 0C8.01 14 6 11.99 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             6 9.5S8.01 5 10.5 5 15 7.01 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             15 9.5 12.99 14 10.5 14z" />
                                    </svg>
                                    <!-- Message -->
                                    <span style="margin-top: 10px; font-size: 16px;">No Data Found</span>
                                </div>
                            </td>
                        </tr>

                    @endif
                </tbody>
            </table>
        </div>
        <div class="row p-2">
            <div class="col-sm-12">
                {{-- $departments->links() --}}
            </div>
        </div>

    @endsection
    @section('scripts')
        <script>var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        </script>
        <script>
            $(document).ready(function () {
                $('#listall').DataTable({
                    // Optional configuration here
                    // Search is enabled by default
                    "paging": true,
                    "ordering": true,
                    "info": true
                });
            });
        </script>
    @endsection

@endif