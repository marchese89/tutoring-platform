@extends('layouts.student-dashboard')

@section('inner')
    <script type="text/javascript">
        function sendMessage(testo) {
            document.getElementById("messaggio").value = "";

            if (!testo || testo.trim() === "") {
                return;
            }

            let xmlhttp = new XMLHttpRequest();

            xmlhttp.open("POST", "{{ route('student.chat.messages.store') }}", true);

            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // CSRF Laravel (OBBLIGATORIO)
            xmlhttp.setRequestHeader(
                "X-CSRF-TOKEN",
                document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            );

            const params = new URLSearchParams();
            params.append("id_chat", "{{ $chat->id }}");
            params.append("testo", testo);

            xmlhttp.send(params.toString());
            console.log("Messaggio inviato: " + testo);
        }
    </script>
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.courses.index') }}">Corsi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.courses.show', $corso->id) }}">Corso</a>
        </li>
    </ul>
    <div class="container" style="text-align: center;width:100%">
        <h2>Modifica Esercizio Corso di</h2>
        <h2>"{{ $corso->name }}"</h2>
        <h3>Titolo Esercizio</h3>
        <h3>"{{ $esercizio->title }}"</h3>
        <br>

        <br>
        <h4>Traccia</h4>

        <iframe width="90%" src="/protected-files/{{ $esercizio->trace }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <h4>Svolgimento</h4>
        <iframe width="90%" src="/protected-files/{{ $esercizio->execution }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <div class="container" style="width: 70%;text-align:center">
            <h2>Chat di Supporto</h2>
            <br>
            <br>
            <div id="messaggi">
                @foreach ($messaggi as $item)
                    @if ($item['is_teacher'])
                        <div class="chat-message" style="justify-content: flex-start;">
                            <div class="message-content">
                                <p class="sender-name">Insegnante</p>
                                <p class="message-text">{{ $item['message'] }}</p>
                                <span class="timestamp">{{ $item['date'] }}</span>
                            </div>
                        </div>
                    @else
                        <div class="chat-message" style="justify-content: flex-end;">
                            <div class="message-content" style="background-color: #5755c559;">
                                <p class="sender-name">Tu</p>
                                <p class="message-text">{{ $item['message'] }}</p>
                                <span class="timestamp">{{ $item['date'] }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div style="text-align: center">
                <textarea id="messaggio" name="messaggio" rows="5" cols="100"
                    style="width: 80%; font-size: 18px; resize: none; border-radius: 5px 5px 5px 5px"></textarea>

                <script type="text/javascript">
                    var input = _("messaggio");

                    //Execute a function when the user presses a key on the keyboard
                    input.addEventListener("keypress", function(event) {
                        // If the user presses the "Enter" key on the keyboard
                        if (event.key === "Enter") {
                            // Cancel the default action, if needed
                            event.preventDefault();
                            // Trigger the button element with a click
                            _("invia").click();
                        }
                    });
                </script> <br>
                <button id="invia" class="btn btn-primary" onclick=sendMessage(_("messaggio").value)>Invia</button>
                <br>
                <br>

            </div>
        </div>
    </div>
    <script>
        function appendMessage(msg) {
            console.log("append message", msg);
            const html = `
        <div class="chat-message" style="justify-content: ${msg.author == 0 ? 'flex-end' : 'flex-start'};">
            <div class="message-content" style="${msg.author == 0 ? 'background-color: #5755c559;' : ''}">
                <p class="sender-name">${msg.author == 0 ? 'Tu' : 'Insegnante'}</p>
                <p class="message-text">${msg.message}</p>
                <span class="timestamp">${msg.date ?? ''}</span>
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
            console.log("Script da testare");
            const chatId = {{ $chat->id }};

            if (!window.Echo) {
                console.error("Echo NON è disponibile");
                return;
            }

            window.Echo.channel('chat.' + chatId)
                .listen('.MessageSent', function(e) {
                    appendMessage(e);
                });

        });
    </script>
@endsection
