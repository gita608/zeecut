<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ config('app.name') }}" name="description" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        body {
            background-color: #f8f9fa;
        }

        .auth-page {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .auth-form-container {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .auth-logo img {
            height: 40px;
            margin-right: 8px;
        }

        .auth-logo span {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            border-radius: 5px;
        }

        .alert {
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="auth-page">
        <div class="auth-form-container">
            <div class="auth-logo">
                <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="Logo">
                <span>{{ config('app.name') }}</span>
            </div>
            <!-- Display error message if set -->
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <form action="{{ route('verify') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter your password" required>
                        <button class="btn btn-light" type="button" id="password-addon">
                            <i class="mdi mdi-eye-outline"></i>
                        </button>
                    </div>
                </div>
                <!-- <div class="mb-3 text-end">
                    <a href="#" class="text-muted small">Forgot password?</a>
                </div> -->
                <div class="mb-3">
                    <button class="btn btn-primary w-100" type="submit">Log In</button>
                </div>
                <p class="text-center">
                    <a href="{{route('register')}}">Register Here</a>
                </p>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/waves.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <!-- pace js -->
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <!-- password addon init -->
    <script src="{{ asset('assets/js/pass-addon.init.js') }}"></script>

</body>

</html>
