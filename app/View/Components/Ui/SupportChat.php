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

    public readonly string $title;

    public readonly string $description;

    public readonly string $ownSender;

    public readonly string $otherSender;

    public function __construct(
        public readonly Chat $chat,
        iterable $messages,
        ?string $title = null,
        ?string $description = null,
        ?string $postRoute = null,
        public readonly int $ownAuthor = ChatSenderRole::STUDENT->value,
        ?string $ownSender = null,
        ?string $otherSender = null,
    ) {
        $this->title = $title ?? __('ui.chat.title');
        $this->description = $description ?? __('ui.chat.description');
        $this->ownSender = $ownSender ?? __('ui.chat.you');
        $this->otherSender = $otherSender ?? __('ui.chat.teacher');
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
