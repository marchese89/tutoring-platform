@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Esercizio'" />
@endsection

@section('inner')
    <x-ui.page-section>
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
                        Esercizio
                    </span>

                    <h5 class="fw-semibold mb-0">
                        {{ $esercizio->title }}
                    </h5>
                </div>
            </div>
        </x-ui.card>

        <div class="mt-4">
            <x-ui.card>
                <h4 class="fw-bold mb-3">
                    Traccia
                </h4>

                <div class="ratio ratio-16x9 rounded-4 overflow-hidden border bg-light">
                    <iframe src="/protected-files/{{ $esercizio->trace }}#view=FitH"></iframe>
                </div>
            </x-ui.card>
        </div>

        <div class="mt-4">
            <x-ui.card>
                <h4 class="fw-bold mb-3">
                    Svolgimento
                </h4>

                <div class="ratio ratio-16x9 rounded-4 overflow-hidden border bg-light">
                    <iframe src="/protected-files/{{ $esercizio->execution }}#view=FitH"></iframe>
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
                            Scrivi qui per ricevere supporto sull'esercizio.
                        </p>
                    </div>
                </div>

                <div id="messaggi" class="d-flex flex-column gap-3 mb-4">
                    @foreach ($messaggi as $item)
                        <div class="d-flex {{ $item['is_teacher'] ? 'justify-content-start' : 'justify-content-end' }}">
                            <div
                                class="w-75 p-3 rounded-4 shadow-sm {{ $item['is_teacher'] ? 'bg-light' : 'bg-primary text-white' }}">
                                <p class="fw-semibold mb-1">
                                    {{ $item['sender'] }}
                                </p>

                                <p class="mb-2">
                                    {{ $item['message'] }}
                                </p>

                                <small class="{{ $item['is_teacher'] ? 'text-muted' : 'text-white-50' }}">
                                    {{ $item['date'] }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>
                    <textarea id="messaggio" name="messaggio" rows="4" class="form-control rounded-4 mb-3"
                        placeholder="Scrivi un messaggio..."></textarea>

                    <div class="text-end">
                        <x-ui.primary-button id="invia" type="button"
                            onclick="sendMessage(document.getElementById('messaggio').value)">
                            Invia
                        </x-ui.primary-button>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </x-ui.page-section>

    <script>
        function sendMessage(testo) {
            const input = document.getElementById("messaggio");

            input.value = "";

            if (!testo || testo.trim() === "") {
                return;
            }

            fetch("{{ route('student.chat.messages.store') }}", {
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
                <div class="w-75 p-3 rounded-4 shadow-sm ${isStudent ? 'bg-primary text-white' : 'bg-light'}">
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
                console.error("Echo non disponibile");
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
