<?php

use App\AreaClearence;
use App\Division;
use App\UserLogin;
use App\Department;
use App\Job;


?>


@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Area Clearance</a></li>
@endsection
@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">List of Area Clearance</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{route('admin.area_cls.create')}}" class="btn btn-sm btn-outline-secondary">Add Area Clearance</a>
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
    <div class="table-responsive">
        <table class="table table-striped table-sm" id="ListAll">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Division</th>
                    <th>Department</th>
                    <th>Section</th>
                    <th>Job Category</th>
                    <th>Employee Details</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($area_clearences->count() > 0)
                    <?php $count =1 ?>
                    @foreach($area_clearences as $area_clearence)
                    <?php 
                        $division_name   = Division::where('id',$area_clearence->division_id)->get();
                        $department_name = Department::where('id',$area_clearence->department_id)->get();
                        $section_name = Division::where('id',$area_clearence->section_id)->get();
                        $jobcat_name = Job::where('id',$area_clearence->job_id)->get();
                        $user_name = UserLogin::where('id',$area_clearence->user_id)->get();
                        // echo  $division_name[0]->name ;
                        // echo  $area_clearence->division_id ;
                    ?>
                        <tr>
                            <th>{{$count++}}</th>
                            <th>{{@$division_name[0]->name}}</th>
                            <th>{{@$department_name[0]->department_name}}</th>
                            <th>{{@$section_name[0]->name}}</th>
                            <th>{{@$jobcat_name[0]->job_title}}</th>
                            <th>{{@$user_name[0]->name}}</th>
                            <th>
                                <a class="btn btn-info btn-sm" href="{{route('admin.area_cls.edit',\Crypt::encrypt($area_clearence->id)) }}">Edit</a> |
                                <a class="btn btn-danger btn-sm" onclick="deleteRecord('{{$area_clearence->id}}')">Delete</a>
                                    <form id="delete-{{$area_clearence->id}}" action="{{ route('admin.area_cls.destroy',$area_clearence->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                            </th>
                        </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="7" class="" style="color:red;text-align:center;">NA..</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-12">
            {{-- $jobs->links() --}}
        </div>
    </div>
@endsection
@section('scripts')
<script>
    function deleteRecord(id){
        // alert(id)
        let  choice = confirm("Are you sure want to delete the record permanently?");
        if(choice){
            document.getElementById('delete-'+id).submit();
        }
    }
    $(document).ready(function() {
        $('#ListAll').DataTable();
    });

</script>
@endsection