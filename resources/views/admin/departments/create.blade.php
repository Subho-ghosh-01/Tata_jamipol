@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.department.index')}}">List of Department</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Department</li>
@endsection
@if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1)
    return redirect('admin/dashboard');
@else
@section('content')
<form action="{{route('admin.department.store')}}" method="post"  autocomplete="off">
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
        <label for="form-control-label" class="col-sm-2 col-form-label">Department Name<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="department_name" id="" value="">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Divisions<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-5" id="add_new">
            <select class="form-control appendrow"  name="division_id[]">
                <option value="">Select Division</option>
                @if($divisions->count() > 0)
                    @foreach($divisions as $division)
                        <option value="{{$division->id}}">{{$division->name}}</option>
                    @endforeach
                @endif 
            </select>
        </div>
        <div class="col-sm-1">
            <button type="button" id="btn-add" class="btn btn-primary btn-sm">+</button>&nbsp;
            <button type="button" id="btn-remove" class="btn btn-danger btn-sm">-</button>
        </div> 
    </div>
  
    
    
    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary" value="Add Department">
        </div>
    </div>
</form>
@endsection
@endif
@section('scripts')
<script>
   //Append code
    $("#btn-add").on("click", function (e) {
    var datas='';
        datas +='&nbsp;<select class="form-control appendrow" name="division_id[]"><option value="">Select Divisions</option>';
            @if($divisions->count() > 0)
                @foreach($divisions as $division)
                    datas +='<option value="{{$division->id}}">{{$division->name}}</option>';
                @endforeach
            @endif 
        datas +='</select>';
    $('#add_new').append(datas);

    });

    //Remove 
    $("#btn-remove").on("click", function (e) {
        if($('.appendrow').length > 1){
            $(".appendrow:last").remove();
        }
    });

</script>
@endsection
