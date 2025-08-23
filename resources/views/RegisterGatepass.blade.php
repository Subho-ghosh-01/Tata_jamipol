<?php 
use App\Division;
?>
@extends('layouts.appCopy')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Register</div>

                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success text-center">
                            {{ session('message')}}
                        </div>
                    @endif
					@if (session()->has('message2'))
                        <div class="alert alert-danger text-center">
                            {{ session('message2')}}
                        </div>
                    @endif
					
                
                    <form action="{{route('check_otp')}}" method="POST"  autocomplete="off"  <?php if(Session::get('otp')){ echo "style='display:none'";} ?>>
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" placeholder="Name" autocomplete="off"  required>
                                    
            
                            </div>
                        </div>
						
						<div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email </label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" placeholder="Email" autocomplete="off" required>
                              </div>
                        </div>
				<div class="form-group row" >
                            <label for="password" class="col-md-4 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                                 <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}" required></div>
                            </div>
                                
               </div> 
              
						
                 <div class="form-group row mb-0">
                           <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Send OTP
                                </button>
								
                            </div>
					 </div>		
                  </div>
                   </form>
				   
				   <form action="{{route('gatepass_register_permit.store')}}" method="POST"  autocomplete="off" <?php if(Session::get('otp')){ echo "style='display:block'";}else{ echo "style='display:none'";} ?>>
				   
                    
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name1</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" placeholder="Name" autocomplete="off" value="{{Session::get('name')}}" required readonly>
                                    
                            </div>
                        </div>
						
						<div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email </label>
                            <div class="col-md-6">
                              <input id="email" type="text" class="form-control" name="email" value="{{Session::get('email')}}" placeholder="Email"  autocomplete="off" required readonly>
                              </div>
                        </div>
						
						<div class="form-group row">
                            <label for="enter_otp" class="col-md-4 col-form-label text-md-right">Enter OTP</label>
                            <div class="col-md-6">
                                <input id="enter_otp" type="text" class="form-control" name="enter_otp" placeholder="Enter OTP" required autofocus>

                            </div>
                        </div>
						
				<div class="form-group row" >
                            <label for="password" class="col-md-4 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                                 <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}" required></div>
                            </div>
                                
               </div> 
              
						
                 <div class="form-group row mb-0">
                           <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                   Register
                                </button>
								<a href="" id="cancle" onclick="myGreeting();" class="btn btn-danger">Cancle</a>
                            </div>
					 </div>		
                  </div>
                   </form>
                      
                       <center><a href="{{URL::to('/')}}">Back to Login</a></center>  
                   
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<center><img src="{{URL::to('images/footer.png')}}"></center>
@endsection
<!-- Captcha -->
<script src='https://www.google.com/recaptcha/api.js'></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>


<script>
//const myTimeout = setTimeout(myGreeting, 60000);

function myGreeting() {
   <?php //Session::flush(); ?>
}
</script>


