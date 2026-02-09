@extends('layouts.apps')
@section('content')
@php
    // compute numeric total for checks (remove commas if any)
    if (Session::has('checkout')) {
        $rawTotal = Session::get('checkout')['total']; // Use the total including delivery fee
    } elseif (Session::has('discounts')) {
        $rawTotal = Session::get('discounts')['subtotal'];
    } else {
        $rawTotal = Cart::instance('cart')->subtotal();
    }
    $totalNumeric = floatval(str_replace(',', '', $rawTotal));
@endphp
<div class="breadcrumb py-26 bg-main-50">
    <div class="container container-lg">
        <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
            <h6 class="mb-0">Checkout</h6>
            <ul class="flex-align gap-8 flex-wrap">
                <li class="text-sm">
                    <a href="{{ route('home.index') }}" class="text-gray-900 flex-align gap-8 hover-text-main-600">
                        <i class="ph ph-house"></i>
                        Home
                    </a>
                </li>
                <li class="flex-align">
                    <i class="ph ph-caret-right"></i>
                </li>
                <li class="text-sm text-main-600"> Checkout </li>
            </ul>
        </div>
    </div>
</div>
<section class="checkout py-80">
    <div class="container container-lg">
        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <h5 class="alert-heading">Please fix the following errors:</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-9 col-lg-8">
                <form name="checkout-form" action="{{route('cart.place.an.order')}}" class="pe-xl-5" method="POST">
                  @csrf
                    <div class="row gy-3">
                        @if($address)
                        {{-- Address Display & Edit Section --}}
                        <div class="col-12">
                            <div id="display-address-section">
                                <div class="my-40">
                                    <h2>Your Address</h2>
                                    <p class="text-gray-500 mb-24">Your order will be shipped to the address below. To ship to a different address, please edit the details.</p>
                                    <a href="#" id="edit-address-btn" class="btn btn-main py-18 w-50 rounded-8">Edit address</a>
                                </div>
                                <div class="row">
                                    {{-- Display existing address details --}}
                                    <div class="col-sm-6"><h6 class="text-lg mb-24">Name: <p class="text-gray-500">{{$address->name}}</p></h6></div>
                                    <div class="col-sm-6"><h6 class="text-lg mb-24">Zone: <p class="text-gray-500">{{$address->address}}</p></h6></div>
                                    <div class="col-sm-6"><h6 class="text-lg mb-24">District: <p class="text-gray-500">{{$address->locality}}</p></h6></div>
                                    <div class="col-sm-6"><h6 class="text-lg mb-24">City: <p class="text-gray-500">{{$address->city}}</p></h6></div>
                                    <div class="col-sm-6"><h6 class="text-lg mb-24">Province: <p class="text-gray-500">{{$address->state}}</p></h6></div>
                                    <div class="col-sm-6"><h6 class="text-lg mb-24">Landmark: <p class="text-gray-500">{{$address->landmark}}</p></h6></div>
                                    <div class="col-sm-6"><h6 class="text-lg mb-24">Zip Code: <p class="text-gray-500">{{$address->zip}}</p></h6></div>
                                    <div class="col-sm-6"><h6 class="text-lg mb-24">Contact: <p class="text-gray-500">{{$address->phone}}</p></h6></div>
                                </div>
                            </div>

                            {{-- Editable Address Form (Initially hidden if address exists) --}}
                            <div id="edit-address-section" style="display: none;">
                                <div class="my-40">
                                    <h2>Edit Address</h2>
                                    <p class="text-gray-500 mb-24">Update your shipping details below.</p>
                                </div>
                                <input type="text" class="common-input border-gray-100 mb-16" placeholder="Full name" name="name" value="{{$address->name}}">
                                <input type="text" class="common-input border-gray-100 mb-16" placeholder="Zone" name="address" value="{{$address->address}}">
                                <input type="text" class="common-input border-gray-100 mb-16" placeholder="District (e.g., Neighborhood)" name="locality" value="{{$address->locality}}">
                                <input type="text" class="common-input border-gray-100 mb-16" placeholder="City" name="city" value="{{$address->city}}">
                                <input type="text" class="common-input border-gray-100 mb-16" placeholder="Province" name="state" value="{{$address->state}}">
                                <input type="text" class="common-input border-gray-100 mb-16" placeholder="Landmark" name="landmark" value="{{$address->landmark}}">
                                <input type="text" class="common-input border-gray-100 mb-16" placeholder="Zip Code" name="zip" value="{{$address->zip}}">
                                <input type="text" class="common-input border-gray-100 mb-16" placeholder="phone" name="phone" value="{{$address->phone}}">
                            </div>
                        </div>
                        @else
                        {{-- New Address Form --}}
                        <div class="col-12">
                             <div class="my-40">
                                <h2>Shipping Address</h2>
                                <p class="text-gray-500 mb-24">Please enter your shipping details.</p>
                            </div>
                            <input type="text" class="common-input border-gray-100 mb-16" placeholder="Full name" name="name" value="{{old('name')}}">
                            <input type="text" class="common-input border-gray-100 mb-16" placeholder="Zone" name="address" value="{{old('address')}}">
                            <input type="text" class="common-input border-gray-100 mb-16" placeholder="District (e.g., Neighborhood)" name="locality" value="{{old('locality')}}">
                            <input type="text" class="common-input border-gray-100 mb-16" placeholder="City" name="city" value="{{old('city')}}">
                            <input type="text" class="common-input border-gray-100 mb-16" placeholder="Province" name="state" value="{{old('state')}}">
                            <input type="text" class="common-input border-gray-100 mb-16" placeholder="Landmark" name="landmark" value="{{old('landmark')}}">
                            <input type="text" class="common-input border-gray-100 mb-16" placeholder="Zip Code" name="zip" value="{{old('zip')}}">
                            <input type="text" class="common-input border-gray-100 mb-16" placeholder="phone" name="phone" value="{{old('phone')}}">
                        </div>
                        @endif
                    </div>
                
            </div>
            <div class="col-xl-3 col-lg-4">
                <div class="checkout-sidebar">
                    <div class="bg-color-three rounded-8 p-24 text-center">
                        <span class="text-gray-900 text-xl fw-semibold">Your Orders</span>
                    </div>

                    <div class="border border-gray-100 rounded-8 px-24 py-40 mt-24">
                        <div class="mb-32 pb-32 border-bottom border-gray-100 flex-between gap-8">
                            <span class="text-gray-900 fw-medium text-xl font-heading-two">Product</span>
                            <span class="text-gray-900 fw-medium text-xl font-heading-two">Subtotal</span>
                        </div>

                        @foreach($items as $item)
                        <div class="flex-between gap-24 mb-32">
                            <div class="flex-align gap-12">
                                <span class="text-gray-900 fw-normal text-md font-heading-two w-144">{{$item->name}}</span>
                                <span class="text-gray-900 fw-normal text-md font-heading-two"><i class="ph-bold ph-x"></i></span>
                                <span class="text-gray-900 fw-semibold text-md font-heading-two">{{$item->qty}}</span>
                            </div>
                            <span class="text-gray-900 fw-bold text-md font-heading-two">원{{$item->subtotal()}}</span>
                        </div>
                        @endforeach

                        <div class="border-top border-gray-100 pt-30  mt-30">
                          @php
                              // Get normalized checkout values, defaulting to cart subtotal if not set
                              $checkout = Session::get('checkout', ['discount' => '0.00', 'delivery_fee' => '0.00', 'total' => Cart::instance('cart')->subtotal()]);
                              $subtotalAfterCoupon = $checkout['subtotal'] ?? Cart::instance('cart')->subTotal();
                              $discount = $checkout['discount'];
                              $deliveryFee = $checkout['delivery_fee'];
                              $finalTotal = $checkout['total'];
                          @endphp
                          
                          @if(Session::has('discounts'))
                            <div class="mb-32 flex-between gap-8">
                                <span class="text-gray-900 font-heading-two text-xl fw-semibold">Discount: ({{Session::get('coupon')['code']}})</span>
                                <span class="text-gray-900 font-heading-two text-md fw-bold">원{{$discount}}</span>
                            </div>
                          @endif
                          
                          <div class="mb-32 flex-between gap-8">
                              <span class="text-gray-900 font-heading-two text-xl fw-semibold">Subtotal</span>
                              <span class="text-gray-900 font-heading-two text-md fw-bold">원{{$subtotalAfterCoupon}}</span>
                          </div>

                          {{-- NEW: Delivery Fee --}}
                          <div class="mb-32 flex-between gap-8">
                              <span class="text-gray-900 font-heading-two text-xl fw-semibold">Delivery Fee</span>
                              <span class="text-gray-900 font-heading-two text-md fw-bold">
                                  @if(floatval($deliveryFee) > 0)
                                      원{{ number_format(floatval($deliveryFee), 0) }}
                                  @else
                                      Free
                                  @endif
                              </span>
                          </div>
                          
                          {{-- Final Total --}}
                          <div class="mb-0 flex-between gap-8">
                              <span class="text-gray-900 font-heading-two text-xl fw-semibold">Total</span>
                              <span class="text-gray-900 font-heading-two text-md fw-bold">원{{ number_format(floatval($finalTotal), 0) }}</span>
                          </div>
                        </div>
                    </div>

                    <div class="mt-32">
                        <div class="payment-item">
                            <div class="form-check common-check common-radio py-16 mb-0">
                                <input class="form-check-input" type="radio" name="mode" id="mode" value="card" @if($totalNumeric < 100) disabled @endif required>
                                <label class="form-check-label fw-semibold text-neutral-600" for="mode">Card (Credit / Debit)</label>
                            </div>
                            @if($totalNumeric < 100)
                                <p class="text-danger mt-8">카드 결제는 최소 결제금액 100원 이상에서만 가능합니다. (Only card payments for 100원 and above)</p>
                            @endif
                            <div class="payment-item__content px-16 py-24 rounded-8 bg-main-50 position-relative">   
                                <p class="text-gray-800">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.</p>
                            </div>
                        </div>
                        <div class="payment-item">
                            <div class="form-check common-check common-radio py-16 mb-0">
                                <input class="form-check-input" type="radio" name="mode" id="payment2" value="paypal" required>
                                <label class="form-check-label fw-semibold text-neutral-600" for="payment2">Direct Bank</label>
                            </div>
                            <div class="payment-item__content px-16 py-24 rounded-8 bg-main-50 position-relative">   
                                <p class="text-gray-800">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.</p>
                            </div>
                        </div>
                        <div class="payment-item">
                            <div class="form-check common-check common-radio py-16 mb-0">
                                <input class="form-check-input" type="radio" name="mode" id="mode3" value="cod" required>
                                <label class="form-check-label fw-semibold text-neutral-600" for="mode3">Pick up</label>
                            </div>
                            <div class="payment-item__content px-16 py-24 rounded-8 bg-main-50 position-relative">   
                                <p class="text-gray-800">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-32 pt-32 border-top border-gray-100">
                        <p class="text-gray-500">Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="#" class="text-main-600 text-decoration-underline"> privacy policy</a> .</p>
                    </div>

                    <button type="submit" class="btn btn-main mt-40 py-18 w-100 rounded-8 mt-56">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editAddressBtn = document.getElementById('edit-address-btn');
        const displayAddressSection = document.getElementById('display-address-section');
        const editAddressSection = document.getElementById('edit-address-section');

        // If an address exists, the edit section is initially hidden.
        // This script reveals it on button click.
        if (editAddressBtn && editAddressSection) {
            // Pre-populate the form fields for editing
            editAddressSection.style.display = 'none';

            editAddressBtn.addEventListener('click', function(event) {
                event.preventDefault();
                if (displayAddressSection) {
                    displayAddressSection.style.display = 'none';
                }
                editAddressSection.style.display = 'block';
            });
        } else if (editAddressSection) {
            // If no address exists, the edit section should be visible by default.
            editAddressSection.style.display = 'block';
        }

        // client-side: prevent submitting card payment if total < 100
        const checkoutForm = document.querySelector('form[name="checkout-form"]');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function (e) {
                const selected = document.querySelector('input[name="mode"]:checked');
                const total = parseFloat('{{ $totalNumeric }}');
                // require a payment method
                if (!selected) {
                    e.preventDefault();
                    alert('Please select a payment method.');
                    return;
                }
                if (selected && selected.value === 'card' && total < 100) {
                    e.preventDefault();
                    alert('Card payments require a minimum amount of 100원.');
                }
            });
        }
    });
</script>
@endsection