<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Squeal</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
   <link href="{{ asset('assets/css/sb-admin-2.css') }}" rel="stylesheet">

   <style>

   </style>

</head>

<body class="@guest guest-bg @else auth-bg @endguest">
    @guest
    <div class="welcome-text">
        SQUEAL
    </div>
    @endguest

    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5 position-relative @guest guest-card @else dashboard-card @endguest">
                        <div class="card-body p-0">
                            <div class="row">

                                @guest
                                <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                    <img src="assets/img/squel logo orange.png" alt="logo" class="img-logo img-fluid"
                                        style="padding-top: 150px;">
                                </div>
                                @endguest

                                <div class="col-lg-6">
                                    <div class="p-5 position-relative">

                                        @auth
                                        <img src="assets/img/squel logo orange.png" alt="logo" class="small-logo">

                                        <form action="/logout" method="POST" class="logout-btn">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">Logout</button>
                                        </form>

                                        <div class="text-center mt-5">
                                            <h1 class="h4 mb-4">Welcome {{ auth()->user()->name }}!</h1>
                                        </div>

                                        <h2 class="h5 mb-3">Create a new post</h2>
                                        <form action="/create-post" method="POST" class="user">
                                            @csrf
                                            <div class="form-group">
                                                <input name="title" type="text" class="form-control form-control-user"
                                                    placeholder="Post Title">
                                            </div>
                                            <div class="form-group">
                                                <textarea name="body" class="form-control form-control-user"
                                                    placeholder="Body Content"></textarea>
                                            </div>
                                            <button class="btn btn-orange btn-user btn-block btn-sm">Save Post</button>
                                        </form>
                                        <hr>

                                        <h2 class="h5 mb-3">All Posts</h2>
                                        @foreach ($posts as $post)
                                        <div class="card post-card mb-3">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $post['title'] }}</h5>
                                                <p class="card-text">{{ $post['body'] }}</p>
                                                <a href="/edit-post/{{$post->id}}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="/delete-post/{{$post->id}}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        @endforeach

                                        @else
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
                                            <div class="text-center">
                                                <button id="show-register" class="toggle-link">Don’t have an account? Register</button>
                                            </div>
                                        </div>

                                        <!-- Register Section (hidden by default) -->
                                        <div id="register-section" style="display:none;">
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
                                                <button id="show-login" class="toggle-link">Already have an account? Login</button>
                                            </div>
                                        </div>
                                        @endauth

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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script>
        // Toggle login/register
        document.getElementById("show-register").addEventListener("click", function () {
            document.getElementById("login-section").style.display = "none";
            document.getElementById("register-section").style.display = "block";
        });

        document.getElementById("show-login").addEventListener("click", function () {
            document.getElementById("register-section").style.display = "none";
            document.getElementById("login-section").style.display = "block";
        });
    </script>

</body>
</html>
