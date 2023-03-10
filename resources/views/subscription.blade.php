<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    <h2>サブスクリプション</h2>
                    <form id="setup-form" action="{{ route('subscribe.post') }}" method="post">
                        @csrf
                        <input id="card-holder-name" type="text" placeholder="カード名義人" name="card-holder-name">
                        <div id="card-element"></div>
                        <button id="card-button" data-secret="{{ $intent->client_secret }}">
                            サブスクリプション
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://js.stripe.com/v3"></script>
        <script>
            const stripe = Stripe('{{ config('services.stripe.pb_key') }}');

            const elements = stripe.elements();
            const cardElement = elements.create('card');
            cardElement.mount('#card-element');

            const cardHolderName = document.getElementById('card-holder-name');
            const cardButton = document.getElementById('card-button');
            const clientSecret = cardButton.dataset.secret;

            function stripePaymentIdHandler(paymentMethodId) {
                // Insert the paymentMethodId into the form so it gets submitted to the server
                const form = document.getElementById('setup-form');

                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'paymentMethodId');
                hiddenInput.setAttribute('value', paymentMethodId);
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            }

            cardButton.addEventListener('click', async (e) => {
                e.preventDefault()
                const { setupIntent, error } = await stripe.confirmCardSetup(
                    clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: { name: cardHolderName.value }
                        }
                    }
                );

                if (error) {
                    // Display "error.message" to the user...
                    console.log(error);
                } else {
                    // The card has been verified successfully...
                    stripePaymentIdHandler(setupIntent.payment_method);
                }
            });

            
        </script>
    @endpush
</x-app-layout>
