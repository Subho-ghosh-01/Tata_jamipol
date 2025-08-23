@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Import Work Order</a></li>
@endsection
@section('content')
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
    <form action="{{route('admin.work_order_import')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <a href="{{ URL::to('public/documents/wo.xlsx')}}">Click Here to Download Sample File</a><br><br>
            </div>
            <div class="col-sm-12 text-center">
                <a herf=""> <input type="file" name="file_datas" required accept=".xlsx, .xls" class="btn btn-primary">
                </a><br><br><br><br>
            </div>
            <div class="col-sm-12 text-center">
                <input type="submit" name="button" class="btn btn-primary" value="Submit">
            </div>
        </div>
    </form>
@endsection

<!-- From JS Started -->
@section('scripts')
    <script>
    </script>
@endsection