<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        auth()->user()->following()->syncWithoutDetaching([$user->id]);

        return back()->with('success', 'You are now following ' . $user->name);
    }

    public function unfollow(User $user)
    {
        auth()->user()->following()->detach($user->id);

        return back()->with('success', 'You unfollowed ' . $user->name);
    }


    public function isFollowing(User $user)
    {
        return auth()->user()->following()->where('followed_id', $user->id)->exists();
    }

    // API routes
    public function apiFollow(User $user)
    {
        $me = auth()->user();
        $me->following()->syncWithoutDetaching([$user->id]);

        return response()->json([
            'success' => true,
            'message' => 'You are now following ' . $user->name,
            'following' => true,
            'user_id' => $user->id
        ]);
    }

    public function apiUnfollow(User $user)
    {
        $me = auth()->user();
        $me->following()->detach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'You unfollowed ' . $user->name,
            'following' => false,
            'user_id' => $user->id
        ]);
    }

    public function apiIsFollowing(User $user)
    {
        $me = auth()->user();
        $isFollowing = $me->following()->where('followed_id', $user->id)->exists();

        return response()->json([
            'success' => true,
            'following' => $isFollowing,
            'user_id' => $user->id
        ]);
    }
}