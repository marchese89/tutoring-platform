@extends('layouts.student-dashboard')

@section('inner')
    @if ($chat)
        <script type="text/javascript">
            setInterval(getMessages, 1000);

            function getMessages() {
                let xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        _("messaggi").innerHTML = this.responseText;
                    }
                };
                //aut=1 -> insegnante
                xmlhttp.open("GET", "{{ route('student.chats.messages.index', ['id_chat' => $chat->id]) }}", true);
                xmlhttp.send();
            }

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

                xmlhttp.send(
                    "id_chat={{ $chat->id }}&testo=" + encodeURIComponent(testo)
                );
            }
        </script>
    @endif
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.dashboard') }}">Dashboard</a>
        </li>
        @if ($richiesta->paid == 0)
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('student.direct-requests.index') }}">Richieste Dirette</a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('student.direct-requests.purchased') }}">Richieste Dirette</a>
            </li>
        @endif
    </ul>
    <div class="container" style="text-align: center">
        <h3>Richiesta Lezione: </h3>
        <h3 style="color: blue">{{ $richiesta->title }}</h3>
        <h4>Traccia</h4>

        <iframe width="90%" src="/protected-files/{{ $richiesta->trace }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        @if ($richiesta->price != null && $richiesta->price != 0 && $richiesta->paid == 0)
            <div class="col-md-12">
                <h5>Prezzo</h5>
                <label>{{ $richiesta->price }} &nbsp;<strong>&euro;</strong></label>
            </div>
            <br>
            <div class="col-12" style="text-align:center">
                <button type="submit" class="btn btn-primary"
                    onclick="location.href='{{ route('cart.items.store', ['id' => $richiesta->id, 'type' => 5]) }}'">Acquista</button>
            </div>
        @endif
        @if ($richiesta->paid == 1)
            <br>
            <br>
            <h4>Soluzione</h4>
            <iframe width="90%" src="/protected-files/{{ $richiesta->execution }}#view=FitH" height="800px">
            </iframe>
            <br>
            <br>

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
                    <button id="invia" class="btn btn-primary"
                        onclick=sendMessage(_("messaggio").value)>Invia</button>
                    <br>
                    <br>
                </div>
            </div>
        @endif
        <br>
        <br>
        <br>
    </div>
@endsection
