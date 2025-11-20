<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Livewire\Chat;

class ChatMessage extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'image_path',
        'conversation_id',
        'is_ai',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
    
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
    
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    
    // Keep old function for backward compatibility
    public function reeiver()
    {
        return $this->receiver();
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
