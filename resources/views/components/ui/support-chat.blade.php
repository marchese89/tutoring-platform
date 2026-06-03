@props([
    'chat',
    'messages',
    'title' => 'Chat di supporto',
    'description' => 'Scrivi qui per ricevere supporto.',
    'postRoute' => null,
    'ownAuthor' => 0,
    'ownSender' => 'Tu',
    'otherSender' => 'Insegnante',
])

@php
    $chatDomId = 'support-chat-' . $chat->id;
    $messageCount = count($messages);
    $postUrl = $postRoute ?? route('student.chat.messages.store');
@endphp

@once
    <style>
        .support-chat-log {
            max-height: 34rem;
            overflow-y: auto;
            padding-right: .25rem;
        }

        .support-chat-bubble {
            max-width: min(78%, 44rem);
        }

        .support-chat-message {
            overflow-wrap: anywhere;
            white-space: pre-wrap;
        }

        @media (max-width: 575.98px) {
            .support-chat-bubble {
                max-width: 92%;
            }
        }

        .support-chat-icon {
            height: 3rem;
            width: 3rem;
        }
    </style>
@endonce

<x-ui.card>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">
        <div class="d-flex gap-3">
            <div
                class="support-chat-icon rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0">
                <i class="bi bi-chat-dots fs-4"></i>
            </div>

            <div>
                <h4 class="fw-bold mb-1">
                    {{ $title }}
                </h4>

                <p class="text-muted mb-0">
                    {{ $description }}
                </p>
            </div>
        </div>

        <span class="badge bg-light text-secondary border rounded-pill px-3 py-2">
            {{ $messageCount }} {{ $messageCount === 1 ? 'messaggio' : 'messaggi' }}
        </span>
    </div>

    <div id="{{ $chatDomId }}-messages" class="support-chat-log d-flex flex-column gap-3 mb-4">
        @forelse ($messages as $item)
            @php
                $isArrayMessage = is_array($item);
                $isOwnMessage = $isArrayMessage
                    ? !($item['is_teacher'] ?? false)
                    : (int) $item->author === (int) $ownAuthor;
                $sender = $isArrayMessage
                    ? $item['sender']
                    : ($isOwnMessage ? $ownSender : $otherSender);
                $date = $isArrayMessage
                    ? $item['date']
                    : \App\Helpers\DateHelper::format($item->date);
                $message = $isArrayMessage ? $item['message'] : $item->message;
            @endphp

            <div class="d-flex {{ $isOwnMessage ? 'justify-content-end' : 'justify-content-start' }}">
                <div
                    class="support-chat-bubble p-3 rounded-4 shadow-sm {{ $isOwnMessage ? 'bg-primary text-white' : 'bg-light border' }}">
                    <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                        <p class="fw-semibold mb-0">
                            {{ $sender }}
                        </p>

                        <small class="{{ $isOwnMessage ? 'text-white-50' : 'text-muted' }}">
                            {{ $date }}
                        </small>
                    </div>

                    <p class="support-chat-message mb-0">
                        {!! nl2br(e($message)) !!}
                    </p>
                </div>
            </div>
        @empty
            <div id="{{ $chatDomId }}-empty" class="text-center text-muted border rounded-4 bg-light p-4">
                Nessun messaggio presente.
            </div>
        @endforelse
    </div>

    <div>
        <label class="form-label fw-semibold" for="{{ $chatDomId }}-input">
            Messaggio
        </label>

        <textarea id="{{ $chatDomId }}-input" name="messaggio" rows="4" class="form-control rounded-4 mb-3"
            placeholder="Scrivi un messaggio..."></textarea>

        <div class="d-flex justify-content-end">
            <x-ui.primary-button id="{{ $chatDomId }}-send" type="button" size="sm">
                <i class="bi bi-send me-2"></i>
                Invia
            </x-ui.primary-button>
        </div>
    </div>
</x-ui.card>

<script>
    (function() {
        const chatDomId = @json($chatDomId);
        const chatId = @json($chat->id);
        const postUrl = @json($postUrl);
        const ownAuthor = @json((int) $ownAuthor);
        const ownSender = @json($ownSender);
        const otherSender = @json($otherSender);

        function escapeHtml(value) {
            const div = document.createElement("div");

            div.textContent = value ?? "";

            return div.innerHTML;
        }

        function messageHtml(value) {
            return escapeHtml(value).replace(/\n/g, "<br>");
        }

        function appendMessage(msg) {
            const isOwnMessage = Number(msg.author) === Number(ownAuthor);
            const wrapper = document.createElement("div");
            const emptyState = document.getElementById(`${chatDomId}-empty`);

            if (emptyState) {
                emptyState.remove();
            }

            wrapper.className = `d-flex ${isOwnMessage ? 'justify-content-end' : 'justify-content-start'}`;
            wrapper.innerHTML = `
                <div class="support-chat-bubble p-3 rounded-4 shadow-sm ${isOwnMessage ? 'bg-primary text-white' : 'bg-light border'}">
                    <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                        <p class="fw-semibold mb-0">
                            ${isOwnMessage ? escapeHtml(ownSender) : escapeHtml(otherSender)}
                        </p>

                        <small class="${isOwnMessage ? 'text-white-50' : 'text-muted'}">
                            ${escapeHtml(msg.date ?? '')}
                        </small>
                    </div>

                    <p class="support-chat-message mb-0">
                        ${messageHtml(msg.message)}
                    </p>
                </div>
            `;

            const container = document.getElementById(`${chatDomId}-messages`);

            container.appendChild(wrapper);
            container.scrollTop = container.scrollHeight;
        }

        function sendMessage() {
            const input = document.getElementById(`${chatDomId}-input`);
            const text = input.value;

            input.value = "";

            if (!text || text.trim() === "") {
                return;
            }

            fetch(postUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: new URLSearchParams({
                    id_chat: chatId,
                    testo: text
                })
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById(`${chatDomId}-input`);
            const button = document.getElementById(`${chatDomId}-send`);
            const container = document.getElementById(`${chatDomId}-messages`);

            container.scrollTop = container.scrollHeight;

            input.addEventListener("keydown", function(event) {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault();

                    button.click();
                }
            });

            button.addEventListener("click", sendMessage);

            if (!window.Echo) {
                console.error("Echo non disponibile");
                return;
            }

            window.Echo
                .channel("chat." + chatId)
                .listen(".MessageSent", function(e) {
                    appendMessage(e);
                });
        });
    })();
</script>
