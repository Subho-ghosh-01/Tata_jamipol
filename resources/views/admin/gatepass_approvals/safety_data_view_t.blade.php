<?php 
use App\Department;
use App\UserLogin;
use App\ChangeRequest;
use App\Division;
?>

@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.safety_data_view.index')}}">List of Safety Data View</a></li>
@endsection                        
@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">List of Safety Data View </h1>
</div>



<form action="{{ route('admin.approve.index')}}" method="POST" enctype="multipart/form-data">


        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="my-permit"> 
                    <thead>
                        <tr>
                            <th>#</th>
                           
                           <th>Financial Year</th>
                             <th>Month</th>
                            <th>Branch</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($gatepasss->count() > 0 ) 
                    <?php $count =  1 ;?>
                        @foreach($gatepasss as $gatepass) 

                        @php
                        @$Division = Division::where('id',@$gatepass->division_id)->first();
                        @endphp
                       
                            <tr>
                                <td>{{$count ++}}</td>
                                  
                                  <td>{{@$gatepass->financial_year}}</td>
                                 <td>@if($gatepass->month == "1")
                                            {{"Janaury"}}
                                      @elseif($gatepass->month == "2")
                                      {{"February"}}
                                      @elseif($gatepass->month == "3")
                                      {{"March"}}
                                      @elseif($gatepass->month == "4")
                                      {{"April"}}
                                       @elseif($gatepass->month == "5")
                                      {{"May"}}
                                      @elseif($gatepass->month == "6")
                                      {{"June"}}
                                      @elseif($gatepass->month == "7")
                                      {{"July"}}
                                       @elseif($gatepass->month == "8")
                                      {{"August"}}
                                      @elseif($gatepass->month == "9")
                                      {{"September"}}
                                       @elseif($gatepass->month == "10")
                                      {{"October"}}
                                      @elseif($gatepass->month == "11")
                                      {{"November"}}
                                      @elseif($gatepass->month == "12")
                                      {{"December"}}
                                   @endif </td>
                                 <td>{{@$Division->name}}</td>
                                 

                            <td><a class="btn btn-info btn-sm" href="{{route('admin.edit_safety_data.edit',\Crypt::encrypt($gatepass->id))}}" title="Edit">View</a>

                            </td>      
                            </tr>
                        @endforeach
                    @else            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    @endif 
                </tbody>
                </table>
            </div>           
        </div>
        
        

   
	

</form>

@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#my-permit').DataTable();
    });
    $(document).ready(function() {
        $('#my-permit1').DataTable();
    });
    
    

</script>
@endsection