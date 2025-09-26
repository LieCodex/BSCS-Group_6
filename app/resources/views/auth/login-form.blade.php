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

<body class="bg-gray-900 text-white">
  <div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-6xl">
      <div class="bg-gray-800 border border-gray-700 shadow-lg rounded-lg overflow-hidden my-8">
        <div class="grid grid-cols-1 lg:grid-cols-2">

          <!-- Left Side Logo -->
          <div class="hidden lg:flex flex-col items-center justify-center text-center p-8 bg-gray-900">
            <img src="assets/img/squel logo orange.png" alt="logo" class="w-48 h-auto">
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
                                            <div class="form-group mt-3">
                                                <input type="checkbox" name="remember" id="remember">
                                                <label for="remember">Remember Me</label>
                                            </div>
                                            <button class="btn btn-orange btn-user btn-block mt-4">Login</button>
                                            <hr>
                                            <a href="{{ route('google.login') }}" class="btn btn-orange btn-user btn-block">
                                                <img src="{{ asset('assets/img/GLogo.png') }}" alt="Google Logo" class="w-5 h-5 mr-2 inline-block">
                                                Sign in with Google
                                            </a>
                                        </form>

            <div class="text-center mt-4">
              <a href="{{ route('register.form') }}" class="text-orange-400 hover:underline">
                Don't have an account? Register
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

</body>
</html>
