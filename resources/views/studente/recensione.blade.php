@extends('layouts.dashboard-studente')

@section('page-title')
    <x-ui.section-header title="Recensione" />
@endsection

@section('inner')
    <style>
        .stars a {
            opacity: 20%;
            cursor: pointer;
        }

        .stars:hover a {
            opacity: 100%;
        }

        .stars a:hover {
            opacity: 100%;
        }

        .stars a:hover~a {
            opacity: 20%;
        }
    </style>
    <script>
        function invia_feefback(punteggio) {

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    _("stars").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("POST", "{{ route('student.feedback.store') }}", true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
            xmlhttp.send("punteggio=" + encodeURIComponent(punteggio));

        }


        function countChar(val) {
            var len = val.value.length;
            _("current").innerHTML = len;
        }


        function storeReview(testo) {

            _("recensione").value = "";
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    _("recensione").value = this.responseText;
                }
            };
            xmlhttp.open("POST", "{{ route('student.review.store') }}", true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
            xmlhttp.send("testo=" + encodeURIComponent(testo));

        }
    </script>
    </script>

    @php
        use App\Models\Feedback;
        $student_id = auth()->user()->student->id;
        $feedback = Feedback::where('student_id', '=', $student_id)->first();
        $f = 0;
        if ($feedback != null) {
            $f = $feedback->punteggio;
        }
        $id_stud = 0;
        $id_lez = 0;
    @endphp
    <div class="container" style="width: 70%;text-align:center">
        <h2>Valutazione</h2>
        <div class="stars" id="stars">
            <a <?php if($f > 0){?> style="opacity: 100%;" <?php }?> onclick="invia_feefback(1)">⭐</a>
            <a <?php if($f > 1){?> style="opacity: 100%;" <?php }?> onclick="invia_feefback(2)">⭐</a>
            <a <?php if($f > 2){?> style="opacity: 100%;" <?php }?> onclick="invia_feefback(3)">⭐</a>
            <a <?php if($f > 3){?> style="opacity: 100%;" <?php }?> onclick="invia_feefback(4)">⭐</a>
            <a <?php if($f > 4){?> style="opacity: 100%;" <?php }?> onclick="invia_feefback(5)">⭐</a>
        </div>
        <h2>Recensione</h2>

        <textarea id="recensione" name="recensione" rows="5" cols="100" maxlength="500" onkeyup="countChar(this)"
            style="width: 80%; font-size: 18px; resize: none; border-radius: 5px 5px 5px 5px"></textarea>
        <div id="the-count">
            <span id="current">0</span> <span id="maximum">/ 500</span>
        </div>
        <script type="text/javascript">
            var input = document.getElementById("recensione");

            //Execute a function when the user presses a key on the keyboard
            input.addEventListener("keypress", function(event) {
                // If the user presses the "Enter" key on the keyboard
                if (event.key === "Enter") {
                    // Cancel the default action, if needed
                    event.preventDefault();
                    // Trigger the button element with a click
                    _("storeReview").click();
                }
            });
        </script> <br>

        <button id="storeReview" class="btn btn-primary" onclick=storeReview(_("recensione").value)>Invia</button>
        <br>
    </div>
@endsection
