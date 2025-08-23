@extends('layouts.appCopy')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">2 Factor Authentication</div>
                    <div class="card-body">
                        @if (session()->has('message'))
                            <div class="alert alert-danger text-center">
                                {{ session('message')}}
                            </div>
                        @endif
                        <form method="POST" action="{{route('otpPost')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="enter_otp" class="col-md-12 col-form-label text-md-left">Please enter the OTP
                                    sent to your registered email below:-</label>
                                <div class="col-md-12">
                                    <input id="enter_otp" type="text" class="form-control" name="enter_otp" value="{{Session::get('otp')}}"
                                        placeholder="Enter OTP" required autofocus>
                                    <div style="margin-top: 10px;">
                                        @if ($errors->has('enter_otp'))
                                            @foreach($errors->get('enter_otp') as $error)
                                                <p class="alert alert-danger" style="text-align:center;">
                                                    <strong>{{ $error }}</strong>
                                                </p>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                        </form>
                        <a href="{{ route('logout1') }}" class="btn btn-danger">
                            <form id="logout-form" action="{{ route('logout1') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <span data-feather=""> </span> Cancel
                        </a>


                    </div>


                </div>
            </div>



        </div>


    </div>
    </div>
    </div>
    </div>
    </div><br>
    <center><img src="{{ URL::to('/images/footer.png') }}"></center>
@endsection