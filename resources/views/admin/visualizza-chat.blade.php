@extends('layouts.dashboard-admin')

@section('page-title')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2>Chat Con Studente</h2>
            </div>
        </div>
    </div>
@endsection

@section('inner')
    @php $enableEcho = true; @endphp

    @php

        use App\Helpers\DateHelper;

    @endphp

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .chat-box {
            max-height: 700px;
            overflow-y: auto;
        }

        .chat-message {
            display: flex;
            margin-bottom: 20px;
        }

        .message-content {
            max-width: 75%;
            padding: 14px;
            border-radius: 18px;
            background-color: #f1f3f5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .message-admin {
            background-color: #dbe4ff;
        }

        .sender-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .message-text {
            margin-bottom: 8px;
            white-space: pre-wrap;
        }

        .timestamp {
            font-size: 12px;
            color: gray;
        }

        iframe {
            border-radius: 18px;
            border: none;
        }
    </style>

    <script>
        function invia_messaggio(testo) {

            if (!testo || testo.trim() === "") {
                return;
            }

            document.getElementById("messaggio").value = "";

            const xhr = new XMLHttpRequest();

            xhr.open("POST", "/chat/admin/invia-messaggio", true);

            xhr.setRequestHeader(
                "Content-Type",
                "application/x-www-form-urlencoded"
            );

            xhr.setRequestHeader(
                "X-CSRF-TOKEN",
                document.querySelector('meta[name="csrf-token"]')
                .getAttribute("content")
            );

            const params = new URLSearchParams();

            params.append("id_chat", "{{ $chat->id }}");
            params.append("aut", 1);
            params.append("testo", testo);

            xhr.send(params.toString());
        }
    </script>

    <div class="container">

        {{-- INFO PRODOTTO --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 text-center">

                <h3 class="fw-bold mb-4">
                    {{ $titolo }}
                </h3>

                <h5 class="mb-3">
                    Presentazione
                </h5>

                <iframe width="100%" height="700px" src="/protected_file/{{ $pres }}#view=FitH">
                </iframe>

                <br><br><br>

                <h5 class="mb-3">
                    Svolgimento
                </h5>

                <iframe width="100%" height="700px" src="/protected_file/{{ $exec }}#view=FitH">
                </iframe>

            </div>
        </div>

        {{-- CHAT --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Conversazione
                </h4>

                <div id="messaggi" class="chat-box">

                    @foreach ($messaggi as $item)
                        @if ($item->author == 0)
                            <div class="chat-message justify-content-start">

                                <div class="message-content">

                                    <p class="sender-name">
                                        {{ $utente->name . ' ' . $utente->surname }}
                                    </p>

                                    <p class="message-text">
                                        {{ $item->message }}
                                    </p>

                                    <span class="timestamp">
                                        {{ DateHelper::format($item->date) }}
                                    </span>

                                </div>

                            </div>
                        @else
                            <div class="chat-message justify-content-end">

                                <div class="message-content message-admin">

                                    <p class="sender-name">
                                        Tu
                                    </p>

                                    <p class="message-text">
                                        {{ $item->message }}
                                    </p>

                                    <span class="timestamp">
                                        {{ DateHelper::format($item->date) }}
                                    </span>

                                </div>

                            </div>
                        @endif
                    @endforeach

                </div>

                <div class="mt-4 text-center">

                    <textarea id="messaggio" rows="5" class="form-control mb-3" style="resize:none"
                        placeholder="Scrivi un messaggio..."></textarea>

                    <button id="invia" class="btn btn-primary rounded-pill px-4"
                        onclick="invia_messaggio(document.getElementById('messaggio').value)">
                        Invia
                    </button>

                </div>

            </div>
        </div>

    </div>

    {{-- ENTER --}}
    <script>
        const input = document.getElementById("messaggio");

        input.addEventListener("keypress", function(event) {

            if (event.key === "Enter" && !event.shiftKey) {

                event.preventDefault();

                document.getElementById("invia").click();
            }
        });
    </script>

    {{-- APPEND MESSAGGI --}}
    <script>
        function appendMessage(msg) {

            const html = `

                <div class="chat-message ${msg.author == 1 ? 'justify-content-end' : 'justify-content-start'}">

                    <div class="message-content ${msg.author == 1 ? 'message-admin' : ''}">

                        <p class="sender-name">
                            ${msg.author == 1 ? 'Tu' : 'Studente'}
                        </p>

                        <p class="message-text">
                            ${msg.message}
                        </p>

                        <span class="timestamp">
                            ${msg.date ?? ''}
                        </span>

                    </div>

                </div>
            `;

            const container = document.getElementById("messaggi");

            container.insertAdjacentHTML('beforeend', html);

            container.scrollTop = container.scrollHeight;
        }
    </script>

    {{-- ECHO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            if (!window.Echo) {
                console.error("Echo non disponibile");
                return;
            }

            window.Echo
                .channel('chat.{{ $chat->id }}')
                .listen('.MessageSent', function(e) {

                    appendMessage(e);

                });

        });
    </script>
@endsection
