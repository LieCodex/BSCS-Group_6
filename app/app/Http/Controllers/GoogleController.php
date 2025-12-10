<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Google_Client;


class GoogleController extends Controller
{
        // Redirect to Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle callback from Google
    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Find or create user
        $user = User::updateOrCreate(
    ['email' => $googleUser->getEmail()],
    [
        'name' => $googleUser->getName(),
        'google_id' => $googleUser->getId(),
        'avatar' => $googleUser->getAvatar(),
        'password' => bcrypt(str()->random(16)), // random password
    ]
);
        // Log in the user
        Auth::login($user, true);

        return redirect('/'); // Change to your desired route
    }



    public function apigoogleLogin(Request $request)
{
    $request->validate([
        'id_token' => 'required'
    ]);

    $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
    $payload = $client->verifyIdToken($request->id_token);

    if (!$payload) {
        return response()->json(['error' => 'Invalid Google token'], 401);
    }

    // Extract user info
    $email = $payload['email'];
    $name = $payload['name'] ?? null;
    $avatar = $payload['picture'] ?? null;
    $googleId = $payload['sub'];

    // Create or update user
    $user = User::updateOrCreate(
        ['email' => $email],
        [
            'name' => $name,
            'google_id' => $googleId,
            'avatar' => $avatar,
            'password' => bcrypt(str()->random(16))
        ]
    );

    // Create Sanctum token for the app
    $token = $user->createToken('mobile')->plainTextToken;

    return response()->json([
        'success' => true,
        'token' => $token,
        'user' => $user
    ]);
}

}


