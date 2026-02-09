@extends('layouts.apps')

@section('content')
<section class="payment-section p-20">
    <div class="container container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <a href="{{ route('cart.checkout') }}" class="btn btn-danger py-18 rounded-8 text-sm text-muted mb-3 d-inline-block text-decoration-none mb-10">
                    ← Back to Cart
                </a>

                <div class="row g-0 shadow-lg rounded overflow-hidden p-20 justify-content-between">
                    {{-- LEFT COLUMN: Order Summary --}}
                    <div class="col-md-5 bg-light p-4 border-end"> 
                        <h6 class="fw-bold mb-4">Order Summary</h6> 

                        @php
                            $checkout = Session::get('stripe_checkout.checkout', []);
                            $cartItems = Cart::instance('cart')->content();
                        @endphp

                        <div class="mb-3">
                            @if($cartItems && $cartItems->count() > 0)
                                @foreach($cartItems as $item)
                                    @php
                                        $product = $item->model ?? null;
                                        $imagePath = ($product && $product->image)
                                            ? asset('uploads/products/thumbnails/' . $product->image)
                                            : asset('assets/images/placeholder.png');
                                    @endphp
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $imagePath }}" alt="{{ $product->name ?? 'Product' }}" class="rounded me-3" width="64" height="64" style="object-fit:cover;">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="fw-semibold">{{ $product->name ?? 'Product Name' }}</div>
                                                    <small class="text-muted">{{ $item->qty }} × 원{{ number_format($item->price) }}</small>
                                                </div>
                                                <div class="fw-semibold text-nowrap">원{{ number_format($item->price * $item->qty) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No items found in your cart.</p>
                            @endif
                        </div>

                        <hr>
                        <ul class="list-unstyled text-sm mb-3">
                            <li class="d-flex justify-content-between">
                                <span>Subtotal</span>
                                <span>원{{ number_format($checkout['subtotal'] ?? 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Tax</span> 
                                <span>원{{ number_format($checkout['tax'] ?? 0) }}</span> 
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Discount</span> 
                                <span class="text-success">- 원{{ number_format($checkout['discount'] ?? 0) }}</span> 
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Delivery Fee</span>
                                <span>{{ ($checkout['delivery_fee'] ?? 0) > 0 ? '원' . number_format($checkout['delivery_fee']) : 'Free' }}</span>
                            </li>
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-6">
                            <span>Total Payment</span>
                            <span class="text-main">원{{ number_format($checkout['total'] ?? 0) }}</span> 
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: Payment Form --}}
                    <div class="col-md-7 bg-white p-20">
                        <h4 class="mb-4 fw-bold">Complete Payment</h4>

                        <form action="{{ route('stripe.checkout') }}" method="POST" id="stripe-form">
                            @csrf
                            <input type="hidden" name="amount" value="{{ $checkout['total'] ?? 0 }}">
                            <input type="hidden" name="stripeToken" id="stripe-token">

                            {{-- Card Holder Name --}}
                            <div class="mb-3">
                                <input type="text" id="card-holder-name" class="form-control form-control-lg" placeholder="Card Holder Name" required>
                            </div>

                            {{-- Card Number --}}
                            <div class="mb-3 position-relative">
                                <div id="card-number-element" class="p-3 border rounded" style="min-height: 50px;"></div>
                                <div id="card-number-errors" role="alert" class="text-danger small mt-1"></div>
                            </div>

                            {{-- Expiry and CVC --}}
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div id="card-expiry-element" class="p-3 border rounded" style="min-height: 50px;"></div>
                                </div>
                                <div class="col-6">
                                    <div id="card-cvc-element" class="p-3 border rounded" style="min-height: 50px;"></div>
                                </div>
                            </div>

                            {{-- Save Card --}}
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" id="save-card-checkbox" name="save_card">
                                <label class="form-check-label" for="save-card-checkbox">
                                    Save my card for faster checkout
                                </label>
                            </div>

                            {{-- Pay Button --}}
                            <button type="submit" class="btn btn-success w-100 py-18 text-dark fw-bold">
                                <i class="fas fa-lock me-2"></i> PAY 원{{ number_format($checkout['total'] ?? 0) }}
                            </button>

                            <p class="text-center small mt-3">
                                By clicking the button you confirm to have accepted<br>
                                <a href="{{ route('home.privacy.policy') }}" class="text-decoration-none">Privacy Policy</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Stripe.js --}}
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stripe = Stripe("{{ env('STRIPE_PUBLISHABLE_KEY') }}");
    const elements = stripe.elements();

    const style = {
        base: { fontFamily: 'system-ui, sans-serif', fontSize: '18px', '::placeholder': { color: '#aab7c4' } },
        invalid: { color: '#fa755a', iconColor: '#fa755a' }
    };

    const cardNumber = elements.create('cardNumber', { style: style });
    cardNumber.mount('#card-number-element');

    const cardExpiry = elements.create('cardExpiry', { style: style });
    cardExpiry.mount('#card-expiry-element');

    const cardCvc = elements.create('cardCvc', { style: style });
    cardCvc.mount('#card-cvc-element');

    const form = document.getElementById('stripe-form');
    const cardHolderName = document.getElementById('card-holder-name');
    const cardErrors = document.getElementById('card-number-errors');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        stripe.createToken(cardNumber, { name: cardHolderName.value })
            .then(function(result) {
                if (result.error) {
                    cardErrors.textContent = result.error.message;
                    submitButton.disabled = false;
                } else {
                    document.getElementById('stripe-token').value = result.token.id;
                    form.submit();
                }
            });
    });
});
</script>
@endsection
