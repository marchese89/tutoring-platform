@extends('layouts.dashboard-studente')

@section('page-title')
    <x-ui.section-header title="Lezione" />
@endsection

@section('inner')
    @php $enableEcho = true; @endphp

    <div class="container pb-5">

        <x-ui.card>
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">
                        Corso
                    </span>

                    <h3 class="fw-bold mb-0">
                        {{ $corso->name }}
                    </h3>
                </div>

                <div class="text-lg-end">
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 mb-2">
                        Lezione
                    </span>

                    <h5 class="fw-semibold mb-0">
                        {{ $lezione->title }}
                    </h5>
                </div>

            </div>
        </x-ui.card>

        <div class="mt-4">
            <x-ui.card>
                <h4 class="fw-bold mb-3">
                    Presentazione
                </h4>

                <div class="ratio ratio-16x9 rounded-4 overflow-hidden border bg-light">
                    <iframe src="/protected_file/{{ $lezione->presentation }}#view=FitH"></iframe>
                </div>
            </x-ui.card>
        </div>

        <div class="mt-4">
            <x-ui.card>
                <h4 class="fw-bold mb-3">
                    Svolgimento
                </h4>

                <div class="ratio ratio-16x9 rounded-4 overflow-hidden border bg-light">
                    <iframe src="/protected_file/{{ $lezione->lesson }}#view=FitH"></iframe>
                </div>
            </x-ui.card>
        </div>

        <div class="mt-4">
            <x-ui.card>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">
                            Chat di supporto
                        </h4>

                        <p class="text-muted mb-0">
                            Scrivi qui per ricevere supporto sulla lezione.
                        </p>
                    </div>
                </div>

                <div id="messaggi" class="d-flex flex-column gap-3 mb-4">

                    @foreach ($messaggi as $item)
                        @php
                            $isTeacher = $item->author == 1;
                        @endphp

                        <div class="d-flex {{ $isTeacher ? 'justify-content-start' : 'justify-content-end' }}">
                            <div class="p-3 rounded-4 shadow-sm {{ $isTeacher ? 'bg-light' : 'bg-primary text-white' }}"
                                style="max-width: 75%;">

                                <p class="fw-semibold mb-1">
                                    {{ $isTeacher ? 'Insegnante' : 'Tu' }}
                                </p>

                                <p class="mb-2">
                                    {{ $item->message }}
                                </p>

                                <small class="{{ $isTeacher ? 'text-muted' : 'text-white-50' }}">
                                    {{ \App\Helpers\DateHelper::format($item->date) }}
                                </small>
                            </div>
                        </div>
                    @endforeach

                </div>

                <div>
                    <textarea id="messaggio" name="messaggio" rows="4" class="form-control rounded-4 mb-3"
                        placeholder="Scrivi un messaggio..."></textarea>

                    <div class="text-end">
                        <button id="invia" type="button" class="btn btn-primary rounded-pill px-4"
                            onclick="invia_messaggio(document.getElementById('messaggio').value)">
                            Invia
                        </button>
                    </div>
                </div>
            </x-ui.card>
        </div>

    </div>

    <script>
        function invia_messaggio(testo) {
            const input = document.getElementById("messaggio");

            input.value = "";

            if (!testo || testo.trim() === "") {
                return;
            }

            fetch("/chat/studente/invia-messaggio", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: new URLSearchParams({
                    id_chat: "{{ $chat->id }}",
                    testo: testo
                })
            });
        }

        function escapeHtml(value) {
            const div = document.createElement("div");

            div.textContent = value ?? "";

            return div.innerHTML;
        }

        function appendMessage(msg) {
            const isStudent = msg.author == 0;

            const wrapper = document.createElement("div");

            wrapper.className = `d-flex ${isStudent ? 'justify-content-end' : 'justify-content-start'}`;

            wrapper.innerHTML = `
                <div class="p-3 rounded-4 shadow-sm ${isStudent ? 'bg-primary text-white' : 'bg-light'}" style="max-width:75%;">
                    <p class="fw-semibold mb-1">
                        ${isStudent ? 'Tu' : 'Insegnante'}
                    </p>

                    <p class="mb-2">
                        ${escapeHtml(msg.message)}
                    </p>

                    <small class="${isStudent ? 'text-white-50' : 'text-muted'}">
                        ${escapeHtml(msg.date ?? '')}
                    </small>
                </div>
            `;

            const container = document.getElementById("messaggi");

            container.appendChild(wrapper);

            container.scrollTop = container.scrollHeight;
        }

        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById("messaggio");

            input.addEventListener("keydown", function(event) {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault();

                    document.getElementById("invia").click();
                }
            });

            const chatId = {{ $chat->id }};

            if (!window.Echo) {
                console.error("Echo NON disponibile");
                return;
            }

            window.Echo
                .channel("chat." + chatId)
                .listen(".MessageSent", function(e) {
                    appendMessage(e);
                });
        });
    </script>
@endsection
