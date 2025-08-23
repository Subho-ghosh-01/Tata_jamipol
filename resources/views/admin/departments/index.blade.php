<?php 
use App\Division;
?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Department</a></li>
@endsection
@if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1)
    return redirect('admin/dashboard');
@else
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Department</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{route('admin.department.create')}}" class="btn btn-sm btn-outline-secondary">Add Department </a>
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
@if(Session::get('user_sub_typeSession') == 3)
    <form class="form-inline" autocomplete=off action="{{route('admin.getdepartmentlist')}}" method="post">
            @csrf
        <div class="form-group mb-3">
            <select class="form-control rec" id="division_id" name="division_id"  onchange="getDepartment(this,this.value)">
                <option value="">Select Division</option>
                @if($divisions->count() > 0)
                    @foreach($divisions as $division)
                        <option value="{{@$division->id}}">{{@$division->name}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group mx-sm-2 mb-3">
            <input type="submit" name="submit" class="btn btn-primary" value="Find Department" onclick="return check();">
        </div>
    </form>
@endif
    <div class="table-responsive">
        <table class="table table-striped table-sm" id="listall">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Department Name</th>
                    <th>Division</th>
                    <th>Action</th>
                 </tr>
            </thead>
            <tbody>
                @if($departments->count() > 0 ) 
                    <?php $count = 1; ?>
                    @foreach($departments as $department)
                        <?php 
                            $division_name  = Division::where('id',$department->division_id)->get();
                        ?>
                        <tr>
                            <td>{{$count++}}</td>
                            <td>{{$department->department_name}}</td>
                            <td>{{@$division_name[0]->name}} </td>
                            <td>
                            <a class="btn btn-info btn-sm" href="{{route('admin.department.edit',\Crypt::encrypt($department->id)) }}" title="Edit">Edit</a> |
                            <a class="btn btn-danger btn-sm" onclick="deleteRecord('{{$department->id}}')">Delete</a>
                                <form id="delete-{{$department->id}}" action="{{ route('admin.department.destroy',$department->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else            
                <tr>
                    <td colspan="4" class="" style="color:red;text-align:center;">No Departments Found.....</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-12">
            {{-- $departments->links() --}}
        </div>
    </div>
@endsection
@endif
@section('scripts')
<script>
    function deleteRecord(id){
        // alert(id)
        let  choice = confirm("Are you sure? You want to delete the record Pamanently?");
        if(choice){
            document.getElementById('delete-'+id).submit();
        }
    }
    $(document).ready(function() {
        $('#listall').DataTable();
    });
    function getDepartment(th,divisionID) {
        if(divisionID!="")
        {
            $("#department_id").html('<option value="">--Select--</option>');
            if(divisionID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.job.department')}}/" + divisionID,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        for(var i=0;i<data.length;i++){
                            $('#department_id').append('<option value="'+data[i].id+'" >'+data[i].department_name+'</option>');
                        }
                    }
                });
            }else{
                $('#department_id').html('<option value="">Select Department</option>');
            }     
        }
    }
function check()
{
    var flag=true;
    $(".rec").each(function(e){
        if($(this).val()=="")
        {
            $(this).addClass("verror");
            flag=false;
        }
        else
        {
            $(this).removeClass("verror");
        }
    })
    if(flag==true)
    {}
    else
    {
        return false;
    }
}   
</script>
@endsection