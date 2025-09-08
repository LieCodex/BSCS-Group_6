<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Squeal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
   <link href="{{ asset('assets/css/sb-admin-2.css') }}" rel="stylesheet">

   <style>

   </style>

</head>

<body class="guest-bg">
    <div class="welcome-text">
        SQUEAL
    </div>

    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5 position-relative guest-card">
                        <div class="card-body p-0">
                            <div class="row">

                                <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                    <img src="assets/img/squel logo orange.png" alt="logo" class="img-logo img-fluid"
                                        style="padding-top: 150px;">
                                </div>

                                <div class="col-lg-6">
                                    <div class="p-5 position-relative">
                                        
                                        <!-- Register Section -->
                                        <div class="text-center">
                                            <h1 class="h4 mb-4 login-register-heading">Sign Up</h1>
                                        </div>
                                        <form action="/register" method="POST" class="user">
                                            @csrf
                                            <div class="form-group">
                                                <input name="name" type="text" class="form-control form-control-user"
                                                    placeholder="Name">
                                            </div>
                                            <div class="form-group">
                                                <input name="email" type="text" class="form-control form-control-user"
                                                    placeholder="Email">
                                            </div>
                                            <div class="form-group">
                                                <input name="password" type="password" class="form-control form-control-user"
                                                    placeholder="Password">
                                            </div>
                                            <button class="btn btn-orange btn-user btn-block">Register</button>
                                            <hr>
                                            <a href="{{ route('google.login') }}" class="btn btn-orange btn-user btn-block">
                                                <i class="fab fa-google fa-fw"></i> Sign in with Google
                                            </a>
                                        </form>

                                        <div class="text-center">
                                            <a href="{{url('/')}}" class="toggle-link">Already have an account? Login</a>
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

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>