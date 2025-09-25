<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;


class Chat extends Component
{
    public $users;
    public $selectedUser;
    public $newMessage;
    public $messages;
    public $authId;
    public $loginID;
    public $unread = []; // user_id => true
    protected $listeners = [
    'heartbeat' => 'heartbeat',
    ];
    
    public function heartbeat()
    {
        if (auth()->check()) {
            auth()->user()->forceFill(['last_seen_at' => now()])->save();
            \Log::debug('UpdateLastSeen (heartbeat) for user ' . auth()->id());
        }
    }
    public function mount(){
        $this->authId = auth()->id();
        $this->loginID = $this->authId;

        if (auth()->check()) {
            auth()->user()->forceFill(['last_seen_at' => now()])->save();
            \Log::info('UpdateLastSeen (mount) for user ' . auth()->id());
        }

        $this->loadUsers();


        
        $this->selectedUser = $this->users->first();
        if ($this->selectedUser) {
            $this->loadMessages();
        }

        

    }

    protected function loadUsers()
    {
        $authId = auth()->id();

        $this->users = User::where('id', '!=', $authId)
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
            $this->messages = ChatMessage::where(function($query){
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $this->selectedUser->id);
            })->orWhere(function($query){
                $query->where('sender_id', $this->selectedUser->id)
                    ->where('receiver_id', auth()->id());
            })->get();
            $this->dispatch('chatChanged');
    }

    public function submit(){
        if(!$this->newMessage) return;

        // Save the message to the database
        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedUser->id,
            'message' => $this->newMessage,
        ]);

        $this->messages->push($message);

        // Clear the input field
        $this->newMessage = '';
        


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
        $this->loadMessages();
        unset($this->unread[$id]);
    }
}
