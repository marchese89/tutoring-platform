@props(['formattedTotal', 'stripeKey', 'intentUrl', 'returnUrl', 'backUrl'])

<x-ui.card>
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-2">
            Pagamento sicuro
        </h4>

        <p class="text-muted mb-0">
            Paga
            <span class="fw-bold text-success">
                {{ $formattedTotal }}&euro;
            </span>
            tramite Stripe.
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-3">
            {{ session('success') }}
        </div>
    @endif

    <form id="payment-form" data-stripe-key="{{ $stripeKey }}" data-intent-url="{{ $intentUrl }}"
        data-return-url="{{ $returnUrl }}">

        <div id="payment-element"></div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4">
            <x-ui.primary-button href="{{ $backUrl }}" size="sm">
                Indietro
            </x-ui.primary-button>

            <x-ui.primary-button id="submit" type="submit" disabled>
                <span id="spinner" class="spinner-border spinner-border-sm d-none" aria-hidden="true">
                </span>

                <span id="button-text">
                    Paga adesso
                </span>
            </x-ui.primary-button>
        </div>

        <div id="payment-message" class="alert alert-danger rounded-3 mt-4 d-none" role="alert">
        </div>
    </form>
</x-ui.card>

@once
    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>

        <script>
            document.addEventListener('DOMContentLoaded', async () => {
                const form = document.getElementById('payment-form');

                if (!form) {
                    return;
                }

                const submitButton = document.getElementById('submit');
                const spinner = document.getElementById('spinner');
                const buttonText = document.getElementById('button-text');
                const message = document.getElementById('payment-message');
                const stripe = Stripe(form.dataset.stripeKey);

                let elements;

                function showMessage(text) {
                    message.textContent = text;
                    message.classList.remove('d-none');
                }

                function setLoading(loading) {
                    submitButton.disabled = loading;
                    spinner.classList.toggle('d-none', !loading);
                    buttonText.classList.toggle('d-none', loading);
                }

                try {
                    const response = await fetch(form.dataset.intentUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                        },
                        body: JSON.stringify({}),
                    });

                    const data = await response.json();

                    if (!response.ok || !data.clientSecret) {
                        showMessage(data.message ?? 'Impossibile inizializzare il pagamento.');
                        return;
                    }

                    elements = stripe.elements({
                        clientSecret: data.clientSecret,
                    });

                    elements.create('payment').mount('#payment-element');
                    submitButton.disabled = false;
                } catch (error) {
                    showMessage('Impossibile inizializzare il pagamento.');
                    return;
                }

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    setLoading(true);

                    const result = await stripe.confirmPayment({
                        elements,
                        confirmParams: {
                            return_url: form.dataset.returnUrl,
                        },
                    });

                    if (result.error) {
                        showMessage(result.error.message ??
                            'Si è verificato un errore durante il pagamento.');
                        setLoading(false);
                    }
                });
            });
        </script>
    @endpush
@endonce
