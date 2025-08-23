@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Reports</a></li>
@endsection


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Safety Reports</h1>
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

    <form class="form-inline" autocomplete=off action="{{route('admin.safety_report')}}"  method="POST">
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
                <option value="ALL">ALL Department</option>
            </select>
        </div>
<div class="form-group mb-2" style="display:none;">
            <select class="form-control" name="" id="" >

    <option value="">From Month</option>
    <option  value='1'>Janaury</option>
    <option value='2'>February</option>
    <option value='3'>March</option>
    <option value='4'>April</option>
    <option value='5'>May</option>
    <option value='6'>June</option>
    <option value='7'>July</option>
    <option value='8'>August</option>
    <option value='9'>September</option>
    <option value='10'>October</option>
    <option value='11'>November</option>
    <option value='12'>December</option>
            </select>
        </div>
        <div class="form-group mb-2" >
            <label for="form-control-label" class="col-sm-2 col-form-label">From Month</label><br>
           <input type="date" name="from_month" class="form-control rec" placeholder="Select Month" min="2023-01"
    max="2025-12" required>
      </div>
       <div class="form-group mb-2" >
            <label for="form-control-label" class="col-sm-2 col-form-label">To Month</label><br>
           <input type="date" name="to_month" class="form-control rec" placeholder="Select Month" min="2023-01"
    max="2025-12" required>
      </div>
        
<div class="form-group mb-2" style="display:none;" >
            <select name="financial_year" class="form-control rec">
                <option value="">All Year</option>
            </select> 
 </div>
        <div class="form-group mx-sm-2 mb-2" style="display: none;">
            <input type="text" name="fromdate" class="form-control" placeholder="From Date" id="start_date" >
        </div>
        <div class="form-group mb-2" style="display: none;">
            <input type="text" name="todate" class="form-control" placeholder="To Date" id="end_date" >
        </div>
        <div class="form-group mx-sm-2 mb-2">
            <input type="submit" name="submit" class="btn btn-primary" value="Find Report">
        </div>
    </form>
 <button class="btn btn-info" >Download <i class='fas fa-long-arrow-alt-down'></i></button>
    <div class="table-responsive">

        <table id="example" class="display table table-striped table-bordered" style="width:100%">
            <thead>
              <tr>
                            <th>#</th>
                           
                           <th>Financial Year</th>
                             <th>Month</th>
                            <th>Branch</th>
                            <th>No of Safety Training Session For Employee</th>
                            <th>No of Employees  Attended  Safety Training</th>
                            <th>No of Safety Training for Contractor Employees </th>
                            <th>No of Contractor Employees  attended the training </th>
                            <th>No of Health Awareness Session</th>
                            <th>No of Mass Meeting </th>
                            <th>No of AISSC Meeting</th>
                            <th>No of Mock Drill</th>
                            <th>No of Job Cycle Check</th>
                            <th>No of Safety Kaizen</th>
                            <th>No of Sr.Leader Line Walk</th>
                            <th>No of Safety Campaign</th>
                            <th>No of MOC </th>
                            <th>No of SOP revised </th>
                            <th>No of Fatilities</th>
                            <th>No of Major Fires</th>
                            <th>No of Lost Time Injury  </th>
                            <th>No of Restricted Work Case</th>
                            <th>No of Medical Treatment Case </th>
                            <th> No of First Aid Case</th>
                            <th>Total No of Incidents occurred during the Month </th>
                            <th> No of Road Related Incident (inside + outside premises)</th>
                            <th>Average number of employees present during the month</th>
                            <th>Average number of contractor employees present during the month</th>
                            <th>Qualitative Information</th>

                            <th>Action</th>
                        </tr>
            </thead>
            <tbody>
                         @if($report)
     @php $count=1 @endphp
                        @foreach($report  as $reports) 
                        @php
                        @$Division = DB::table('divisions')->where('id',$reports->division_id)->first();
                        @endphp
                       
                          
               <tr>
                                 <td>{{$count ++}}</td>
                                 <td>{{date('Y', strtotime($reports->financial_year))}}</td>
                                 <td>{{date('F', strtotime($reports->month))}}</td>
                                 <td>{{@$Division->name}}</td>
                                 <td>{{@$reports->q1}}</td>
                                 <td>{{@$reports->q2}}</td>
                                 <td>{{@$reports->q3}}</td>
                                 <td>{{@$reports->q4}}</td>
                                 <td>{{$reports->q5}}</td>
                                 <td>{{@$reports->q10}}</td>
                                 <td>{{@$reports->q11}}</td>
                                 <td>{{@$reports->q12}}</td>
                                 <td>{{@$reports->q13}}</td>
                                 <td>{{@$reports->q14}}</td>
                                 <td>{{@$reports->q15}}</td>
                                 <td>{{@$reports->q16}}</td>
                                 <td>{{@$reports->q17}}</td>
                                 <td>{{@$reports->q18}}</td>
                                 <td>{{@$reports->T1}}</td>
                                 <td>{{@$reports->T2}}</td>
                                 <td>{{@$reports->T3}}</td>
                                 <td>{{@$reports->T4}}</td>
                                 <td>{{@$reports->T5}}</td>
                                 <td>{{@$reports->T6}}</td>
                                 <td>{{@$reports->T7}}</td>
                                 <td>{{@$reports->T8}}</td>
                                 <td>{{@$reports->T9}}</td>
                                 <td>{{@$reports->T10}}</td>
                                 <td>{{$reports->remarks}}</td>
                                 <td><a class="btn btn-info btn-sm" href="{{route('admin.edit_safety_data.edit',\Crypt::encrypt($reports->id))}}" title="Edit">View</a>

                            </td>      
                            </tr>
                     
                   @endforeach
                   @endif
                   </tbody>
        </table>
    </div>
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css" integrity="sha512-BMbq2It2D3J17/C7aRklzOODG1IQ3+MHw3ifzBHMBwGO/0yUqYmsStgBjI0z5EYlaDEFnvYV7gNYdD3vFLRKsA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@section('scripts')
<script>
  //  $('#start_date').datetimepicker({
      //  format:'Y/m/d'
    //});
   // $('#end_date').datetimepicker({
     //   format:'Y/m/d'
  //  });    
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
<script>
$(document).ready(function() {
 var current_year = new Date().getFullYear()
 var amount_of_years = 10

  for (var i = 0; i < amount_of_years+1; i++) {
    var year = (current_year+i).toString();
    var element = '<option value="' + year + '">' + year + '</option>';
    $('select[name="financial_year"]').append(element)
  }
})
</script>

@endsection
