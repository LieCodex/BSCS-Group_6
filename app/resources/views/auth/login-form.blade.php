<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Squeal</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
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

          <!-- Right Side Login -->
          <div class="p-8">
            <div class="text-center">
              <h1 class="text-2xl font-semibold mb-6 text-orange-400">Sign In</h1>
            </div>
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
              @csrf
              <div>
                <input name="loginemail" type="email"
                  class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none text-white placeholder-gray-400"
                  placeholder="Email" required>
              </div>
              <div>
                <input name="loginpassword" type="password"
                  class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none text-white placeholder-gray-400"
                  placeholder="Password" required>
              </div>
              <button
                class="w-full bg-orange-500 text-white py-2 rounded-lg shadow-md hover:bg-orange-600 transition mt-4">
                Login
              </button>
              <div class="flex items-center my-6">
                <hr class="flex-grow border-gray-700">
                <span class="mx-3 text-gray-400">OR</span>
                <hr class="flex-grow border-gray-700">
              </div>
              <a href="{{ route('google.login') }}"
                class="flex items-center justify-center w-full bg-orange-500 text-white py-2 rounded-lg shadow-md hover:bg-orange-600 transition">
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
