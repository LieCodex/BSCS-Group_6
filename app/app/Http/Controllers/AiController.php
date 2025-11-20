<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Events\MessageSent;

class AiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'reply' => 'required|string',
            'ai_id' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id',
        ]);

         $message = ChatMessage::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => $request->ai_id,
            'receiver_id' => $request->user_id,
            'message' => $request->reply,
            'is_ai' => true,
        ]);

        broadcast(new MessageSent($message));
        return response()->json(['status' => 'ok']);
    }
}
