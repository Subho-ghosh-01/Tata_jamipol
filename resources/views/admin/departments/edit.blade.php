@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.department.index')}}">List of Department</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Department</li>
@endsection
@if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1)
    return redirect('admin/dashboard');
@else
@section('content')
<form action="{{route('admin.department.update',$department->id)}}" method="post"  autocomplete="off">
    @csrf
    @method('PUT')
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
    <div class="form-group-row">
        <div class="col-sm-12" style="text-align:center;">
            @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message')}}
            </div>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Department Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="department_name" id="" value="{{$department->department_name}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Divisions</label>
        <div class="col-sm-10">
            <select class="form-control" id="" name="division_id">
            @if($divisions->count() > 0)
                @foreach($divisions as $division[0])
                    <option value="{{$division[0]->id}}" @if($division[0]->id == $departments[0]->division_id) {{ "Selected"}} @endif >{{$division[0]->name}}</option>
                @endforeach
            @endif
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary" value="Update Section">
        </div>
    </div>
</form>
@endsection
@endif