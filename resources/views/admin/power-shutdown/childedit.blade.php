<?php use App\Division;
use App\Department;
use App\Section; ?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Shutdown</li>
@endsection
@section('content')
<form action="{{route('admin.childupdate',$shutdowns->id)}}" method="post"  autocomplete="off">
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
    {{-- $shutdowns->id --}}
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Supervisor Name</label>
            <div class="col-sm-10" id="">
                <input type="text" class="form-control" name="supervisor_ven" id="" value="{{$shutdowns->supervisor_name}}">&nbsp;
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Electrical Supervisory License numbe</label>
            <div class="col-sm-10" id="">
                <input type="text" class="form-control" name="electrical_license_ven" id="" value="{{$shutdowns->electrical_license}}">&nbsp;
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">License Number Validity date</label>
            <div class="col-sm-10" id="">
                <input type="date" class="form-control" name="validity_date_ven" id="" value="{{$shutdowns->validity_date}}">&nbsp;
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Competent for Voltage level</label>
            <div class="col-sm-10">
                <table style="width: 180px;">
                    <tr><td><span>132KV</span></td>
                        <td><input type="radio" name="v132kv_ven" @if($shutdowns->kv132 == 'yes') {{'checked'}} @endif value="yes">&nbsp; Yes
                        <input type="radio" name="v132kv_ven" @if($shutdowns->kv132 == 'no') {{'checked'}} @endif  value="no">&nbsp; No
                        </td>
                    <tr>
                    <tr><td><span>33KV</span></td>
                        <td><input type="radio" name="v33kv_ven" @if($shutdowns->kv33 == 'yes') {{'checked'}} @endif value="yes">&nbsp; Yes
                        <input type="radio" name="v33kv_ven" @if($shutdowns->kv33 == 'no') {{'checked'}} @endif value="no">&nbsp; No
                        </td>
                    <tr><td><span>11KV</span></td>
                        <td><input type="radio" name="v11kv_ven" @if($shutdowns->kv11 == 'yes') {{'checked'}} @endif  value="yes">&nbsp; Yes
                        <input type="radio" name="v11kv_ven" @if($shutdowns->kv11 == 'no') {{'checked'}} @endif value="no">&nbsp; No
                        </td>
                    <tr><td><span>LT</span></td>
                        <td><input type="radio" name="vlt_ven"  @if($shutdowns->lt == 'yes') {{'checked'}} @endif value="yes">&nbsp; Yes
                        <input type="radio" name="vlt_ven" @if($shutdowns->lt == 'no') {{'checked'}} @endif value="no">&nbsp; No
                        </td>
                    <tr>
                </table>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Issue Power Clearance</label>
            <div class="form-check col-sm-10">
                    <input type="radio" class="" name="issue_power_ven" @if($shutdowns->issue_power == 'yes') {{'checked'}} @endif  value="yes">&nbsp; Yes
                    <input type="radio" class=""  name="issue_power_ven" @if($shutdowns->issue_power == 'no') {{'checked'}} @endif  value="no">&nbsp; No
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Receive Power Clearance</label>
            <div class="form-check col-sm-10">
                <input type="radio" class="" name="rec_power_ven"  @if($shutdowns->receive_power == 'yes') {{'checked'}} @endif value="yes">&nbsp; Yes
                <input type="radio" class=""  name="rec_power_ven" @if($shutdowns->receive_power == 'no') {{'checked'}} @endif value="no">&nbsp; No  
            </div>
        </div>       
        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <input type="submit" name="submit" class="btn btn-primary" value="Submit">
            </div>
        </div>
</form>
@endsection
