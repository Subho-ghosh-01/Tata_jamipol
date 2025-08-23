@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Division</a></li>
@endsection

@if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1)
    return redirect('admin/dashboard');
@else
    @section('content')
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Division</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{route('admin.division_new.create')}}" class="btn btn-sm btn-outline-secondary">Add Division </a>
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
                        <th>Sl No.</th>
                        <th>Name</th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($divisions->count() > 0)
                        <?php        $count = 1;?>
                        @foreach($divisions as $division)
                            <tr>
                                <td>{{$count++}}</td>
                                <td>{{$division->name}}</td>

                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="{{route('admin.division_new.edit', \Crypt::encrypt($division->id)) }}"
                                        title="Edit">Edit</a> |
                                    <a class="btn btn-danger btn-sm" onclick="deleteRecord('{{$division->id}}')">Delete</a>
                                    <form id="delete-{{$division->id}}"
                                        action="{{ route('admin.division_new.destroy', $division->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="" style="color:red;text-align:center;">No Division Found.....</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{-- $divisions->links() --}}
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
            $('#listall').DataTable();
        });
    </script>
@endsection