<?php

namespace App\Events;

use App\Helpers\DateHelper;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;;

use App\Models\ChatMessage;

class MessageSent implements ShouldBroadcastNow
{
    public $message;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('chat.' . $this->message->chat_id);
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'chat_id' => $this->message->chat_id,
            'message' => $this->message->message,
            'author' => $this->message->sender_role,
            'sender_role' => $this->message->sender_role,
            'date' => DateHelper::format($this->message->sent_at),
        ];
    }
}
