<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Squeal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/sb-admin-2.css') }}" rel="stylesheet">
</head>

<body class="guest-bg">

    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5 position-relative guest-card">
                        <div class="card-body p-0">
                            <div class="row">

                                <div class="col-lg-6 d-none d-lg-flex flex-column align-items-center justify-content-center text-center logo-wrapper">

                                    <img src="assets/img/squel logo orange.png" alt="logo" class="img-logo img-fluid">
                                </div>

                                <div class="col-lg-6">
                                    <div class="p-5 position-relative">
                                        <!-- Login Section -->
                                        <div class="text-center">
                                            <h1 class="h4 mb-4 login-register-heading">Sign In</h1>
                                        </div>
                                        <form action="{{ route('login') }}" method="POST" class="user">
                                            @csrf
                                            <div class="form-group">
                                                <input name="loginemail" type="email" class="form-control form-control-user"
                                                    placeholder="Email" required>
                                            </div>
                                            <div class="form-group mt-3">
                                                <input name="loginpassword" type="password" class="form-control form-control-user" placeholder="Password" required>
                                            </div>
                                            <button class="btn btn-orange btn-user btn-block mt-4">Login</button>
                                            <hr>
                                            <a href="{{ route('google.login') }}" class="btn btn-orange btn-user btn-block">
                                                <img src="{{ asset('assets/img/GLogo.png') }}" alt="Google Logo" class="w-5 h-5 mr-2 inline-block">
                                                Sign in with Google
                                            </a>
                                        </form>

                                        <div class="text-center mt-3">
                                            <a href="{{ route('register.form') }}" class="toggle-link">Don't have an account? Register</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
