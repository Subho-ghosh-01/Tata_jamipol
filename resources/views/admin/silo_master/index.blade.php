@extends('admin.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Silo Fields</li>
@endsection

@if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1)
    <script>window.location.href = "{{ url('admin/dashboard') }}";</script>
@else
    @section('content')
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Silo Fields</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.silo_master.create') }}" class="btn btn-sm btn-outline-secondary">+ Add New</a>
            </div>
        </div>

        <!-- Success Message -->
        <div class="form-group-row">
            <div class="col-sm-12 text-center">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-sm" id="listall">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Field Name</th>
                        <th>Label</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>Active</th>
                        <th>Order</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($silo_master->count() > 0)
                        @php $count = 1; @endphp
                        @foreach($silo_master as $field)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $field->name }}</td>
                                <td>{{ $field->label }}</td>
                                <td>{{ ucfirst($field->type) }}</td>
                                <td>
                                    @if($field->isrequired)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if($field->isactive)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $field->displayorder }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('admin.silo_master.edit', \Crypt::encrypt($field->id)) }}">Edit</a>
                                    |
                                    <a class="btn btn-danger btn-sm" onclick="deleteRecord('{{ $field->id }}')">Delete</a>

                                    <form id="delete-{{ $field->id }}" action="{{ route('admin.silo_master.destroy', $field->id) }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center text-danger">No Silo Field Found...</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endsection
@endif

@section('scripts')
    <script>
        function deleteRecord(id) {
            if (confirm("Are you sure you want to delete this record permanently?")) {
                document.getElementById('delete-' + id).submit();
            }
        }

        $(document).ready(function () {
            $('#listall').DataTable();
        });
    </script>
@endsection