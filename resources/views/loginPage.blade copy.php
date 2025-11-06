@extends('layouts.appCopy')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Login</div>

                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-danger text-center">
                            {{ session('message')}}
                        </div>
                    @endif
                    <form method="POST" action="{{route('loginPost')}}">
                        @csrf
                        <div class="form-group row">
                            <label for="vendor_code" class="col-md-4 col-form-label text-md-right">Employee P.No./Vendor User Name</label>
                            <div class="col-md-6">
                                <input id="vendor_code" type="text" class="form-control" name="vendor_code" value="" placeholder="Employee Personal No./Vendor User Name" required autofocus>
                                <div style="margin-top: 10px;"> 
                                    @if ($errors->has('vendor_code'))
                                        @foreach($errors->get('vendor_code') as $error)
                                        <p class="alert alert-danger" style="text-align:center;">
                                            <strong>{{ $error }}</strong>
                                        </p>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                                    
                                    <div style="margin-top: 10px;"> 
                                        @if ($errors->has('password'))
                                            @foreach($errors->get('password') as $error)
                                            <p class="alert alert-danger">
                                                <strong>{{ $error }}</strong>
                                            </p>
                                            @endforeach
                                        @endif
                                    </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                               <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}" required></div>
                            </div>
                        </div>

                        

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                {{-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif --}} 
                            </div>
                        </div> 
						
				
                    </form>
                   <br><center><a href="{{URL::to('forgotPage')}}">Forgot Password?</a>/<a href="{{URL::to('RegisterGatepass')}}">Register</a></center><br>
				<!-- <center> <a href="{{URL::to('RegisterGatepass')}}"><button type="submit" class="btn btn-primary">Register</button></a></center><br>-->
				
                </div>
            </div>
        </div>
    </div>
</div><br>
<center><img src="{{ asset('images/footer.png') }}"></center>
@endsection
<script src='https://www.google.com/recaptcha/api.js'></script>
