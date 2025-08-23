@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.skill.index')}}">List of Skill</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Skill</li>
@endsection
@if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1)
    return redirect('admin/dashboard');
@else
    @section('content')
        <form action="{{route('admin.skill.update', $skill->id)}}" method="post" autocomplete="off">
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
                <label for="form-control-label" class="col-sm-2 col-form-label">Skill Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="skill_name" id="" value="{{$skill->skill_name}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Skill Rate</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="skill_rate" id="" value="{{$skill->skill_rate}}">
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