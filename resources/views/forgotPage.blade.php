@extends('layouts.appCopy')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Forgot Password</div>

                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-danger text-center">
                            {{ session('message')}}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{route('forgotPost')}}">
                        @csrf

                        <div class="form-group row">
                            <label for="vendor_code" class="col-md-4 col-form-label text-md-right">Employee P.No./Vendor User Name</label>
                            <div class="col-md-6">
                                <input id="vendor_code" type="vendor_code" class="form-control" name="vendor_code" placeholder="Employee Personal No./Vendor User Name" autocomplete="vendor_code">
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
                            <label for="password" class="col-md-4 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                                <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}" required></div>
                            </div>
                                
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Forgot
                                </button>
                            </div>
                        </div>
                    </form>
                   <br><center><a href="{{URL::to('/')}}">Back to Login</a></center>

                </div>
            </div>
        </div>
    </div>
</div>
<br>
<center><img src="{{URL::to('images/footer.png')}}"></center>
@endsection
<script src='https://www.google.com/recaptcha/api.js'></script>