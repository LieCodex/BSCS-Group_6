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

    public function mount(){
        $this->users = User::whereNot('id', auth()->id())->latest()->get(); 
        $this->selectedUser = $this->users->first();
        $this->loadMessages();
        $this->authId = Auth::id();
        $this->loginID = Auth::id();

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
    }

    public function getListeners()
    {
        return [
            "echo-private:chat.{$this->loginID},MessageSent" => 'newChatMessageNotification'
        ];
    }

    public function newChatMessageNotification($message)
    {
        if($message['sender_id'] == $this->selectedUser->id){
            $messageObj = ChatMessage::find($message['id']);
            $this->messages->push($messageObj);

        }
    }

    public function selectUser($id){
        $this->selectedUser = User::find($id);
        $this->loadMessages();
    }
}
