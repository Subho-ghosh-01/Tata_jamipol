<?php 
use App\Division;
?>

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="">Request Visitor Gatepass</a></li>
@endsection
@section('content')
@extends('admin.app')


                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success text-center">
                            {{ session('message')}}
                        </div>
                    @endif
                    <!--<form method="POST" action="{{route('RequestVGatepassPost')}}">-->
                    <form action="" method="POST"  autocomplete="off" enctype="multipart/form-data">
                        @csrf

                  <center> <h5 >Please Watch This Video Before Filling The Form</h5> </center>
<br>

                 <tr><td>   <center><video width="500" controls autoplay poster="">
     <source src="http://localhost/jamipol_vms/public/documents/safety_vms/movie.mp4" >
 
  
</video></center>

                            <br> </td></tr>      
  
              <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-4">
                                 <a href="{{URL::to('RequestVGatepass')}}"<button type="submit" class="btn btn-primary">
                                  I will follow the safety guidelines, please proceed to request visitor gatepass.
                                </button></a>
                                
                            </div>
                 </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>

@endsection
