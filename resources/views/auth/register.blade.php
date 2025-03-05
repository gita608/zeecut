<!doctype html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets/frontend/assets/') }}" data-template="vertical-menu-template-free"
    data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Register | {{ config('app.name') }}</title>

    <meta name="description" content="" />


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/vendor/fonts/remixicon/remixicon.css') }}" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/vendor/libs/node-waves/node-waves.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="{{ asset('assets/frontend/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/frontend/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/config.js') }}"></script>
</head>

<body>
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6 mx-4 ">
                <div class="card p-7 shadow-lg " style="border-radius: 15px; border: 1px solid #e0e0e0;">
                    <div class="app-brand justify-content-center mt-5">
                        <a href="{{ url('/') }}"
                            class="app-brand-link d-flex align-items-center gap-2 text-decoration-none">
                            <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="Logo" class="brand-logo"
                                style="height: 40px;">
                            <span class="app-brand-text text-heading fw-bold" style="font-size: 1.7rem; color: #333;">
                                {{ config('app.name') }}
                            </span>
                        </a>
                    </div>

                    <div class="card-body mt-1">
                        <h4 class="mb-1 text-center" style="color: #555;">Register at {{ config('app.name') }}
                            👋🏻</h4>
                        <p class="mb-4 text-center" style="color: #888;">Create your account and get started.</p>
                        
                        @if (session('error_message'))
                            <div class="alert alert-danger">
                                {{ session('error_message') }}
                            </div>
                        @endif
                        
                        @if (session('success_message'))
                            <div class="alert alert-success">
                                {{ session('success_message') }}
                            </div>
                        @endif

                        <form id="formRegistration" class="mb-5" action="{{ url('login/save_user') }}" method="POST">
                            @csrf
                            <div class="row mb-4">
                                <!-- Full Name -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" required />
                                        <label for="name">Name</label>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="phone" name="phone" oninput="number_length(10, 'phone')"
                                            placeholder="Phone" required />
                                        <label for="phone">Phone</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <!-- Date of Birth -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" class="form-control" id="dob" name="dob" placeholder="Date of Birth" required />
                                        <label for="dob">Date of Birth</label>
                                    </div>
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select id="gender" name="gender" class="form-select" required>
                                            <option value="" disabled selected>Choose Gender</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                        </select>
                                        <label for="gender">Gender</label>
                                    </div>
                                </div>
                            </div>
                            
                             <div class="row mb-4">
                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Email" required />
                                        <label for="email">Email</label>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password" class="form-control" name="password"placeholder="Password" required />
                                        <label for="password">Password</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <!-- PIN Code -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="pin_code" name="pin_code"
                                            placeholder="PIN Code" oninput="number_length(6, 'pin_code')" required />
                                        <label for="pin_code">PIN Code</label>
                                    </div>
                                </div>

                                <!-- Place -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="place" name="place" placeholder="Place" required />
                                        <label for="place">Place</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="form-floating form-floating-outline mb-4">
                                <textarea class="form-control" id="address" name="address" placeholder="Address" rows="1"
                                    style="height: 58px;" required></textarea>
                                <label for="address">Address</label>
                            </div>

                            
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" id="referred_by" class="form-control" name="referred_by" placeholder="Referral Code" />
                                <label for="referred_by">Referral Code</label>
                            </div>

                            <div class="mb-4">
                                <button class="btn btn-primary d-grid w-100" type="submit" style="border-radius: 10px;">
                                    Register
                                </button>
                            </div>
                        </form>

                        <p class="text-center mb-3">
                            <a href="{{ route('login') }}" style="text-decoration: none; color: #0056b3;">
                                <span>Already have an account? Login</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .authentication-wrapper.authentication-basic .authentication-inner {
            max-width: 40rem !important;
        }
    </style>
    <!-- JS Scripts -->
    <script src="{{ asset('assets/frontend/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script>
        function number_length(maxLength, fieldId) {
            let inputElement = document.getElementById(fieldId);
            let input = inputElement.value.replace(/[^0-9]/g, '');
            if (input.length > maxLength) input = input.slice(0, maxLength);
            inputElement.value = input;
        }
    </script>
</body>

</html>
