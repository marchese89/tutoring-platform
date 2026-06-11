@once
    <style>
        .support-chat-log {
            max-height: 34rem;
            overflow-y: auto;
            padding: 1rem;
            background: #f0f2f5;
            border: 1px solid #e5e7eb;
            border-radius: 1.25rem;
        }

        .support-chat-bubble {
            max-width: min(78%, 44rem);
            border-radius: 1rem;
            color: #1f2937;
            padding: .75rem .875rem .5rem;
            position: relative;
            text-align: left;
        }

        .support-chat-bubble-own {
            background: #d9fdd3;
            border: 1px solid #c7efc1;
        }

        .support-chat-bubble-other {
            background: #ffffff;
            border: 1px solid #e5e7eb;
        }

        .support-chat-sender {
            color: #008069;
            font-size: .78rem;
            font-weight: 700;
            margin-bottom: .25rem;
        }

        .support-chat-message {
            font-size: .95rem;
            line-height: 1.45;
            margin-bottom: .25rem;
            overflow-wrap: anywhere;
            text-align: left;
            white-space: normal;
        }

        .support-chat-date {
            color: #667781;
            display: block;
            font-size: .72rem;
            line-height: 1;
            text-align: right;
        }

        .support-chat-empty {
            background: rgba(255, 255, 255, .72);
            border: 1px solid #e5e7eb;
            color: #667781;
        }

        @media (max-width: 575.98px) {
            .support-chat-bubble {
                max-width: 92%;
            }
        }

        .support-chat-icon {
            height: 3rem;
            width: 3rem;
            background: #e7f6ef;
            color: #008069;
        }

        .support-chat-send {
            align-items: center;
            background: #008069;
            border: 1px solid #008069;
            border-radius: 999px;
            color: #ffffff;
            display: inline-flex;
            font-weight: 700;
            gap: .45rem;
            padding: .45rem 1rem;
        }

        .support-chat-send:hover {
            background: #006d5b;
            border-color: #006d5b;
            color: #ffffff;
        }

        .support-chat-send:focus-visible {
            box-shadow: 0 0 0 .25rem rgba(0, 128, 105, .18);
            outline: 0;
        }

        .support-chat-input:focus {
            border-color: #008069;
            box-shadow: 0 0 0 .25rem rgba(0, 128, 105, .12);
        }
    </style>
@endonce

<x-ui.card>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">
        <div class="d-flex gap-3">
            <div
                class="support-chat-icon rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
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

        <span
            id="{{ $chatDomId }}-count"
            class="badge bg-light text-secondary border rounded-pill px-3 py-2"
            data-message-count="{{ $messageCount }}">
            {{ $messageCount }} {{ $messageCount === 1 ? 'messaggio' : 'messaggi' }}
        </span>
    </div>

    <div id="{{ $chatDomId }}-messages" class="support-chat-log d-flex flex-column gap-2 mb-4">
        @forelse ($preparedMessages as $item)
            <div class="d-flex {{ $item['is_own'] ? 'justify-content-end' : 'justify-content-start' }}">
                <div class="support-chat-bubble {{ $item['is_own'] ? 'support-chat-bubble-own' : 'support-chat-bubble-other' }}">
                    <div class="support-chat-sender">
                        {{ $item['sender'] }}
                    </div>

                    <p class="support-chat-message">
                        {!! nl2br(e($item['message'])) !!}
                    </p>

                    <span class="support-chat-date">
                        {{ $item['date'] }}
                    </span>
                </div>
            </div>
        @empty
            <div id="{{ $chatDomId }}-empty" class="support-chat-empty text-center rounded-4 p-4">
                Nessun messaggio presente.
            </div>
        @endforelse
    </div>

    <div>
        <label class="form-label fw-semibold" for="{{ $chatDomId }}-input">
            Messaggio
        </label>

        <textarea id="{{ $chatDomId }}-input" name="message" rows="4" class="support-chat-input form-control rounded-4 mb-3"
            placeholder="Scrivi un messaggio..."></textarea>

        <div class="d-flex justify-content-end">
            <button id="{{ $chatDomId }}-send" type="button" class="support-chat-send">
                <i class="bi bi-send"></i>
                Invia
            </button>
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

        function updateMessageCount() {
            const badge = document.getElementById(`${chatDomId}-count`);

            if (!badge) {
                return;
            }

            const currentCount = Number(badge.dataset.messageCount || 0) + 1;

            badge.dataset.messageCount = currentCount;
            badge.textContent = `${currentCount} ${currentCount === 1 ? 'messaggio' : 'messaggi'}`;
        }

        function appendMessage(msg) {
            const isOwnMessage = Number(msg.sender_role ?? msg.author) === Number(ownAuthor);
            const wrapper = document.createElement("div");
            const emptyState = document.getElementById(`${chatDomId}-empty`);

            if (emptyState) {
                emptyState.remove();
            }

            wrapper.className = `d-flex ${isOwnMessage ? 'justify-content-end' : 'justify-content-start'}`;
            wrapper.innerHTML = `
                <div class="support-chat-bubble ${isOwnMessage ? 'support-chat-bubble-own' : 'support-chat-bubble-other'}">
                    <div class="support-chat-sender">
                        ${isOwnMessage ? escapeHtml(ownSender) : escapeHtml(otherSender)}
                    </div>

                    <p class="support-chat-message">
                        ${messageHtml(msg.message)}
                    </p>

                    <span class="support-chat-date">
                        ${escapeHtml(msg.date ?? '')}
                    </span>
                </div>
            `;

            const container = document.getElementById(`${chatDomId}-messages`);

            container.appendChild(wrapper);
            container.scrollTop = container.scrollHeight;
            updateMessageCount();
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
                    chat_id: chatId,
                    message: text
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
                .private("chat." + chatId)
                .listen(".MessageSent", function(e) {
                    appendMessage(e);
                });
        });
    })();
</script>
