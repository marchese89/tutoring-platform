@extends('layouts.dashboard-studente')

@section('page-title')
    <div class="container my-3">
        <h2>Visualizza Lezione </h2>
    </div>
@endsection

@section('inner')
    @php $enableEcho = true; @endphp

    <script type="text/javascript">
        function invia_messaggio(testo) {

            document.getElementById("messaggio").value = "";

            if (!testo || testo.trim() === "") {
                return;
            }

            let xmlhttp = new XMLHttpRequest();

            xmlhttp.open("POST", "/chat/studente/invia-messaggio", true);

            xmlhttp.setRequestHeader(
                "Content-Type",
                "application/x-www-form-urlencoded"
            );

            xmlhttp.setRequestHeader(
                "X-CSRF-TOKEN",
                document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            );

            const params = new URLSearchParams();

            params.append("id_chat", "{{ $chat->id }}");
            params.append("testo", testo);

            xmlhttp.send(params.toString());
        }
    </script>

    <div class="container" style="text-align:center;width:100%">

        <h2>Lezione Corso di</h2>
        <h2>"{{ $corso->name }}"</h2>

        <h3>Titolo Lezione</h3>
        <h3>"{{ $lezione->title }}"</h3>

        <br><br>

        <h4>Presentazione</h4>

        <iframe width="90%" height="800px" src="/protected_file/{{ $lezione->presentation }}#view=FitH">
        </iframe>

        <br><br>

        <h4>Svolgimento</h4>

        <iframe width="90%" height="800px" src="/protected_file/{{ $lezione->lesson }}#view=FitH">
        </iframe>

        <br><br>

        <div class="container" style="width:70%;text-align:center">

            <h2>Chat di Supporto</h2>

            <br><br>

            <div id="messaggi">

                @foreach ($messaggi as $item)
                    @if ($item->author == 1)
                        <div class="chat-message" style="justify-content:flex-start;">

                            <div class="message-content">

                                <p class="sender-name">
                                    Insegnante
                                </p>

                                <p class="message-text">
                                    {{ $item->message }}
                                </p>

                                <span class="timestamp">
                                    {{ \App\Helpers\DateHelper::format($item->date) }}
                                </span>

                            </div>

                        </div>
                    @else
                        <div class="chat-message" style="justify-content:flex-end;">

                            <div class="message-content" style="background-color:#5755c559;">

                                <p class="sender-name">
                                    Tu
                                </p>

                                <p class="message-text">
                                    {{ $item->message }}
                                </p>

                                <span class="timestamp">
                                    {{ \App\Helpers\DateHelper::format($item->date) }}
                                </span>

                            </div>

                        </div>
                    @endif
                @endforeach

            </div>

            <div style="text-align:center">

                <textarea id="messaggio" name="messaggio" rows="5" cols="100"
                    style="width:80%;
                           font-size:18px;
                           resize:none;
                           border-radius:5px">
                </textarea>

                <script type="text/javascript">
                    var input = _("messaggio");

                    input.addEventListener("keypress", function(event) {

                        if (event.key === "Enter") {

                            event.preventDefault();

                            _("invia").click();
                        }
                    });
                </script>

                <br>

                <button id="invia" class="btn btn-primary" onclick='invia_messaggio(_("messaggio").value)'>

                    Invia

                </button>

                <br><br>

            </div>

        </div>

        <script>
            function appendMessage(msg) {

                const html = `
                    <div class="chat-message"
                        style="justify-content:${msg.author == 0 ? 'flex-end' : 'flex-start'};">

                        <div class="message-content"
                            style="${msg.author == 0 ? 'background-color:#5755c559;' : ''}">

                            <p class="sender-name">
                                ${msg.author == 0 ? 'Tu' : 'Insegnante'}
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const chatId = {{ $chat->id }};

                if (!window.Echo) {

                    console.error("Echo NON disponibile");

                    return;
                }

                window.Echo
                    .channel('chat.' + chatId)
                    .listen('.MessageSent', function(e) {

                        appendMessage(e);

                    });

            });
        </script>

    </div>
@endsection
