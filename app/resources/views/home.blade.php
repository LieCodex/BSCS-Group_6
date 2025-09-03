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
                                            @if(auth()->user()->avatar)
                                                <img src="{{ auth()->user()->avatar }}" 
                                                    alt="{{ auth()->user()->name }}" 
                                                    class="rounded-circle mb-3" 
                                                    style="width:80px; height:80px; object-fit:cover;">
                                            @endif
                                            <h1 class="h4 mb-4">Welcome {{ auth()->user()->name }}!</h1>
                                        </div>


                                        
                                        <a href="/create-post" class="btn btn-primary mb-3">Create New Post</a>
                                        <hr>

                                    @foreach ($posts as $post)
                                        <div class="card post-card mb-3">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $post->title }}</h5>
                                                <p class="card-text">{{ $post->body }}</p>

                                                <!-- Display post images -->
                                                @if($post->images->count() > 0)
                                                    <div class="d-flex flex-wrap mb-2">
                                                @foreach($post->images as $img)
<button 
    type="button"
    class="img-thumbnail-btn"
    data-bs-toggle="modal" 
    data-bs-target="#imageModal"
    data-img="{{ asset('storage/' . $img->image_path) }}"
    style="border:none; padding:0; margin:5px; background:none;">
    <img src="{{ asset('storage/' . $img->image_path) }}" 
        alt="Post Image" 
        style="width:100px; height:100px; object-fit:cover;">
</button>
                                                @endforeach
                                                    </div>
                                                @endif

                                                <a href="/edit-post/{{$post->id}}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="/delete-post/{{$post->id}}" method="POST" style="display:inline;">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        // Toggle login/register
        var showRegister = document.getElementById("show-register");
        if (showRegister) {
            showRegister.addEventListener("click", function () {
                document.getElementById("login-section").style.display = "none";
                document.getElementById("register-section").style.display = "block";
            });
        }

        var showLogin = document.getElementById("show-login");
        if (showLogin) {
            showLogin.addEventListener("click", function () {
                document.getElementById("register-section").style.display = "none";
                document.getElementById("login-section").style.display = "block";
            });
        }
    </script>
    <script>
document.querySelectorAll('.img-thumbnail-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var imgSrc = btn.getAttribute('data-img');
        document.getElementById('modalFullImage').src = imgSrc;
    });
});
</script>

<!-- image modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="modalFullImage" src="" class="img-fluid" alt="Full Image">
      </div>
    </div>
  </div>
</div>

</body>
</html>
