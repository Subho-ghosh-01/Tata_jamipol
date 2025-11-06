<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suraksh - Login</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Global Styles */
        body {
            background: linear-gradient(135deg, #2a5298, #1e3c72);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-x: hidden;
            font-family: "Poppins", sans-serif;
        }

        /* Header */
        .header-bar {
            background: rgba(30, 60, 114, 0.9);
            color: #fff;
            padding: 10px 0;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Container */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            padding: 30px 10px;
        }

        /* Card */
        .login-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            max-width: 880px;
            width: 100%;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.25);
            animation: fadeInUp 0.9s ease;
        }

        /* Left Panel */
        .brand-section {
            background: linear-gradient(160deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            text-align: center;
            padding: 40px 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-logo {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .brand-section img {
            max-width: 130px;
            opacity: 0.85;
            transition: transform 0.3s;
        }

        .brand-section img:hover {
            transform: scale(1.05);
        }

        /* Right Panel */
        .form-section {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 35px;
        }

        .form-section h4 {
            font-weight: 700;
            color: #1e3c72;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e3e6f0;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #2a5298;
            box-shadow: 0 0 0 0.25rem rgba(42, 82, 152, 0.25);
        }

        .input-group-text {
            background: #2a5298;
            color: #fff;
            border: none;
            border-radius: 10px 0 0 10px;
        }

        /* Buttons */
        .btn-login {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(42, 82, 152, 0.3);
        }

        /* Links */
        .form-section a {
            color: #2a5298;
            transition: 0.3s;
        }

        .form-section a:hover {
            color: #1e3c72;
            text-decoration: underline;
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Small Devices */
        @media (max-width: 767px) {
            .brand-section {
                display: none;
            }

            .form-section {
                border-radius: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shield-alt me-2 fs-4"></i>
                    <h5 class="mb-0 fw-semibold">Suraksh Security Solutions</h5>
                </div>
                <div class="text-end">
                    <small class="fst-italic">Secure • Reliable • Professional</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Content -->
    <div class="login-container">
        <div class="login-card">
            <div class="row g-0">
                <!-- Left Branding -->
                <div class="col-md-6 brand-section">
                    <div class="brand-logo">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h4 class="mb-2 fw-bold">Welcome Back</h4>
                    <p class="mb-3 small">Access your secure dashboard</p>
                    <img src="{{ asset('images/top_logo.png') }}" alt="Security">
                </div>

                <!-- Right Form -->
                <div class="col-md-6 form-section">
                    <h4 class="text-center mb-4">Sign In</h4>

                    @if (session()->has('message'))
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>{{ session('message') }}</div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('loginPost') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Employee ID / Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="vendor_code" required autofocus>
                            </div>
                            @if ($errors->has('vendor_code'))
                                @foreach ($errors->get('vendor_code') as $error)
                                    <div class="text-danger mt-1 small">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            @if ($errors->has('password'))
                                @foreach ($errors->get('password') as $error)
                                    <div class="text-danger mt-1 small">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mb-3 text-center">
                            <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                        </div>

                        <button type="submit" class="btn btn-login w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ URL::to('forgotPage') }}" class="me-3">
                            <i class="fas fa-key me-1"></i>Forgot Password?
                        </a>
                        <a href="{{ URL::to('RegisterGatepass') }}">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
</body>

</html>