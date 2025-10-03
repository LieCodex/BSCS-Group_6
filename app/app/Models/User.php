<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'last_seen_at', // 👈 add this if missing
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }
    

    public function userPosts(){ // shows the posts that the user has made
        return $this->hasMany(Post::class, 'user_id');
    }
    

    // Users that this user follows
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }
    // Messages this user sent
    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    // Messages this user received
    public function receivedMessages()
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    public function messagesWithAuth()
    {
        $authId = auth()->id();
        return $this->hasMany(ChatMessage::class, 'sender_id')
            ->where('sender_id', $authId)
            ->orWhere('receiver_id', $authId);
    }

    //notification
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }


    // Users that follow this user
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class, 'sender_id')
            ->orWhere('receiver_id', $this->id)
            ->latestOfMany();
    }

    public function lastMessageWithAuth()
    {
        $authId = auth()->id();

        return ChatMessage::where(function ($q) use ($authId) {
                $q->where('sender_id', $authId)
                ->where('receiver_id', $this->id);
            })
            ->orWhere(function ($q) use ($authId) {
                $q->where('sender_id', $this->id)
                ->where('receiver_id', $authId);
            })
            ->latest()
            ->first();
    }
    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(2));
    }
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

}
