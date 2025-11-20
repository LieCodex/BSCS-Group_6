<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;


class Chat extends Component
{
    use WithFileUploads;
    public $users;
    public $selectedUser;
    public $newMessage;
    public $messages;
    public $authId;
    public $loginID;
    public $image;
    public $unread = []; // user_id => true
    protected $listeners = [
    'heartbeat' => 'heartbeat',
    ];
    
    public function heartbeat()
    {
        if (auth()->check()) {
            auth()->user()->forceFill(['last_seen_at' => now()])->save();
            Log::debug('UpdateLastSeen (heartbeat) for user ' . auth()->id());
        }
    }
    public function mount(){
        $this->authId = auth()->id();
        $this->loginID = $this->authId;

        if (auth()->check()) {
            auth()->user()->forceFill(['last_seen_at' => now()])->save();
        }

        $this->loadUsers();

        $selectedUserId = request('user_id'); // from profile link
        $this->selectedUser = $selectedUserId
            ? User::find($selectedUserId)  // could be a new user with no messages
            : $this->users->first() ?? null;

        if ($this->selectedUser) {
            $this->loadMessages(); // returns empty collection if no messages
        } else {
            $this->messages = collect();
        }

    }

    protected function loadUsers()
    {
        $authId = auth()->id();

        $this->users = User::where('id', '!=', $authId)
            ->where(function($q) use ($authId) {
                $q->whereHas('sentMessages', function($q) use ($authId) {
                    $q->where('receiver_id', $authId);
                })
                ->orWhereHas('receivedMessages', function($q) use ($authId) {
                    $q->where('sender_id', $authId);
                });
            })
            ->get()
            ->sortByDesc(fn($user) => optional($user->lastMessageWithAuth())->created_at)
            ->values();
    }
    public function render()
    {
        
       return view('livewire.chat'); 
    }

    public function loadMessages()
    {
        if (!$this->selectedUser) {
            $this->messages = collect();
            return;
        }

        $this->messages = ChatMessage::where(function($query){
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $this->selectedUser->id);
        })->orWhere(function($query){
            $query->where('sender_id', $this->selectedUser->id)
                ->where('receiver_id', auth()->id());
        })->get();

        $this->dispatch('chatChanged');
    }

public function submit()
{
    if (!$this->newMessage && !$this->image) return;

    $imageUrl = null;

    // Only upload when sending
    if ($this->image) {
        $file = $this->image;

        if ($file->isValid()) {
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = Str::random(12) . '_' . time() . '.' . $extension;

            // Upload to the same folder as post images
            $path = Storage::disk('spaces')->putFileAs(
                'post_images', // same as posts
                $file,
                $filename,
                ['visibility' => 'public']
            );

            $imageUrl = Storage::disk('spaces')->url($path);
        }
    }

    // Ensure conversation exists
    $conversation = \App\Models\Conversation::firstOrCreate([
        'user_id' => $this->selectedUser->id
    ]);

    // Save the message to DB
    $message = ChatMessage::create([
        'sender_id'       => auth()->id(),
        'receiver_id'     => $this->selectedUser->id,
        'message'         => $this->newMessage,
        'image_path'      => $imageUrl,
        'conversation_id' => $conversation->id,
        'is_ai'           => false,
    ]);

    // Reset form
    $this->messages->push($message);

    
    try {
        \Illuminate\Support\Facades\Http::post(
            'http://localhost:5678/webhook/ai-input',
            [
                'message_id'      => $message->id,
                'message'         => $message->message,
                'sender_id'       => auth()->id(),
                'receiver_id'     => $this->selectedUser->id,
                'conversation_id' => $message->conversation_id ?? null,
            ]
        );
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('AI webhook error: ' . $e->getMessage());
    }

    $this->newMessage = '';
    $this->image = null;

    broadcast(new MessageSent($message));
    $this->dispatch('messageSent');
    $this->loadUsers();
}
    public function getListeners()
    {
        return [
            "echo-private:chat.{$this->loginID},MessageSent" => 'newChatMessageNotification'
        ];
    }

    public function newChatMessageNotification($message)
    {
        $messageObj = ChatMessage::find($message['id']);

        // If the message came from the currently selected user, push to messages
        if ($message['sender_id'] == $this->selectedUser->id) {
            $this->messages->push($messageObj);
        }else {
        // Mark as unread if not current chat
        $this->unread[$message['sender_id']] = true;
    }

        // Always reload/sort user list, so sender jumps to top
        $this->loadUsers();
    }

    public function selectUser($id){
    $this->selectedUser = User::find($id);

    // If user has previous messages, load them; otherwise, empty collection
    $this->messages = $this->selectedUser
            ? ChatMessage::where(function($query){
                    $query->where('sender_id', auth()->id())
                        ->where('receiver_id', $this->selectedUser->id);
                })->orWhere(function($query){
                    $query->where('sender_id', $this->selectedUser->id)
                        ->where('receiver_id', auth()->id());
                })->get()
            : collect();

        unset($this->unread[$id]);
    }
}
