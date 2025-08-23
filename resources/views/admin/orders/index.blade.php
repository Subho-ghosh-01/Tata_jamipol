<?php 
use App\Division;
use App\Department;
?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Work Orders</a></li>
@endsection

<!-- breadcrumbs end -->
@if(Session::get('user_sub_typeSession') == 2)
    return redirect('admin/dashboard');
@else
    <!-- Content start section -->
    @section('content')
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Work Order</h1>
            <div class="row mx-md-n4">
                <div class="col px-md-2">
                    <div class="p-3 bg-light"><a href="{{route('admin.work_order_view')}}"
                            class="btn btn-sm btn-outline-secondary">Upload Work Order</a></div>
                </div>
                <div class="col px-md-2">
                    <div class="p-3 bg-light"><a href="{{route('admin.work-order.create')}}"
                            class="btn btn-sm btn-outline-secondary">Add Work Order</a></div>
                </div>
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
                        <th>Vendor Code</th>
                        <th>Order Code</th>
                        <th>Order Validity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($workorders->count() > 0)
                        <?php        $count = 1;?>
                        @foreach($workorders as $workorder)
                            <tr>
                                <td>{{$count++}}</td>
                                <td>{{$workorder->vendor_code}}</td>
                                <td>{{$workorder->order_code}}</td>
                                <td>{{$workorder->order_validity}}</td>
                                <td><a class="btn btn-info btn-sm"
                                        href="{{route('admin.work-order.edit', \Crypt::encrypt($workorder->id)) }}">Edit</a>
                                    <a class="btn btn-danger btn-sm" onclick="deleteRecord('{{$workorder->id}}')">Delete</a>
                                    <form id="delete-{{$workorder->id}}"
                                        action="{{ route('admin.work-order.destroy', $workorder->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="" style="color:red;text-align:center;">No Jobs Found.....</td>
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
            // alert(id)
            let choice = confirm("Are you sure want to delete the record permanently?");
            if (choice) {
                document.getElementById('delete-' + id).submit();
            }
        }

        $(document).ready(function () {
            $('#ListAll').DataTable();
        });

    </script>
@endsection