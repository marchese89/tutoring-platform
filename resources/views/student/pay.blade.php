@extends('layouts.student-dashboard')

@section('inner')
    <script src="https://js.stripe.com/v3/"></script>


    <div class="container" style="text-align: center;width:35%">
        <h2>Acquista</h2>
    </div>
    <br>
    <div class="container" style="text-align: center;width:80%; height:500px">

        <h3>Paga <strong>{{ session()->get('prezzo') * session()->get('qta') }}&euro;</strong> in modo Sicuro tramite Stripe
        </h3>
        <br>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="container" style="text-align: center;width:35%">

            <br>
            <br>
            <form id="payment-form">
                <div id="payment-element">
                    <!--Stripe.js injects the Payment Element-->
                </div>
                <br>
                <br>
                <div class="container" style="text-align: center">
                    <button id="submit" class="btn btn-primary">
                        <div class="spinner hidden" id="spinner"></div>
                        <span id="button-text">Paga Adesso</span>
                    </button>
                </div>
                <div id="payment-message" class="hidden"></div>

            </form>
        </div>
    </div>
    <br>
    <br>
    <div class="container" style="text-align: center;width:35%">
        <button class="btn btn-primary" onclick="location.href='{{ route('payment.extra') }}'">Indietro</button>
    </div>
    <br>
    <br>
    </div>
    <script>
        // This is your test publishable API key.

        const stripe = Stripe(
            "{{ env('STRIPE_KEY') }}"
        );

        let elements;

        initialize();
        checkStatus();

        document
            .querySelector("#payment-form")
            .addEventListener("submit", handleSubmit);

        // Fetches a payment intent and captures the client secret
        async function initialize() {

            const {
                clientSecret
            } = await fetch("{{ route('payment.process.legacy') }}", {
                method: "GET",
                headers: {
                    "Content-Type": "application/json"
                },
            }).then((r) => r.json());

            elements = stripe.elements({
                clientSecret
            });

            const paymentElement = elements.create("payment");
            paymentElement.mount("#payment-element");
        }

        async function handleSubmit(e) {
            e.preventDefault();
            setLoading(true);

            const {
                error
            } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    // Make sure to change this to your payment completion page
                    return_url: "{{ route('payment.success') }}",
                },
            });

            // This point will only be reached if there is an immediate error when
            // confirming the payment. Otherwise, your customer will be redirected to
            // your `return_url`. For some payment methods like iDEAL, your customer will
            // be redirected to an intermediate site first to authorize the payment, then
            // redirected to the `return_url`.
            if (error.type === "card_error" || error.type === "validation_error") {
                showMessage(error.message);
            } else {
                showMessage("An unexpected error occurred.: " + error.type);
            }

            setLoading(false);
        }

        // Fetches the payment intent status after payment submission
        async function checkStatus() {
            const clientSecret = new URLSearchParams(window.location.search).get(
                "payment_intent_client_secret"
            );

            if (!clientSecret) {
                return;
            }

            const {
                paymentIntent
            } = await stripe.retrievePaymentIntent(clientSecret);

            switch (paymentIntent.status) {
                case "succeeded":
                    showMessage("Payment succeeded!");
                    break;
                case "processing":
                    showMessage("Your payment is processing.");
                    break;
                case "requires_payment_method":
                    showMessage("Your payment was not successful, please try again.");
                    break;
                default:
                    showMessage("Something went wrong.");
                    break;
            }
        }

        // ------- UI helpers -------

        function showMessage(messageText) {
            const messageContainer = document.querySelector("#payment-message");

            messageContainer.classList.remove("hidden");
            messageContainer.textContent = messageText;

            setTimeout(function() {
                messageContainer.classList.add("hidden");
                messageText.textContent = "";
            }, 4000);
        }

        // Show a spinner on payment submission
        function setLoading(isLoading) {
            if (isLoading) {
                // Disable the button and show a spinner
                document.querySelector("#submit").disabled = true;
                document.querySelector("#spinner").classList.remove("hidden");
                document.querySelector("#button-text").classList.add("hidden");
            } else {
                document.querySelector("#submit").disabled = false;
                document.querySelector("#spinner").classList.add("hidden");
                document.querySelector("#button-text").classList.remove("hidden");
            }
        }
    </script>
@endsection
