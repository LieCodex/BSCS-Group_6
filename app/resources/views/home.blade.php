<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Squeal</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.css" rel="stylesheet">

    <style>
        body.guest-bg {
            margin: 0;
            padding: 0;
            background: url("assets/img/gradient_wallpaper_4k.png") no-repeat center center fixed;
            background-size: cover;
            font-family: 'Montserrat', sans-serif;
        }

        body.auth-bg {
            margin: 0;
            padding: 0;
            background: url("assets/img/gradient_wallpaper_4k.png") no-repeat center center fixed;
            background-size: cover;
            font-family: 'Montserrat', sans-serif;
        }

        .welcome-text {
            position: absolute;
            top: 150px;
            left: 35%;
            transform: translateX(-50%);
            font-size: 2.4rem;
            font-weight: 700;
            color: #e98000ff;
            z-index: 2000;
            font-family: 'Montserrat', sans-serif;
        }

        .dashboard-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 15px;
        }

        /* Guest (login/register) card */
        .card.guest-card,
        .card.guest-card .card-body,
        .card.guest-card .p-5 {
            background: rgba(255, 255, 255, 0) !important;
            backdrop-filter: blur(6px);
            border-radius: 15px;
        }

        /* Logged-in dashboard card fullscreen transparent */
        .card.dashboard-card,
        .card.dashboard-card .card-body {
            background: rgba(255, 255, 255, 0) !important; /* fully transparent */
            backdrop-filter: none !important;
            border-radius: 0; /* remove rounded corners for fullscreen */
            color: #ffffff;
            width: 100% !important;
            min-height: 100vh !important;
            box-shadow: none !important;
        }

        .card.dashboard-card h1,
        .card.dashboard-card h2,
        .card.dashboard-card h5,
        .card.dashboard-card p {
            color: #ffffff;
        }

        .card.dashboard-card .form-control-user {
            border: 2px solid #ff8000ff !important;
            color: #ffffffff !important;
            background: transparent !important;
        }

        .card.dashboard-card .form-control-user::placeholder {
            color: rgba(233, 128, 0, 0.7) !important;
        }

        .card.dashboard-card .btn-orange {
            background-color: transparent;
            border: 2px solid #e98000;
            color: #e98000;
        }

        .card.dashboard-card .btn-orange:hover {
            background-color: #e98000;
            color: white;
        }

        /* Posts inside dashboard */
        .card.post-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            color: #ffffff;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .small-logo {
            position: absolute;
            top: 15px;
            left: 20px;
            width: 60px;
            height: auto;
        }

        .login-register-heading {
            color: orange;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }

        .btn-orange {
            background-color: transparent;
            border: 2px solid #e98000;
            color: #e98000;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            transition: 0.3s ease;
        }

        .btn-orange:hover {
            background-color: #e98000;
            color: white;
            border-color: #e98000;
        }

        .form-control-user {
            background-color: transparent !important;
            border: 2px solid #ff8000ff !important;
            color: #f97f05ff !important;
            border-radius: 10px;
            font-weight: 500;
            font-family: 'Montserrat', sans-serif;
        }

        .form-control-user::placeholder {
            color: rgba(255, 255, 255, 1) !important;
            font-family: 'Montserrat', sans-serif;
        }

        .form-control-user:focus {
            background-color: transparent !important;
            border-color: #ffb200ff !important;
            box-shadow: 0 0 8px rgba(233, 128, 0, 0.6) !important;
            color: #ffffffff !important;
        }

        .toggle-link {
            background: none;
            border: none;
            color: #e98000;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            margin-top: 15px;
        }
    </style>
</head>

<body class="@guest guest-bg @else auth-bg @endguest">
    @guest
    <div class="welcome-text">SQUEAL</div>
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
                                                    <input name="loginname" type="text" class="form-control form-control-user"
                                                        placeholder="Name">
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
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>

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
