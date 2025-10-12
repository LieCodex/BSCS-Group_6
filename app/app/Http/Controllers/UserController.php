<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\Rekognition\RekognitionClient;


class UserController extends Controller 
{

    // Show logged in user's profile
    public function Profile()
    {
        $user = auth()->user();
        $posts = Post::with(['user', 'images', 'comments'])
                    ->where('user_id', $user->id)
                    ->latest()
                    ->paginate(100); 
        return view('dashboard.profile', compact('user', 'posts'));
    }

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
    $user = auth()->user();

    $validated = $request->validate([
        'name'   => 'required|string|max:255',
        'bio'    => 'nullable|string|max:1000',
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:10240',
    ]);

    // Update name and bio first
    $user->name = $validated['name'];
    $user->bio  = $validated['bio'];

    // Handle avatar upload if present
    if ($request->hasFile('avatar')) {
        $file = $request->file('avatar');

        if ($file && $file->isValid()) {
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = Str::random(12) . '_' . time() . '.' . $extension;

            // Upload temporarily to Spaces
            $path = Storage::disk('spaces')->putFileAs(
                'avatars',
                $file,
                $filename,
                ['visibility' => 'public']
            );

            if ($path) {
                // 🧠 Run Rekognition for moderation (only if not a GIF)
                if ($extension !== 'gif') {
                    try {
                        $rekognition = new RekognitionClient([
                            'version' => 'latest',
                            'region' => config('filesystems.disks.s3.region'),
                            'credentials' => [
                                'key' => config('filesystems.disks.s3.key'),
                                'secret' => config('filesystems.disks.s3.secret'),
                            ],
                        ]);

                        $result = $rekognition->detectModerationLabels([
                            'Image' => [
                                'Bytes' => file_get_contents($file->getRealPath()),
                            ],
                            'MinConfidence' => 80,
                        ]);

                        $labels = $result['ModerationLabels'];
                        $flagged = false;

                        foreach ($labels as $label) {
                            if (in_array($label['Name'], [
                                'Explicit Nudity',
                                'Sexual Activity',
                                'Violence',
                                'Drugs'
                            ])) {
                                $flagged = true;
                                break;
                            }
                        }

                        // Delete unsafe avatar and abort
                        if ($flagged) {
                            Storage::disk('spaces')->delete($path);
                            return redirect()
                                ->back()
                                ->withInput()
                                ->with('error', 'Avatar rejected due to unsafe content.');
                        }
                    } catch (\Exception $e) {
                        // Log error but don't block profile update
                        \Log::error('Rekognition error: ' . $e->getMessage());
                    }
                }

                // Delete old avatar if exists
                if ($user->avatar) {
                    $parsedUrl = parse_url($user->avatar, PHP_URL_PATH);
                    $parsedUrl = ltrim($parsedUrl, '/');
                    $key = str_replace('squeal-spaces-file-storage/', '', $parsedUrl);
                    Storage::disk('spaces')->delete($key);
                }

                // Save new avatar URL
                $user->avatar = Storage::disk('spaces')->url($path);
            }
        }
    }

    $user->save();

    // Redirect to profile page
    return redirect()->route('user.profile', $user->id)
             ->with('success', 'Profile updated successfully!');
}

    public function show($id)
    {
        $user = User::findOrFail($id);
        $posts = Post::where('user_id', $id)->latest()->paginate(10);

        return view('dashboard.profile', compact('user', 'posts'));
    }


}
