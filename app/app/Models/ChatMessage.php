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
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
    public function reeiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
