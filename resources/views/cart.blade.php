@extends('layouts.apps')
@section('content')
<div class="breadcrumb py-26 bg-main-50">
    <div class="container container-lg">
        <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
            <h6 class="mb-0">Cart</h6>
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
                <li class="text-sm text-main-600"> Cart </li>
            </ul>
        </div>
    </div>
</div>
<section class="cart py-80">
    <div class="container container-lg">
        <div class="row gy-4">
            <div class="col-xl-9 col-lg-8">
                {{-- Session Messages --}}
                @if (session('success'))
                    <div class="alert alert-success mb-24">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger mb-24">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="cart-table border border-gray-100 rounded-8 px-40 py-48">
                    <div class="overflow-x-auto scroll-sm scroll-sm-horizontal">
                      @if (Cart::instance('cart')->content()->count()>0)
                        <table class="table style-three" id="cart-table">
                            <thead>
                                <tr>
                                    <th class="h6 mb-0 text-lg fw-bold">Delete</th>
                                    <th class="h6 mb-0 text-lg fw-bold">Product Name</th>
                                    <th class="h6 mb-0 text-lg fw-bold">Price</th>
                                    <th class="h6 mb-0 text-lg fw-bold">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($items as $item)
                              <?php 
                                $product = $item->model;
                                $OutOfStock = ($product->quantity <= 0 || $product->stock_status === 'outstock'); 
                                $ratings = $item->model->reviews->pluck('rating')->toArray();

                                $medianRating = 0;

                                if (count($ratings) > 0) {
                                    sort($ratings); // sort ascending
                                    $count = count($ratings);
                                    $middle = floor(($count - 1) / 2);

                                    if ($count % 2) {
                                        // Odd count
                                        $medianRating = $ratings[$middle];
                                    } else {
                                        // Even count
                                        $lowMiddle = $ratings[$middle];
                                        $highMiddle = $ratings[$middle + 1];
                                        $medianRating = ($lowMiddle + $highMiddle) / 2;
                                    }
                                }
                                ?>
                                <tr>
                                    <td>
                                      <form method="POST" action="{{route('cart.item.remove',['rowId'=>$item->rowId])}}" id="remove-item-{{$item->id}}">
                                      @csrf
                                      @method('DELETE')
                                        <button type="submit" class="remove-tr-btn flex-align gap-12 hover-text-danger-600">
                                            <i class="ph ph-x-circle text-2xl d-flex"></i>
                                            Remove
                                        </button>
                                      </form> 
                                    </td>
                                    <td>
                                        <div class="table-product d-flex align-items-center gap-24">
                                            <a href="{{ route('shop.product.details', ['product_slug' => $item->model->slug]) }}" class="table-product__thumb border border-gray-100 rounded-8 flex-center ">
                                                <img src="{{asset('uploads/products/thumbnails')}}/{{$item->model->image}}" alt="">
                                            </a>
                                            <div class="table-product__content text-start">
    
                                                <h6 class="title text-lg fw-semibold mb-8">
                                                    <a href="{{ route('shop.product.details', ['product_slug' => $item->model->slug]) }}" class="link text-line-2" tabindex="0">{{$item->name}}</a>
                                                </h6>
    
                                                <div class="flex-align gap-16 mb-16">
                                                    <div class="flex-align gap-6">
                                                        <span class="text-md fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                                        <span class="text-md fw-semibold text-gray-900">{{ $medianRating }}</span>
                                                    </div>
                                                    <span class="text-sm fw-medium text-gray-200">|</span>
                                                    <span class="text-neutral-600 text-sm">{{ $item->model->reviews->count() }} Reviews</span>
                                                </div>
                                                @if($OutOfStock)
                                                    <span class="badge bg-danger text-white px-2 py-1 rounded-4">Out of stock</span>
                                                @else
                                                    <span class="text-white bg-success text-sm px-2 py-1 rounded-4">In stock</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-lg h6 mb-0 fw-semibold">원{{$item->price}}</span>
                                    </td>
                                    <td>
                                        {{-- MODIFIED QUANTITY SECTION: ONE FORM FOR UPDATE --}}
                                        <div class="d-flex rounded-4 overflow-hidden">
                                            <form method="POST" action="{{route('cart.qty.update',['rowId'=>$item->rowId])}}" class="qty-update-form d-flex " id="update-qty-{{$item->rowId}}">
                                            @csrf
                                            @method('PUT')
                                            
                                            {{-- DECREASE BUTTON: SUBMITS THE FORM WITH action=decrease --}}
                                            <button type="submit" name="action" value="decrease" class="qty-control__reduce quantity__minus border border-end border-gray-100 flex-shrink-0 h-48 w-48 text-neutral-600 flex-center hover-bg-main-600 hover-text-white">
                                                <i class="ph ph-minus"></i>
                                            </button>
                                            
                                            {{-- QTY INPUT: The value submitted when action is not present --}}
                                            <input type="number" name="qty" class="quantity__input flex-grow-1 border border-gray-100 border-start-0 border-end-0 text-center w-32 px-4" value="{{$item->qty}}" min="1">
                                            
                                            {{-- INCREASE BUTTON: SUBMITS THE FORM WITH action=increase --}}
                                            <button type="submit" name="action" value="increase" class="qty-control__increase quantity__plus border border-end border-gray-100 flex-shrink-0 h-48 w-48 text-neutral-600 flex-center hover-bg-main-600 hover-text-white">
                                                <i class="ph ph-plus"></i>
                                            </button>
                                            
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                              @endforeach 
                              @else
                              <h4 class="text-center">No items in cart</h4>
                              @endif 
                            </tbody>
                        </table>
                    </div>
                    <div class="flex-between flex-wrap gap-16 mt-16">
                    @if (Cart::instance('cart')->content()->count()>0)
                        <div class="flex-align gap-16">
                          @if(!Session::has('coupon'))
                          <form action="{{route('cart.coupon.apply')}}" method="POST">
                            @csrf
                            <input type="text" class="common-input mb-10" name="coupon_code" placeholder="Coupon Code">
                            <input type="submit" value="APPLY COUPON" class="btn btn-main py-18 w-100 rounded-8">
                          </form>
                          @else
                          <form action="{{route('cart.coupon.remove')}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="text" class="common-input mb-10" name="coupon_code" placeholder="Coupon Code" value="@if(Session::has('coupon')){{Session::get('coupon')['code']}} Applied! @endif">
                            <input type="submit" value="REMOVE COUPON" class="btn btn-warning py-18 w-100 rounded-8">
                          </form>
                          @endif
                        </div>
                        <div class="flex-align gap-16">
                          <form method="POST" action="{{route('cart.empty')}}">
                          @csrf
                          @method('DELETE')
                            <button type="submit" class="btn btn-danger py-18 w-100 rounded-8">Clear Cart</button>
                          </form>
                        </div>
                        {{-- CHANGED FROM A LINK TO A BUTTON WITH JS --}}
                        <button type="button" id="update-all-cart-btn" class="text-lg text-gray-500 hover-text-main-600 border-0 bg-transparent cursor-pointer">Update Cart</button>
                    
                    @else
                        <div class="flex-align gap-16">
                          
                            <a href="{{route('shop.index')}}" class="btn btn-main py-18 w-100 rounded-8">Add Cart</a>
                          
                        </div>
                        <a href="{{ route('cart.index') }}" class="text-lg text-gray-500 hover-text-main-600">Update Cart</a>
                    @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4">
                <div class="cart-sidebar border border-gray-100 rounded-8 px-24 py-40">
                    <h6 class="text-xl mb-32">Cart Totals</h6>
                    <div class="bg-color-three rounded-8 p-24">
                      @php
                          // Get normalized checkout values, defaulting to cart subtotal if not set
                          $checkout = Session::get('checkout', ['subtotal' => Cart::instance('cart')->subtotal(), 'delivery_fee' => '0.00', 'total' => Cart::instance('cart')->subtotal()]);
                          $deliveryFee = $checkout['delivery_fee'];
                          $finalTotal = $checkout['total'];
                      @endphp
                      @if(Session::has('discounts'))
                            <div class="mb-32 flex-between gap-8">
                                <span class="text-gray-900 font-heading-two text-xl fw-semibold">Discount: ({{Session::get('coupon')['code']}})</span>
                                <span class="text-gray-900 font-heading-two text-md fw-bold">원{{Session::get('discounts')['discount']}}</span>
                            </div>
                            <div class="mb-32 flex-between gap-8">
                                <span class="text-gray-900 font-heading-two text-xl fw-semibold">Subtotal</span>
                                <span class="text-gray-900 font-heading-two text-md fw-bold">원{{Cart::instance('cart')->subTotal()}}</span>
                            </div>
                      @else
                        <div class="mb-32 flex-between gap-8">
                            <span class="text-gray-900 font-heading-two">Subtotal</span>
                            <span class="text-gray-900 fw-semibold">원{{Cart::instance('cart')->subTotal()}}</span>
                        </div>
                      @endif
                      
                      {{-- DELIVERY FEE SECTION --}}
                        <div class="mb-32 flex-between gap-8">
                            <span class="text-gray-900 font-heading-two">Delivery Fee</span>
                            <span class="text-gray-900 fw-semibold">
                                @if(floatval($deliveryFee) > 0)
                                    원{{ number_format(floatval($deliveryFee), 0) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>
                    </div>  
                    <div class="bg-color-three rounded-8 p-24 mt-24">
                        <div class="flex-between gap-8">
                            <span class="text-gray-900 text-xl fw-semibold">Total</span>
                            <span class="text-gray-900 text-xl fw-semibold">원{{ number_format(floatval($finalTotal), 0) }}</span>
                        </div>
                    </div>
                    <a href="{{route('cart.checkout')}}" class="btn btn-main mt-40 py-18 w-100 rounded-8 {{ $hasOutOfStock ? 'disabled opacity-50 cursor-not-allowed' : '' }}" @if($hasOutOfStock) onclick="return false;" @endif>Proceed to checkout</a>
                    @if($hasOutOfStock)
                        <p class="text-danger mt-2 text-center fw-semibold">
                            Some items are out of stock. Please remove them to proceed.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
 </section>
@endsection 
@push('scripts')
<script>
    $(function(){
        // The individual increase/decrease buttons still submit the form directly.
        $(".qty-control__increase").on("click",function(e){});
        $(".qty-control__reduce").on("click",function(e){});
        $(".remove-cart").on("click",function(){
            $(this).closest('form').submit();
        });

        // NEW LOGIC FOR "UPDATE CART" BUTTON
        $("#update-all-cart-btn").on("click", function() {
            // Find all forms that update quantity and submit them
            $('.qty-update-form').each(function() {
                var form = $(this);
                // Temporarily remove the 'name' attribute from the +/- buttons 
                // so the form only submits the 'qty' input value when the main update button is clicked.
                var increaseButton = form.find('.qty-control__increase');
                var decreaseButton = form.find('.qty-control__reduce');
                
                // Only modify buttons if they are part of the form submission process
                if (increaseButton.length && decreaseButton.length) {
                    increaseButton.attr('data-temp-name', increaseButton.attr('name')).removeAttr('name');
                    decreaseButton.attr('data-temp-name', decreaseButton.attr('name')).removeAttr('name');
                }

                // Submit the form
                form.submit();
                
                // Restore the 'name' attribute immediately after submitting (optional, but good practice)
                if (increaseButton.length && decreaseButton.length) {
                    increaseButton.attr('name', increaseButton.attr('data-temp-name')).removeAttr('data-temp-name');
                    decreaseButton.attr('name', decreaseButton.attr('data-temp-name')).removeAttr('data-temp-name');
                }
            });
        });
        
        // Ensure that hitting ENTER in a quantity input field updates that item's quantity
        $(".quantity__input").on("keypress", function(e) {
            if (e.which === 13) { // 13 is the keycode for ENTER
                e.preventDefault();
                $(this).closest('form').submit();
            }
        });
    })
</script>
    
@endpush