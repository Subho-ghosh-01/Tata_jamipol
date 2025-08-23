<?php
use App\Division;
?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Users</a></li>
@endsection
@if(Session::get('user_sub_typeSession') == 2)
    return redirect('admin/dashboard');
@else
    @section('content')
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Users</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{route('admin.user.create')}}" class="btn btn-sm btn-outline-secondary">Add Users </a>
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
        <!-- Tab Details -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#Emp" role="tab" aria-controls="home"
                    aria-selected="true">Employee</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#Ven" role="tab" aria-controls="profile"
                    aria-selected="false">Vendor</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{url('admin/vendor_clms_pending_list')}}" role="tab" aria-controls="profile"
                    aria-selected="false">Vendor-Pending For Approval</a>
            </li>
        </ul>



        <div class="tab-content">
            <div class="tab-pane fade show active" id="Emp" role="tabpanel" aria-labelledby="home-tab">
                <div class="table-responsive">
                    @if(Session::get('user_sub_typeSession') == 3)
                        <form class="form-inline" autocomplete=off action="{{route('admin.getuserlist')}}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <select class="form-control rec" id="division_id" name="division_id"
                                    onchange="getDepartment(this,this.value)">
                                    <option value="">Select Division</option>
                                    @if($divisions->count() > 0)
                                        @foreach(@$divisions as $division)
                                            <option value="{{@$division->id}}">{{@$division->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group mx-sm-3 mb-3">
                                <select class="form-control rec" id="department_id" name="department_id">
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                            <input type="hidden" name="type" value="Employee">
                            <div class="form-group mx-sm-2 mb-3">
                                <input type="submit" name="submit" class="btn btn-primary" value="Find Employee"
                                    onclick="return check();">
                            </div>
                        </form>
                    @endif
                    <table class="table table-striped table-sm" id="emplist">
                        <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th>Name</th>
                                <th>Employee P.No./Vendor Name</th>
                                <th>Role</th>
                                <th>Sub Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($users->count() > 0)
                                <?php        $count = 1;?>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->vendor_code}}</td>
                                        <td>@if($user->user_type == 1) {{'Employee'}} @else {{'Vendor'}} @endif</td>
                                        <td>@if($user->user_sub_type == 1) {{'Admin'}} @elseif($user->user_sub_type == 2)
                                        {{'User'}}@elseif($user->user_sub_type == 4) {{'Security'}} @else {{'Super Admin'}} @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm"
                                                href="{{route('admin.user.edit', \Crypt::encrypt($user->id)) }}" title="Edit">Edit</a>
                                            <a class="btn btn-danger btn-sm" onclick="deleteRecord('{{$user->id}}')">Delete</a>
                                            <form id="delete-{{$user->id}}" action="{{ route('admin.user.destroy', $user->id) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <a class="btn btn-info btn-sm" href="{{route('admin.user.resetPassword', $user->id)}}"
                                                onclick="return confirm('Are you sure to reset the password?')"
                                                title="Reset">ResetPassword</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="" style="color:red;text-align:center;">No Employee Found!!!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="Ven" role="tabpanel" aria-labelledby="profile-tab">
                <div class="table-responsive">
                    @if(Session::get('user_sub_typeSession') == 3)
                        <form class="form-inline" autocomplete=off action="{{route('admin.getuserlist')}}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <select class="form-control rec1" id="division_id1" name="division_id">
                                    <option value="">Select Division</option>
                                    @if($divisions->count() > 0)
                                        @foreach($divisions as $division)
                                            <option value="{{@$division->id}}">{{@$division->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <input type="hidden" name="type" value="Vendor">
                            <div class="form-group mx-sm-2 mb-3">
                                <input type="submit" name="submit" class="btn btn-primary" value="Find Vendor"
                                    onclick="return check1();">
                            </div>
                        </form>
                    @endif
                    <table class="table table-striped table-sm" id="vendorall">
                        <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th>Division</th>
                                <th>Name</th>
                                <th>Employee P.No./Vendor Name</th>
                                <th>Role</th>
                                <th>Sub Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($vendors->count() > 0)
                                <?php        $count = 1;?>
                                @foreach($vendors as $vendor)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <?php            $divi = Division::where('id', $vendor->division_id)->first(); ?>
                                        <td>{{@$divi->name}}</td>
                                        <td>{{$vendor->name}}</td>
                                        <td>{{$vendor->vendor_code}}</td>
                                        <td>@if($vendor->user_type == 1) {{'Employee'}} @else {{'Vendor'}} @endif</td>
                                        <td>@if($vendor->user_sub_type == 1) {{'Admin'}} @elseif($vendor->user_sub_type == 2) {{'User'}}
                                        @else {{'Super Admin'}} @endif</td>
                                        <td>
                                            <!-- Admin list of vendor -->
                                            @if(Session::get('user_sub_typeSession') == 3 || Session::get('user_sub_typeSession') == 1)
                                                <a class="btn btn-info btn-sm"
                                                    href="{{route('admin.user.edit', \Crypt::encrypt($vendor->id)) }}" title="Edit">Edit</a>
                                                <a class="btn btn-danger btn-sm" onclick="deleteRecord('{{$vendor->id}}')">Delete</a>
                                                <form id="delete-{{$vendor->id}}" action="{{ route('admin.user.destroy', $vendor->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <a class="btn btn-info btn-sm" href="{{route('admin.user.resetPassword', $vendor->id)}}"
                                                    onclick="return confirm('Are you sure to reset the password?')"
                                                    title="Reset">ResetPassword</a>
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
            <div class="tab-pane fade" id="Ven_approval" role="tabpanel" aria-labelledby="profile-tab">
                <div class="table-responsive">
                    @if(Session::get('user_sub_typeSession') == 3)
                        <form class="form-inline" autocomplete=off action="{{route('admin.getuserlist')}}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <select class="form-control rec1" id="division_id1" name="division_id">
                                    <option value="">Select Division</option>
                                    @if($divisions->count() > 0)
                                        @foreach($divisions as $division)
                                            <option value="{{@$division->id}}">{{@$division->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <input type="hidden" name="type" value="Vendor">
                            <div class="form-group mx-sm-2 mb-3">
                                <input type="submit" name="submit" class="btn btn-primary" value="Find Vendor"
                                    onclick="return check1();">
                            </div>
                        </form>
                    @endif
                    <table class="table table-striped table-sm" id="vendorall2">
                        <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th>Division</th>
                                <th>Name</th>
                                <th>Employee P.No./Vendor Name</th>
                                <th>Role</th>
                                <th>Sub Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($vendors->count() > 0)
                                <?php        $count = 1;?>
                                @foreach($vendors as $vendor_approval)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <?php            $divii = Division::where('id', $vendor_approval->division_id)->first(); ?>
                                        <td>{{@$divii->name}}</td>
                                        <td>{{$vendor_approval->name}}</td>
                                        <td>{{$vendor_approval->vendor_code}}</td>
                                        <td>@if($vendor_approval->user_type == 1) {{'Employee'}} @else {{'Vendor'}} @endif</td>
                                        <td>@if($vendor_approval->user_sub_type == 1) {{'Admin'}}
                                        @elseif($vendor_approval->user_sub_type == 2) {{'User'}} @else {{'Super Admin'}} @endif</td>
                                        <td>
                                            <!-- Admin list of vendor -->
                                            @if(Session::get('user_sub_typeSession') == 3 || Session::get('user_sub_typeSession') == 1)
                                                <a class="btn btn-info btn-sm"
                                                    href="{{route('admin.edit_clms.edit', \Crypt::encrypt($vendor_approval->id)) }}"
                                                    title="Edit">Edit</a>
                                                <a class="btn btn-danger btn-sm"
                                                    onclick="deleteRecord('{{$vendor_approval->id}}')">Delete</a>
                                                <form id="delete-{{$vendor->id}}"
                                                    action="{{ route('admin.user.destroy', $vendor_approval->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <a class="btn btn-info btn-sm"
                                                    href="{{route('admin.user.resetPassword', $vendor_approval->id)}}"
                                                    onclick="return confirm('Are you sure to reset the password?')"
                                                    title="Reset">ResetPassword</a>
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
@endif
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