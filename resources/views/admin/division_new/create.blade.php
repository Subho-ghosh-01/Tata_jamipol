@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.division_new.index')}}">List of Division</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Division </li>
@endsection
@if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1)
    return redirect('admin/dashboard');
@else
    @section('content')
        <form action="{{route('admin.division_new.store')}}" method="post" autocomplete="off">
            @csrf
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
                <label for="form-control-label" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="">
                </div>
            </div>



            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    <input type="submit" name="submit" class="btn btn-primary" value="Add Division">
                </div>
            </div>
        </form>
    @endsection
@endif