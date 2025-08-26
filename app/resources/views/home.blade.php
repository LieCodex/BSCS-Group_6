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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.css" rel="stylesheet">

    <style>
        /* Guest background (image) */
        body.guest-bg {
            margin: 0;
            padding: 0;
            background: url("assets/img/wp9775729.webp") no-repeat center center fixed;
            background-size: cover;
            font-family: 'Nunito', sans-serif;
        }

        /* Auth background (plain white) */
        body.auth-bg {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: 'Nunito', sans-serif;
        }

        /* Welcome text styling */
        .welcome-text {
            position: absolute;
            top: 150px;
            left: 35%;
            transform: translateX(-50%);
            font-size: 2.4rem;
            font-weight: 700;
            color: #000108ff;
            z-index: 2000;
        }

        /* Dashboard Wrapper */
        .dashboard-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Default card style */
        .card {
            border-radius: 15px;
        }

        /* Guest card = semi-transparent */
        .card.guest-card,
        .card.guest-card .card-body,
        .card.guest-card .p-5 {
            background: rgba(255, 255, 255, 0.2) !important;
            backdrop-filter: blur(6px);
            border-radius: 15px;
        }

        /* Auth card = solid white */
        .card.auth-card {
            background: #ffffff;
        }

        /* Logout button at top right */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        /* Small logo for logged-in users */
        .small-logo {
            position: absolute;
            top: 15px;
            left: 20px;
            width: 60px;
            height: auto;
        }

        /* Login/Register headings */
        .login-register-heading {
            color: black;
        }
    </style>
</head>

<body class="@guest guest-bg @else auth-bg @endguest">
    @guest
    <!-- Welcome text only visible if not logged in -->
    <div class="welcome-text">Welcome to Squeal!</div>
    @endguest

    <!-- Dashboard Wrapper -->
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">

                    <!-- Card changes depending on auth -->
                    <div
                        class="card o-hidden border-0 shadow-lg my-5 position-relative @guest guest-card @else auth-card @endguest">
                        <div class="card-body p-0">
                            <div class="row">

                                <!-- Show big logo only for guest -->
                                @guest
                                <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                    <img src="assets/img/squel logo orange.png" alt="logo" class="img-logo img-fluid"
                                        style="padding-top: 150px;">
                                </div>
                                @endguest

                                <div class="col-lg-6">
                                    <div class="p-5 position-relative">

                                        @auth
                                        <!-- Small logo in top-left -->
                                        <img src="assets/img/squel logo orange.png" alt="logo" class="small-logo">

                                        <!-- Logout button in top-right -->
                                        <form action="/logout" method="POST" class="logout-btn">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">Logout</button>
                                        </form>

                                        <div class="text-center mt-5">
                                            <h1 class="h4 text-gray-900 mb-4">Welcome {{ auth()->user()->name }}!</h1>
                                        </div>

                                        <!-- Create Post -->
                                        <h2 class="h5 text-gray-800">Create a new post</h2>
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
                                            <button class="btn btn-primary btn-user btn-block">Save Post</button>
                                        </form>
                                        <hr>

                                        <!-- All Posts -->
                                        <h2 class="h5 text-gray-800">All Posts</h2>
                                        @foreach ($posts as $post)
                                        <div class="card mb-3">
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
                                        <!-- Login -->
                                        <div class="text-center">
                                            <h1 class="h4 mb-4 login-register-heading">Login</h1>
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
                                            <button class="btn btn-primary btn-user btn-block">Login</button>
                                            <hr>
                                            <a href="{{ route('google.login') }}" class="btn btn-google btn-user btn-block">
                                                <i class="fab fa-google fa-fw"></i> Login with Google
                                            </a>
                                        </form>
                                        <hr>

                                        <!-- Register -->
                                        <div class="text-center">
                                            <h1 class="h4 mb-4 login-register-heading">Register</h1>
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
                                            <button class="btn btn-success btn-user btn-block">Register</button>
                                              <hr>
                                            <a href="{{ route('google.login') }}" class="btn btn-google btn-user btn-block">
                                                <i class="fab fa-google fa-fw"></i> Sign in with Google
                                            </a>
                                        </form>
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

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>

</body>
</html>
