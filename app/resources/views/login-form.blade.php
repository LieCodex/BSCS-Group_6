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

<body>
    <!-- Login Section -->
    <div id="login-section">
        <div class="text-center">
            <h1 class="h4 mb-4 login-register-heading">Sign In</h1>
        </div>
        <form action="/login" method="POST" class="user">
            @csrf
            <div class="form-group">
                <input name="loginemail" type="email" class="form-control form-control-user"
                    placeholder="Email">
            </div>
            <div class="form-group">
                <input name="loginpassword" type="password"
                    class="form-control form-control-user" placeholder="Password">
            </div>
            <button class="btn btn-orange btn-user btn-block">Login</button>
            <hr>
            <a href="{{ route('google.login') }}" class="btn btn-orange btn-user btn-block">
                <i class="fab fa-google fa-fw"></i> Login with Google
            </a>
        </form>
</body>
</html>