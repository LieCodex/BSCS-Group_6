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
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">

   <style>

   </style>

</head>

<body class="@guest guest-bg @else auth-bg @endguest">
    @guest
    <div class="welcome-text">
        SQUEAL
    </div>
    @endguest

    @auth
    <div id="logo-logout-container" class="dashboard-logo-container">
        <button id="logo-btn" class="dashboard-logo-btn">
            <img src="assets/img/squel logo orange.png" alt="logo" class="dashboard-logo-img">
        </button>
        <form id="logout-form" action="/logout" method="POST" class="dashboard-logout">
            @csrf
            <button class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>
    @endauth

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
                                        <!-- Add these navigation buttons -->
                                        <div class="mb-3">
                                            <a href="{{ url('/') }}" class="btn btn-outline-primary @if(request()->is('/')) active @endif">All Posts</a>
                                            <a href="{{ url('/my-posts') }}" class="btn btn-outline-secondary @if(request()->is('my-posts')) active @endif">My Posts</a>
                                        </div>
                                        <hr>
                                        
                                        <div class="text-center mt-5">
                                            @if(auth()->user()->avatar)
                                                <img src="{{ auth()->user()->avatar }}" 
                                                    alt="{{ auth()->user()->name }}" 
                                                    class="rounded-circle mb-3" 
                                                    style="width:80px; height:80px; object-fit:cover;">
                                            @endif
                                            <h1 class="h4 mb-4">Welcome {{ auth()->user()->name }}!</h1>
                                        </div> <!-- Creat Post -->
                                        <a href="/create-post" class="btn btn-primary mb-3">Create New Post</a>
                                        <hr>

                                        @foreach ($posts as $post)
                                            <x-post-card :post="$post" />
                                        @endforeach


                                        @else
                                        <!-- Log in form -->
                                        @include('login-form')

                                        <!-- Register Section (hidden by default) -->
                                        <div class="text-center">
                                            <a href ="{{url('/register-form')}}" class="toggle-link">Don't have an account? Register</a>
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
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    <script>
document.querySelectorAll('.img-thumbnail-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var imgSrc = btn.getAttribute('data-img');
        document.getElementById('modalFullImage').src = imgSrc;
    });
});

// Toggle logout button when logo is clicked
document.addEventListener('DOMContentLoaded', function() {
    var logoBtn = document.getElementById('logo-btn');
    var logoutForm = document.getElementById('logout-form');
    if (logoBtn && logoutForm) {
        logoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (logoutForm.style.display === 'none') {
                logoutForm.style.display = 'block';
            } else {
                logoutForm.style.display = 'none';
            }
        });
    }
});
</script>

<!-- image modal -->
<!-- Logout confirmation modal -->
<div class="modal fade modal-dashboard-bg" id="logoutConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 justify-content-center flex-column align-items-center">
                <img src="assets/img/crying-squirrel-with-bushy-tail-vector-58512631.png" alt="logo" class="modal-logo-img mb-2">
            </div>
            <div class="modal-body">
                <p>Are you sure you want to log out?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmLogoutBtn">Log Out</button>
            </div>
        </div>
    </div>
</div>
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
