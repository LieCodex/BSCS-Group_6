<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatApiController extends Controller
{
    /**
     * 1. Get List of Users (Chat Sidebar)
     * Replicates loadUsers() from Chat.php
     */
    public function getChatUsers()
    {
        $authId = auth()->id();

        // Get users who have exchanged messages with the current user
        $users = User::where('id', '!=', $authId)
            ->where(function($q) use ($authId) {
                $q->whereHas('sentMessages', function($q) use ($authId) {
                    $q->where('receiver_id', $authId);
                })
                ->orWhereHas('receivedMessages', function($q) use ($authId) {
                    $q->where('sender_id', $authId);
                });
            })
            ->get();

        // Format the data for Flutter
        $formattedUsers = $users->map(function($user) use ($authId) {
            $lastMessage = $user->lastMessageWithAuth();
            
            // Calculate unread count
            $unreadCount = ChatMessage::where('sender_id', $user->id)
                ->where('receiver_id', $authId)
                ->whereNull('read_at') // Assuming you add a read_at column later, or use your specific logic
                ->count();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ?: asset('assets/img/default-avatar.svg'),
                'is_online' => $user->isOnline(),
                'last_message' => $lastMessage ? $lastMessage->message : null,
                'last_message_time' => $lastMessage ? $lastMessage->created_at->diffForHumans() : null,
                'last_message_timestamp' => $lastMessage ? $lastMessage->created_at : null,
                'unread_count' => $unreadCount, // Useful for Flutter UI badges
            ];
        });

        // Sort by last message (newest first)
        $sortedUsers = $formattedUsers->sortByDesc('last_message_timestamp')->values();

        return response()->json([
            'success' => true,
            'data' => $sortedUsers
        ]);
    }

    /**
     * 2. Get Messages for a specific User
     * Replicates loadMessages() from Chat.php
     */
        public function getMessages($friendId)
            {
            $authId = auth()->id();

            $messages = ChatMessage::where(function($query) use ($authId, $friendId){
                $query->where('sender_id', $authId)
                    ->where('receiver_id', $friendId);
            })->orWhere(function($query) use ($authId, $friendId){
                $query->where('sender_id', $friendId)
                    ->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc') 
            ->get();

            // TRANSFORM: Add 'is_me' helper for Flutter
            $messages->transform(function($message) use ($authId) {
                $message->is_me = ($message->sender_id == $authId);
                return $message;
            });

            return response()->json([
                'success' => true,
                'data' => $messages
            ]);
        }

        
    

    /**
     * 3. Send Message
     * Replicates submit() from Chat.php
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'image' => 'nullable|file|image|max:10240', // Standard Multipart upload
        ]);

        $authId = auth()->id();
        $receiverId = $request->receiver_id;
        $imageUrl = null;

        // 1. Handle Image Upload (DigitalOcean Spaces)
        // Matches your PostController logic
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            if ($file->isValid()) {
                $extension = strtolower($file->getClientOriginalExtension());
                $filename = Str::random(12) . '_' . time() . '.' . $extension;

                // Upload to the same folder as posts, as per your Chat.php
                $path = Storage::disk('spaces')->putFileAs(
                    'post_images', 
                    $file, 
                    $filename, 
                    ['visibility' => 'public']
                );

                if ($path) {
                    $imageUrl = Storage::disk('spaces')->url($path);
                }
            }
        }

        // 2. Ensure conversation exists
        $conversation = Conversation::firstOrCreate([
            'user_id' => $receiverId
        ]);

        // 3. Save to DB
        $message = ChatMessage::create([
            'sender_id'       => $authId,
            'receiver_id'     => $receiverId,
            'message'         => $request->message,
            'image_path'      => $imageUrl,
            'conversation_id' => $conversation->id,
            'is_ai'           => false,
        ]);

        // 4. Trigger AI Webhook (Replicating your Livewire logic)
        // NOTE: In production, http://localhost usually refers to the server itself. 
        // Ensure this service is running where the API is hosted.
        try {
            Http::post('http://localhost:5678/webhook/ai-input', [
                'message_id'      => $message->id,
                'message'         => $message->message,
                'sender_id'       => $authId,
                'receiver_id'     => $receiverId,
                'conversation_id' => $message->conversation_id ?? null,
            ]);
        } catch (\Exception $e) {
            \Log::error('AI webhook error: ' . $e->getMessage());
        }

        // 5. Broadcast to Reverb (So the web updates instantly)
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Message sent',
            'data' => $message
        ]);
    }
}