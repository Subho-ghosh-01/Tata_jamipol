@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Reports</a></li>
@endsection

@if(Session::get('user_sub_typeSession') == 2)
    return redirect('admin/dashboard');
@else
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">VMS Reports</h1>
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

    <form class="form-inline" autocomplete=off action="{{route('admin.vms_report')}}"  method="POST">
        @csrf  
       <div class="form-group mb-2">
            <select class="form-control" name="divi_id" onchange="getDepartment(this,this.value)">
                <option value="">ALL Division</option>
                @if($divisions->count() > 0)
                    @foreach($divisions as $division)
                        <option value="{{$division->id}}">{{$division->name}}</option>
                    @endforeach
                @endif
            </select>
        </div>
         <div class="form-group mb-2" style="display: none;">
            <select class="form-control" name="dept_id" id="department_id">
                <option value="">ALL Department</option>
            </select>
        </div> 
        <div class="form-group mx-sm-2 mb-2">
            <input type="text" name="fromdate" class="form-control" placeholder="From Date" id="start_date" required>
        </div>
        <div class="form-group mb-2">
            <input type="text" name="todate" class="form-control" placeholder="To Date" id="end_date" required>
        </div>
        <div class="form-group mx-sm-2 mb-2">
            <input type="submit" name="submit" class="btn btn-primary" value="Find Report">
        </div>
    </form>
 
    <div class="table-responsive">
        <table id="example" class="display table table-striped table-bordered" style="width:100%">
            <thead>
              <tr>
                            <th>#</th>
                            <th>Sl No</th>
                            <th>Visitor Name</th>
                            <th>Visitor Mobile No</th>
                            <th>Visitor Company</th>
                            
                            
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>From Time</th>
                            <th>To Time</th>
                           
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
            </thead>
            <tbody>
                         @if($report)
     @php $count=1 @endphp
                        @foreach($report  as $reports) 
                       
                          
                <tr>
                <td>{{$count++}}</td>
                 <td>{{$reports->full_sl}}</td>
                  <td>{{$reports->visitor_name}}</td>
                                  <td>{{$reports->visitor_mobile_no}}</td>
                                  <td>{{$reports->visitor_company}}</td>
                                  <td>{{date('d-F-Y',strtotime($reports->from_date))}}</td>
                                  <td>{{date('d-F-Y',strtotime($reports->to_date))}}</td>
                                  <td>{{date('h:i A', strtotime(@$reports->from_time))}}</td>
                                  <td>{{date('h:i A', strtotime(@$reports->to_time))}}</td>
                                 
                                  
                                  
                                 <td> @if($reports->status == "Pending_to_approve")
                                            {{"Pending To Approve"}}
                                        @elseif($reports->status == "issued")
                                            {{"Issued"}}
                                  @elseif($reports->status == "Rejected")
                                            {{"Rejected"}}
                                  @elseif($reports->status == "Completed")
                                            {{"Completed"}}
                                @endif 
                                  </td>
                     <td><a class="btn btn-info btn-sm" href="{{route('admin.edit.edit',\Crypt::encrypt($reports->id))}}" title="Edit">View</a></td>
                   </tr>
                     
                   @endforeach
                   @endif
                   </tbody>
        </table>
    </div>
@endsection
@endif
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script>
    $('#start_date').datetimepicker({
        format:'Y/m/d'
    });
    $('#end_date').datetimepicker({
        format:'Y/m/d'
    });    
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
            ]
        });
    });
    function getDepartment(th,divisionID) {
        if(divisionID!="")
        {
            $("#department_id").html('<option value="ALL">ALL</option>');
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
</script>
@endsection