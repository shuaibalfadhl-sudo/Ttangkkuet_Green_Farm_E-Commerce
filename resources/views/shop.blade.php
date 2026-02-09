@extends('layouts.apps')
@section('content')

<!-- ========================= Breadcrumb Start =============================== -->
<div class="breadcrumb py-26 bg-main-50">
    <div class="container container-lg">
        <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
            <h6 class="mb-0">Shop</h6>
            <ul class="flex-align gap-8 flex-wrap">
                <li class="text-sm">
                    <a href="{{ route('home.index') }}" class="text-gray-900 flex-align gap-8 hover-text-main-600">
                        <i class="ph ph-house"></i> Home
                    </a>
                </li>
                <li class="flex-align"><i class="ph ph-caret-right"></i></li>
                <li class="text-sm text-main-600">Product Shop</li>
            </ul>
        </div>
    </div>
</div>
<!-- ========================= Breadcrumb End =============================== -->

<!-- =============================== Shop Section Start ======================================== -->
<section class="shop py-80">
    <div class="container container-lg">
        <div class="row">

            <!-- Sidebar Start -->
            <div class="col-lg-3">
                <div class="shop-sidebar">
                    <button type="button" class="shop-sidebar__close d-lg-none d-flex w-32 h-32 flex-center border border-gray-100 rounded-circle hover-bg-main-600 position-absolute inset-inline-end-0 me-10 mt-8 hover-text-white hover-border-main-600">
                        <i class="ph ph-x"></i>
                    </button>

                    <!-- Categories -->
                    <div class="shop-sidebar__box border border-gray-100 rounded-8 p-32 mb-32">
                        <h6 class="text-xl border-bottom border-gray-100 pb-24 mb-24">Product Category</h6>
                        <ul class="list list-inline mb-0 category-list">
                            @foreach ($categories as $category)
                                <li class="list-item">
                                    <span class="menu-link py-1">
                                        <input type="checkbox" class="chk-category" name="categories" value="{{ $category->id }}"
                                            @if(in_array($category->id, explode(',', $f_categories))) checked @endif>
                                        {{ $category->name }}
                                    </span>
                                    <span class="text-right float-end">{{ $category->products->count() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="shop-sidebar__box rounded-8">
                        @if ($ads && $ads->image)
                            <img src="{{ asset('uploads/ads/' . $ads->image) }}" class="effect8" alt="">
                        @else
                        <img src="{{ asset('assets/images/freedelivery.jpg') }}" alt="">
                        @endif
                    </div>
                </div>
            </div>
            <!-- Sidebar End -->

            <!-- Content Start -->
            <div class="col-lg-9">

                <!-- Top bar -->
                <div class="flex-between gap-16 flex-wrap mb-40">
                    <span class="text-gray-900">Browse to your liking</span>
                    <div class="position-relative flex-align gap-16 flex-wrap">

                        <!-- View toggle -->
                        <div class="list-grid-btns flex-align gap-16">
                            <button type="button" class="w-44 h-44 flex-center border border-gray-100 rounded-6 text-2xl list-btn">
                                <i class="ph-bold ph-list-dashes"></i>
                            </button>
                            <button type="button" class="w-44 h-44 flex-center border border-main-600 text-white bg-main-600 rounded-6 text-2xl grid-btn">
                                <i class="ph ph-squares-four"></i>
                            </button>
                        </div>  

                        <!-- Sort -->
                        <div class="position-relative text-gray-500 flex-align gap-4 text-14">
                            <label for="orderby" class="text-inherit flex-shrink-0">Sort by: </label>
                            <select class="form-control common-input px-14 py-14 text-inherit rounded-6 w-auto" name="orderby" id="orderby">
                                <option value="-1" {{ $order == -1 ? 'selected':'' }}>Default</option>
                                <option value="1" {{ $order == 1 ? 'selected':'' }}>Date, New to Old</option>
                                <option value="2" {{ $order == 2 ? 'selected':'' }}>Date, Old to New</option>
                                <option value="3" {{ $order == 3 ? 'selected':'' }}>Price, Low to High</option>
                                <option value="4" {{ $order == 4 ? 'selected':'' }}>Price, High to Low</option>
                            </select>
                        </div>
                        

                        <!-- Mobile filter button -->
                        <button type="button" class="w-44 h-44 d-lg-none d-flex flex-center border border-gray-100 rounded-6 text-2xl sidebar-btn">
                            <i class="ph-bold ph-funnel"></i>
                        </button>
                    </div>
                </div>

                <!-- Product List -->
                <div class="list-grid-wrapper">
                        @foreach ($products as $product)
                          {{-- Calculate median rating for the current product --}}
                          <?php
                              $ratings = $product->reviews->pluck('rating')->toArray();
                              sort($ratings);
                              $count = count($ratings);

                              $medianRating = 0;

                              if ($count > 0) {
                                  $middle = floor(($count - 1) / 2);
                                  if ($count % 2) {
                                      $medianRating = $ratings[$middle];
                                  } else {
                                      $lowMiddle = $ratings[$middle];
                                      $highMiddle = $ratings[$middle + 1];
                                      $medianRating = ($lowMiddle + $highMiddle) / 2;
                                  }
                              }
                          ?>
                    <div class="product-card h-100 p-16 border border-gray-100 hover-border-main-600 rounded-16 position-relative transition-2">
                        <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}" class="product-card__thumb flex-center rounded-8 bg-gray-50">
                            <img src="{{ asset('uploads/products') }}/{{ $product->image }}" alt="" class="w-auto h-auto" />
                        </a>
                        <div class="product-card__content mt-16 w-100">
                            <h6 class="title text-lg fw-semibold mt-12 mb-8">
                                <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}" class="link text-line-2" tabindex="0">{{ $product->name }}</a>
                            </h6>
                            <div class="flex-align mb-20 mt-16 gap-6">
                                <span class="text-xs fw-medium text-gray-500">{{ $medianRating }}</span>
                                <span class="text-15 fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xs fw-medium text-gray-500">({{ $product->reviews->count() }})</span>
                            </div>

                            <div class="product-card__price my-20">
                                @if ($product->sale_price)
                                    <span
                                        class="text-gray-400 text-md fw-semibold text-decoration-line-through">원{{number_format(floatval($product->regular_price), 0)}}</span>
                                    <span
                                        class="text-heading text-md fw-semibold ">원{{ number_format(floatval($product->sale_price), 0)}}<span
                                            class="text-gray-500 fw-normal">/Kg</span> </span>
                                @else
                                    <span
                                        class="text-heading text-md fw-semibold ">원{{number_format(floatval($product->regular_price), 0)}}<span
                                            class="text-gray-500 fw-normal">/Kg</span> </span>
                                @endif
                            </div>
                            <div class="carts" style="display: flex">
                                @if (Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
                                    <a href="{{ route('cart.index') }}"
                                        class="product-card__cart btn bg-main-50 text-main-600 hover-bg-main-600 hover-text-white py-11 px-24 rounded-pill flex-align gap-8 mt-24 w-100 justify-content-center">
                                        Go To Cart <i class="ph ph-shopping-cart"></i>
                                    </a>
                                @else
                                    <form name="addtocart-form" method="post" class="w-100"
                                        action="{{ route('cart.add') }}">
                                        @csrf
                                        <input type="hidden" name="id"
                                            value="{{ $product->id }}" />
                                        <input type="hidden" name="quantity" value="1" />
                                        <input type="hidden" name="name"
                                            value="{{ $product->name }}" />
                                        <input type="hidden" name="price"
                                            value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price }}" />
                                        <button id="product-btn-{{ $product->id }}" type = "submit" data-aside="cartDrawer" title="Add To Cart" @if($product->quantity <= 0 || $product->stock_status == 'outstock') disabled @endif class="product-card__cart btn bg-main-50 text-main-600 hover-bg-main-600 hover-text-white py-11 px-24 rounded-pill flex-align gap-8 mt-24 w-100 justify-content-center">
                                             <span id="product-btn-text-{{ $product->id }}">
                                                @if($product->quantity <= 0 || $product->stock_status == 'outstock')
                                                    Out of Stock
                                                @else
                                                    Add To Cart <i class="ph ph-shopping-cart"></i>
                                                @endif
                                            </span>
                                        </button>
                                    </form>
                                @endif
                                @if (Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)
                                    <form method="POST"
                                        action="{{ route('wishlist.item.remove', ['rowId' => Cart::instance('wishlist')->content()->where('id', $product->id)->first()->rowId]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="product-card__cart btn bg-warning-600 text-warning-50 hover-bg-warning-50 hover-text-warning-600 py-11 px-24 rounded-pill flex-align gap-8 mt-24 w-100 justify-content-center"
                                            title="Remove To Wishlist">
                                            <i class="ph ph-heart"></i>
                                        </button>
                                    </form>
                                @else
                                    <form name="addtocart-form" method="post"
                                        action="{{ route('wishlist.add') }}">
                                        @csrf
                                        <input type="hidden" name="id"
                                            value="{{ $product->id }}" />
                                        <input type="hidden" name="quantity" value="1" />
                                        <input type="hidden" name="name"
                                            value="{{ $product->name }}" />
                                        <input type="hidden" name="price"
                                            value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price }}" />
                                        <button type = "submit"
                                            class="product-card__cart btn bg-warning-50 text-warning-600 hover-bg-warning-600 hover-text-white py-11 px-24 rounded-pill flex-align gap-8 mt-24 w-100 justify-content-center"
                                            data-aside="cartDrawer" title="Add To Wishlist"><i
                                                class="ph ph-heart"></i></button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
            <!-- Content End -->

        </div>
    </div>
</section>
<!-- =============================== Shop Section End ======================================== -->

<!-- Hidden filter form -->
<form id="frmfilter" method="GET" action="{{ route('shop.index') }}">
    <input type="hidden" name="page" value="{{ $products->currentPage() }}"/>
    <input type="hidden" name="size" id="size" value="{{ $size }}"/>
    <input type="hidden" name="categories" id="hdnCategories" value="{{ $f_categories }}"/> 
    <input type="hidden" name="order" id="order" value="{{ $order }}"/>
    {{-- <input type="hidden" name="min" id="hdnMinPrice" value="{{ $min_price }}"/>
    <input type="hidden" name="max" id="hdnMaxPrice" value="{{ $max_price }}"/> --}}
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Guard: Echo might not be available in some environments
        if (typeof Echo === 'undefined' || Echo === null) return;

        // Listen for product updates on the public 'products' channel.
        // Use the event payload to locate the DOM element by id dynamically.
        Echo.channel('products').listen('ProductUpdated', (e) => {
            // Support different payload shapes: e.product or e (depending on your event)
            const product = e.product || e;
            const id = product.id || (product.product && product.product.id);
            if (!id) return; // nothing we can update

            const button = document.getElementById('product-btn-' + id);
            const buttonText = document.getElementById('product-btn-text-' + id);

            if (button && buttonText) {
                if (product.quantity <= 0 || product.stock_status === 'outstock') {
                    button.setAttribute('disabled', 'true');
                    buttonText.innerHTML = 'Out of Stock';
                } else {
                    button.removeAttribute('disabled');
                    buttonText.innerHTML = 'Add To Cart<i class="ph ph-shopping-cart"></i>';
                }
            }
        });
    });
</script>
<script>
$(function(){
    // Pagesize change
    $("#pagesize").on("change",function(){
        $("#size").val($(this).val());
        $("#frmfilter").submit();
    });

    // Order change
    $("#orderby").on("change",function(){
        $("#order").val($(this).val());
        $("#frmfilter").submit();
    });

    // Category filter
    $("input[name='categories']").on("change",function(){
        var categories = $("input[name='categories']:checked").map(function(){return this.value}).get().join(",");
        $("#hdnCategories").val(categories);
        $("#frmfilter").submit();
    });

    // // Price filter
    // $("input[name='price_range']").on("change",function(){
    //     var range = $(this).val().split(',');
    //     $("#hdnMinPrice").val(range[0]);
    //     $("#hdnMaxPrice").val(range[1]);
    //     setTimeout(()=>$("#frmfilter").submit(),2000);
    // });
});
</script>
@endpush
