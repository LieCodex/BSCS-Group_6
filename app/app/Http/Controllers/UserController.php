<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller 
{
    public function register(Request $request){
        $incomingFields = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email'),
                function ($attribute, $value, $fail) {
                $allowed = ['@gmail.com', '@yahoo.com', '@usm.edu.ph'];
                $valid = false;
                foreach ($allowed as $domain) {
                    if (str_ends_with($value, $domain)) {
                        $valid = true;
                        break;
                    }
                }
                if (!$valid) {
                    $fail('Only Gmail or Yahoo addresses are allowed.');
                }
            }],
            'password' =>['required', 'min:8', 'max:30']
        ]);
                                        
        $incomingFields['password'] = Hash::make($incomingFields['password']);
        $user = User::create($incomingFields);
        auth()->login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard.home');
        
    }

    public function logout(){
        auth()->logout();
        return redirect('/');
    }

    public function login(Request $request){
    $incomingFields = $request->validate([
        'loginemail'=> 'required',
        'loginpassword'=> 'required'
    ]);

    $remember = $request->has('remember');

    if(auth()->attempt(['email'=> $incomingFields['loginemail'], 'password'=> $incomingFields['loginpassword']], $remember)){
        $request->session()->regenerate();
    }
          return redirect()->route('dashboard.home');
    }

   public function updateProfile(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string|max:1000',
            // 'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = auth()->user();

        // Update bio
        $user->bio = $request->input('bio');

        // Update avatar if uploaded
        // if ($request->hasFile('avatar')) {
        //     $avatarPath = $request->file('avatar')->store('avatars', 'public');
        //     $user->avatar = $avatarPath;
        // }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

}
