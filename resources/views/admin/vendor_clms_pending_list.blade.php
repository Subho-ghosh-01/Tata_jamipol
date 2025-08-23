<?php
use App\Division;
?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Users</a></li>
@endsection

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">List of Pending Vendor Registration </h1>

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
    <!-- Tab Details -->




    <div class="tab-content">



        <div class="tab-pane fade show active" id="Ven_approval" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive">

                <table class="table table-striped table-sm" id="vendorall2">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Division</th>
                            <th>Name</th>
                            <th>Employee P.No./Vendor Name</th>
                            <th>Role</th>
                            <th>Sub Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($vendors_approvals->count() > 0)
                            <?php    $count = 1;?>
                            @foreach($vendors_approvals as $vendor_approval)
                                <tr>
                                    <td>{{$count++}}</td>
                                    <?php        $divii = Division::where('id', $vendor_approval->division_id)->first(); ?>
                                    <td>{{@$divii->name}}</td>
                                    <td>{{$vendor_approval->name}}</td>
                                    <td>{{$vendor_approval->vendor_code}}</td>
                                    <td>@if($vendor_approval->user_type == 1) {{'Employee'}} @else {{'Vendor'}} @endif</td>
                                    <td>@if($vendor_approval->user_sub_type == 1) {{'Admin'}}
                                    @elseif($vendor_approval->user_sub_type == 2) {{'User'}} @else {{'Super Admin'}} @endif</td>
                                    <td>{{$vendor_approval->status}}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                            href="{{route('admin.edit_clms1.edit', \Crypt::encrypt($vendor_approval->id)) }}"
                                            title="Edit">Details</a>
                                        @if(Session::get('vcode') == $vendor_approval->vendor_code)
                                            <a class="btn btn-info btn-sm" href="{{route('admin.vendor_details') }}"
                                                title="Edit">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="" style="color:red;text-align:center;">No Vendors Found!!!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function deleteRecord(id) {
            // alert(id)
            let choice = confirm("Are you sure want to delete the record Pamanently?");
            if (choice) {
                document.getElementById('delete-' + id).submit();
            }
        }
        $(document).ready(function () {
            $('#emplist').DataTable();
        });
        $(document).ready(function () {
            $('#vendorall').DataTable();
        });
        $(document).ready(function () {
            $('#vendorall2').DataTable();
        });
        // function getDepartment(th,divisionID) {
        //     if(divisionID!="")
        //     {
        //         $("#department_id").html('<option value="">--Select--</option>');
        //         if(divisionID)
        //         {
        //             $.ajaxSetup({
        //                 headers:{
        //                     'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        //                 }
        //             });
        //             $.ajax({
        //                 type:'GET',
        //                 url:"{{route('admin.job.department')}}/" + divisionID,
        //                 contentType:'application/json',
        //                 dataType:"json",
        //                 success:function(data){
        //                     for(var i=0;i<data.length;i++){
        //                         $('#department_id').append('<option value="'+data[i].id+'" >'+data[i].department_name+'</option>');
        //                     }
        //                 }
        //             });
        //         }else{
        //             $('#department_id').html('<option value="">Select Department</option>');
        //         }     
        //     }
        // }

        $('#division_id').on('change', function () {
            var division_ID = $(this).val();
            if (division_ID != "") {
                $("#department_id").html('<option value="">--Select--</option>');
                if (division_ID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.job.department')}}/" + division_ID,
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
        });


        function check() {
            var flag = true;
            $(".rec").each(function (e) {
                if ($(this).val() == "") {
                    $(this).addClass("verror");
                    flag = false;
                }
                else {
                    $(this).removeClass("verror");
                }
            })
            if (flag == true) {

            }
            else {
                return false;
            }
        }

        function check1() {
            var flag = true;
            $(".rec1").each(function (e) {
                if ($(this).val() == "") {
                    $(this).addClass("verror");
                    flag = false;
                }
                else {
                    $(this).removeClass("verror");
                }
            })
            if (flag == true) {

            }
            else {
                return false;
            }
        }
    </script>
@endsection