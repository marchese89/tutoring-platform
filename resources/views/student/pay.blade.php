@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Acquista'" />
@endsection

@section('inner')
    <script src="https://js.stripe.com/v3/"></script>

    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.card>
                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-2">
                            Pagamento sicuro
                        </h4>

                        <p class="text-muted mb-0">
                            Paga
                            <span class="fw-bold text-success">
                                {{ number_format(session()->get('prezzo') * session()->get('qta'), 2, ',', '.') }}&euro;
                            </span>
                            tramite Stripe.
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success rounded-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="payment-form">
                        <div id="payment-element">
                            <!--Stripe.js injects the Payment Element-->
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4">
                            <x-ui.primary-button href="{{ route('payment.extra') }}" size="sm">
                                Indietro
                            </x-ui.primary-button>

                            <x-ui.primary-button id="submit" type="submit">
                                <span class="spinner hidden" id="spinner"></span>
                                <span id="button-text">Paga adesso</span>
                            </x-ui.primary-button>
                        </div>

                        <div id="payment-message" class="alert alert-danger rounded-3 mt-4 hidden"></div>
                    </form>
                </x-ui.card>
            </div>
        </div>
    </x-ui.page-section>

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
