@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Power Shutdown</a></li>
@endsection
@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">List of Power Shutdown</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{route('admin.power_shutdown.create')}}" class="btn btn-sm btn-outline-secondary">Add Power Shutdown User </a>
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
        <table class="table table-striped table-sm" id="listall">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Email</th>
                    <th>Personal Number</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($powerShutdowns->count() > 0)
                    @php $count=1 @endphp
                        @foreach($powerShutdowns  as $powerShutdown)
                            <tr>
                                <td>{{$count++}}</td>
                                <td>{{$powerShutdown->email_id }}</td>
                                <td>{{$powerShutdown->p_number }}</td>
                                <td>{{$powerShutdown->name }}</td>                 
                                <td><a class="btn btn-info btn-sm" href="{{route('admin.power_shutdown.edit',\Crypt::encrypt($powerShutdown->id)) }}">Edit</a> |
                                <a class="btn btn-danger btn-sm" onclick="deleteRecord('{{$powerShutdown->id}}')">Delete</a>
                                 <form id="delete-{{$powerShutdown->id}}" action="{{ route('admin.power_shutdown.destroy',$powerShutdown->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>                                   
                            </tr>
                        @endforeach
                @else
                    <tr>
                        <td colspan="7" class="" style="color:red;text-align:center;">NA</td>
                    <tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')
<script>
    function deleteRecord(id){
        // alert(id)
        let  choice = confirm("Are you sure want to delete the record Pamanently?");
        if(choice){
            document.getElementById('delete-'+id).submit();
        }
    }
    // $(document).ready(function() {
    //     $('#listall').DataTable();
    // });
</script>
@endsection