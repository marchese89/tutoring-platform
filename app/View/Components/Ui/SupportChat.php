<?php

namespace App\View\Components\Ui;

use App\Enums\ChatSenderRole;
use App\Helpers\DateHelper;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SupportChat extends Component
{
    public readonly string $chatDomId;

    public readonly int $messageCount;

    public readonly string $postUrl;

    public readonly array $preparedMessages;

    public function __construct(
        public readonly Chat $chat,
        iterable $messages,
        public readonly string $title = 'Chat di supporto',
        public readonly string $description = 'Scrivi qui per ricevere supporto.',
        ?string $postRoute = null,
        public readonly int $ownAuthor = ChatSenderRole::STUDENT->value,
        public readonly string $ownSender = 'Tu',
        public readonly string $otherSender = 'Insegnante',
    ) {
        $this->chatDomId = 'support-chat-'.$chat->id;
        $this->postUrl = $postRoute ?? route('student.chat.messages.store');
        $this->preparedMessages = collect($messages)
            ->map(fn (ChatMessage $message) => $this->prepareMessage($message))
            ->all();
        $this->messageCount = count($this->preparedMessages);
    }

    public function render(): View
    {
        return view('components.ui.support-chat');
    }

    private function prepareMessage(ChatMessage $message): array
    {
        $isOwnMessage = (int) $message->sender_role === $this->ownAuthor;

        return [
            'is_own' => $isOwnMessage,
            'sender' => $isOwnMessage ? $this->ownSender : $this->otherSender,
            'date' => DateHelper::format($message->sent_at),
            'message' => $message->message,
        ];
    }
}
