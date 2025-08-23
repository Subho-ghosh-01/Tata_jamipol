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
                            <th>Draft Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($gatepasss->count() > 0 ) 
                    <?php $count =  1 ;
				          $date = date('Y-m-d');
				    ?>
                        @foreach($gatepasss as $gatepass) 

                        @php
                        @$Division = Division::where('id',@$gatepass->division_id)->first();
                        @endphp
                       
                            <tr>
                                <td>{{$count ++}}</td>
                                  
                                  
                                  <td>{{date('Y', strtotime($gatepass->financial_year))}}</td>

                                <td>{{date('F', strtotime($gatepass->month))}}</td>

                                 <td>{{@$Division->name}}</td>
                                 
                                 <td>
                                 @if($gatepass->draft == 'Yes')
                                   <span class='badge badge-warning'>Pending</span>   
                                     @elseif($gatepass->draft == 'No')
                                   <span class='badge badge-success'>Completed</span>
                                   @else
                                   <span class='badge badge-success'>Completed</span>
                                @endif  
                                 </td>

                            <td>
                            @if($gatepass->draft == 'Yes' || Session::get('user_sub_typeSession') == 3 || $gatepass->draft_till_date >= $date) 
                             <a class="btn btn-info btn-sm" href="{{route('admin.edit_safety_data_draft.edit',\Crypt::encrypt($gatepass->id))}}" title="Edit">Edit</a>
                             <a class="btn btn-info btn-sm" href="{{route('admin.edit_safety_data.edit',\Crypt::encrypt($gatepass->id))}}" title="Edit">View</a>
                            @elseif($gatepass->draft == 'NO')
                            <a class="btn btn-info btn-sm" href="{{route('admin.edit_safety_data.edit',\Crypt::encrypt($gatepass->id))}}" title="Edit">View</a>
                            @else
                            <a class="btn btn-info btn-sm" href="{{route('admin.edit_safety_data.edit',\Crypt::encrypt($gatepass->id))}}" title="Edit">View</a>
                            @endif
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