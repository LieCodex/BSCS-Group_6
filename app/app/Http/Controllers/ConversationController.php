<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Conversation;
    use App\Models\ChatMessage;

    class ConversationController extends Controller
    {
        public function recentMessages($id)
        {

            $messages = ChatMessage::where('conversation_id', $id)
                ->latest()
                ->limit(10)

                ->get()
                ->reverse()
                ->map(function ($message) {
                    return [
                        'role' => $message->is_ai ? 'assistant' : 'user',
                        'content' => $message->message ?? '',
                    ];
                });

            return response()->json($messages);
        }
    }