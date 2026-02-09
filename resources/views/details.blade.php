@extends('layouts.apps')
@section('content')
<!-- ========================= Breadcrumb Start =============================== -->
<div class="breadcrumb py-26 bg-color-one">
    <div class="container container-lg">
        <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
            <h6 class="mb-0">Shop Details</h6>
            <ul class="flex-align gap-8 flex-wrap">
                <li class="text-sm">
                    <a href="{{ route('home.index') }}" class="text-main-600 flex-align gap-8">
                        <i class="ph ph-house"></i>
                        Home
                    </a>
                </li>
                <li class="flex-align text-gray-500">
                    <i class="ph ph-caret-right"></i>
                </li>
                <li class="text-sm">
                    <a href="{{ route('shop.index') }}" class="text-main-600 flex-align gap-8">
                        Shop
                    </a>
                </li>
                <li class="flex-align text-gray-500">
                    <i class="ph ph-caret-right"></i>
                </li>
                <li class="text-sm text-neutral-600">
                    {{$product->name}}
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- ========================= Breadcrumb End =============================== -->

    <section class="product-details py-80">
    <div class="container container-lg">
        <div class="row gy-4">
            <div class="col-lg-9">
                <div class="row gy-4">
                    <div class="col-xl-6">
                        <div class="product-details__left">
                            
                            <div class="product-details__thumb-slider border border-gray-600 rounded-16">
                              
                                    <div class="product-details__thumb flex-center h-100">
                                        <img src="{{ asset('uploads/products/' . $product->image) }}" alt="">
                                    </div>
                                
                              @foreach (explode(',',$product->images) as $gimg)
                                
                                    <div class="product-details__thumb flex-center h-100">
                                        <img src="{{asset('uploads/products')}}/{{$gimg}}" alt="">
                                    </div>
                                
                              @endforeach
                            </div>

                            <div class="mt-24">
                                <div class="product-details__images-slider">
                                  <div>
                                        <div class="max-w-120 max-h-120 h-100 flex-center border border-gray-600 rounded-16 p-8">
                                            <img src="{{ asset('uploads/products/' . $product->image) }}" class="h-100" alt="">
                                        </div>
                                    </div>
                                  @foreach (explode(',',$product->images) as $gimg)
                                    <div>
                                        <div class="max-w-120 max-h-120 h-100 flex-center border border-gray-600 rounded-16 p-8">
                                            <img src="{{asset('uploads/products')}}/{{$gimg}}" class="h-100" alt="">
                                        </div>
                                    </div>
                                  @endforeach  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="product-details__content">
                            <h5 class="mb-12">{{$product->name}}</h5>
                            <div class="flex-align flex-wrap gap-12">
                                <div class="flex-align gap-12 flex-wrap">
                                    <div class="flex-align gap-8">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <?php
                                                $fillColor = '#ccc'; // Default empty star color
                                                $isHalfStar = false; // Flag to determine if it's a half-star

                                                if ($medianRating >= $i) {
                                                    $fillColor = '#ffc107'; // Full star color (yellow/gold)
                                                } elseif ($medianRating > ($i - 1) && $medianRating < $i) {
                                                    // This condition checks for a fractional median rating that falls within this star's range
                                                    $isHalfStar = true;
                                                    // The fill will be handled by the gradient
                                                }
                                            ?>
                                            <svg width="20" height="20" viewBox="0 0 24 24" style="margin-right: 2px;" xmlns="http://www.w3.org/2000/svg"
                                                @if($isHalfStar) fill="url(#halfStarGradient-{{ $product->id }}-{{ $i }})"
                                                @else fill="{{ $fillColor }}" @endif>
                                                @if($isHalfStar)
                                                    <defs>
                                                        <linearGradient id="halfStarGradient-{{ $product->id }}-{{ $i }}" x1="0%" y1="0%" x2="100%" y2="0%">
                                                            {{-- First stop: full color up to 50% --}}
                                                            <stop offset="50%" style="stop-color:#ffc107;stop-opacity:1" />
                                                            {{-- Second stop: empty color from 50% --}}
                                                            <stop offset="50%" style="stop-color:#ccc;stop-opacity:1" />
                                                        </linearGradient>
                                                    </defs>
                                                @endif
                                                <path d="M12 .587l3.668 7.568L24 9.423l-6 5.846 1.417 8.254L12 18.897l-7.417 4.626L6 15.269 0 9.423l8.332-1.268z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm fw-medium text-neutral-600">{{$medianRating}} Star Rating</span>
                                    <span class="text-sm fw-medium text-gray-500">({{ $product->reviews->count() }})</span>
                                </div>
                                <span class="text-sm fw-medium text-gray-500">|</span>
                                <span class="text-gray-900"> <span class="text-gray-400">SKU:</span>{{ $product->SKU}} </span>
                            </div>
                            <span class="mt-32 pt-32 text-gray-700 border-top border-gray-100 d-block"></span>
                            <p class="text-gray-700">{{$product->short_description}}</p>
                            <div class="mt-32 flex-align flex-wrap gap-32">
                                <div class="flex-align gap-8">
                                    @if ($product->sale_price)
                                        <h4 class="mb-0">원{{ number_format(floatval($product->sale_price), 0)}} </h4>
                                        <span
                                            class="text-md text-gray-500 text-decoration-line-through">원{{ number_format(floatval($product->regular_price), 0)}}</span>
                                        <span
                                                class="text-gray-500 fw-normal">/Kg</span>
                                    @else
                                        <h4 class="mb-0">원{{ number_format(floatval($product->regular_price), 0)}}
                                            <span class="text-gray-500 fw-normal">/Kg</span> 
                                        </h4>
                                    @endif
                                </div>
                            </div>
                            <span class="mt-32 pt-32 text-gray-700 border-top border-gray-100 d-block"></span>

                            <div class="mb-24">
                                @if($product->quantity < 10)
                                <div class="mt-32 flex-align gap-12 mb-16">
                                    <span class="w-32 h-32 bg-white flex-center rounded-circle text-main-600 box-shadow-xl"><i class="ph-fill ph-lightning"></i></span>
                                    <h6 class="text-md mb-0 fw-bold text-gray-900">Products are almost sold out</h6>
                                </div>
                                @endif
                                <div class="progress w-100 bg-gray-100 rounded-pill h-8" role="progressbar" aria-label="Basic example" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-main-two-600 rounded-pill" style="width: {{$product->quantity}}%"></div>
                                </div>
                                <span class="text-sm text-gray-700 mt-8">Available only:{{$product->quantity}}</span>
                            </div>

                            <span class="text-gray-900 d-block mb-8">Quantity:</span>
                            <div class="flex-between gap-16 flex-wrap">
                                
                                    
                                    @if (Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
                                        <a href="{{ route('cart.index') }}"
                                            class="btn btn-main rounded-pill flex-align d-inline-flex gap-8 px-48">
                                            Go to cart <i class="ph ph-shopping-cart"></i>
                                        </a>
                                    @else
                                        <form name="addtocart-form" method="post" action="{{ route('cart.add') }}">
                                            @csrf
                                            <div class="flex-align flex-wrap gap-16">
                                            <div class="border border-gray-100 rounded-pill py-9 px-16 flex-align">
                                                <button type="button" class="quantity__minus p-4 text-gray-700 hover-text-main-600 flex-center" @if($product->quantity <= 0 || $product->stock_status === 'outstock') disabled @endif><i class="ph ph-minus"></i></button>
                                                <input type="number" class="quantity__input border-0 text-center w-32" name="quantity" value="1" min="1" max="{{ $product->quantity }}" @if($product->quantity <= 0 || $product->stock_status === 'outstock') disabled @endif>
                                                <button type="button" class="quantity__plus p-4 text-gray-700 hover-text-main-600 flex-center" @if($product->quantity <= 0 || $product->stock_status === 'outstock') disabled @endif><i class="ph ph-plus"></i></button>
                                            </div>
                                            <input type="hidden" name="id" value="{{ $product->id }}" />
                                            <input type="hidden" name="name" value="{{ $product->name }}" />
                                            <input type="hidden" name="price" value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price }}" />
                                            <button type = "submit" class="btn btn-main rounded-pill flex-align d-inline-flex gap-8 px-48" data-aside="cartDrawer" title="Add To Cart" @if($product->quantity <= 0 || $product->stock_status === 'outstock') disabled @endif>
                                                @if($product->quantity <= 0 || $product->stock_status == 'outstock')
                                                    Out of Stock
                                                @else
                                                    Add To Cart<i class="ph ph-shopping-cart"></i>
                                                @endif
                                            </button>
                                            </div> 
                                        </form>
                                    @endif
                                
                                
                                <div class="flex-align gap-12">
                                    @if (Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)
                                        <form method="POST"
                                            action="{{ route('wishlist.item.remove', ['rowId' => Cart::instance('wishlist')->content()->where('id', $product->id)->first()->rowId]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-52 h-52 bg-warning-600 text-warning-50 text-xl hover-bg-warning-50 hover-text-warning-600 flex-center rounded-circle"
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
                                                class="w-52 h-52 bg-warning-50 text-warning-600 text-xl hover-bg-warning-600 hover-text-white flex-center rounded-circle"
                                                data-aside="cartDrawer" title="Add To Wishlist"><i
                                                    class="ph ph-heart"></i></button>
                                        </form>
                                    @endif 
                                </div>
                            </div>
                            
                            <span class="mt-32 pt-32 text-gray-700 border-top border-gray-100 d-block"></span>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="product-details__sidebar border border-gray-100 rounded-16 overflow-hidden">
                    <div class="p-24">
                        <div class="flex-between bg-main-600 rounded-pill p-8">
                            <div class="flex-align gap-8">
                                <span class="w-44 h-44 bg-white rounded-circle flex-center text-2xl"><i class="ph ph-storefront"></i></span>
                                <span class="text-white">Ttangkkuet Green Farm</span>
                            </div>
                            <a href="{{ route('home.index') }}" class="btn btn-white rounded-pill text-uppercase">Shop More</a>
                        </div>
                    </div>
                    <div class="p-24 bg-color-one d-flex align-items-start gap-24 border-bottom border-gray-100">
                        <span class="w-44 h-44 bg-white text-main-600 rounded-circle flex-center text-2xl flex-shrink-0">
                            <i class="ph-fill ph-truck"></i>
                        </span>
                        <div class="">
                            <h6 class="text-sm mb-8">Fast Delivery</h6>
                            <p class="text-gray-700">Lightning-fast shipping, guaranteed.</p>
                        </div>
                    </div>
                    <div class="p-24 bg-color-one d-flex align-items-start gap-24 border-bottom border-gray-100">
                        <span class="w-44 h-44 bg-white text-main-600 rounded-circle flex-center text-2xl flex-shrink-0">
                            <i class="ph-fill ph-check-circle"></i>
                        </span>
                        <div class="">
                            <h6 class="text-sm mb-8">Pickup available at Shop location</h6>
                            <p class="text-gray-700">Usually ready in 24 hours</p>
                        </div>
                    </div>
                    <div class="p-24 bg-color-one d-flex align-items-start gap-24 border-bottom border-gray-100">
                        <span class="w-44 h-44 bg-white text-main-600 rounded-circle flex-center text-2xl flex-shrink-0">
                            <i class="ph-fill ph-credit-card"></i>
                        </span>
                        <div class="">
                            <h6 class="text-sm mb-8">Payment</h6>
                            <p class="text-gray-700">Payment upon receipt of goods, Payment by card in the department, Google Pay, Online card.</p>
                        </div>
                    </div>
                    <div class="p-24 bg-color-one d-flex align-items-start gap-24 border-bottom border-gray-100">
                        <span class="w-44 h-44 bg-white text-main-600 rounded-circle flex-center text-2xl flex-shrink-0">
                            <i class="ph-fill ph-package"></i>
                        </span>
                        <div class="">
                            <h6 class="text-sm mb-8">Packaging</h6>
                            <p class="text-gray-700">Research & development value proposition graphical user interface investor.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-80">
            <div class="product-dContent border rounded-24">
                <div class="product-dContent__header border-bottom border-gray-100 flex-between flex-wrap gap-16">
                    <ul class="nav common-tab nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link active" id="pills-description-tab" data-bs-toggle="pill" data-bs-target="#pills-description" type="button" role="tab" aria-controls="pills-description" aria-selected="true">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="pills-reviews-tab" data-bs-toggle="pill" data-bs-target="#pills-reviews" type="button" role="tab" aria-controls="pills-reviews" aria-selected="false">Reviews</button>
                        </li>
                    </ul>
                </div>
                <div class="product-dContent__box">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-description" role="tabpanel" aria-labelledby="pills-description-tab" tabindex="0">
                            <div class="mb-40">
                                <h6 class="mb-24">Product Description</h6>
                                <p>{{$product->description}}</p>    
                            {{-- <ul class="list-inside mt-32 ms-16">
                                    <li class="text-gray-400 mb-4">8.0 oz. bag of LAY'S Classic Potato Chips</li>
                                    <li class="text-gray-400 mb-4">Tasty LAY's potato chips are a great snack</li>
                                    <li class="text-gray-400 mb-4">Includes three ingredients: potatoes, oil, and salt</li>
                                    <li class="text-gray-400 mb-4">Gluten free product</li>
                                </ul>
                                <ul class="mt-32">
                                    <li class="text-gray-400 mb-4">Made in USA</li>
                                    <li class="text-gray-400 mb-4">Ready To Eat.</li>
                                </ul> --}}
                            </div>
                        {{-- <div class="mb-40">
                                <h6 class="mb-24">Product Specifications</h6>
                                <ul class="mt-32">
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium">
                                            Product Type:
                                            <span class="text-gray-500"> Chips & Dips</span>
                                        </span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium">
                                            Product Name:
                                            <span class="text-gray-500"> Potato Chips Classic </span>
                                        </span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium">
                                            Brand:
                                            <span class="text-gray-500"> Lay's</span>
                                        </span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium">
                                            FSA Eligible:
                                            <span class="text-gray-500"> No</span>
                                        </span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium">
                                            Size/Count: 
                                            <span class="text-gray-500"> 8.0oz</span>
                                        </span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium">
                                            Item Code:
                                            <span class="text-gray-500"> 331539</span>
                                        </span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium">
                                            Ingredients:
                                            <span class="text-gray-500"> Potatoes, Vegetable Oil, and Salt.</span>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mb-40">
                                <h6 class="mb-24">Nutrition Facts</h6>
                                <ul class="mt-32">
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium"> Total Fat 10g 13%</span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium"> Saturated Fat 1.5g 7%</span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium"> Cholesterol 0mg 0%</span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium"> Sodium 170mg 7%</span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-heading fw-medium"> Potassium 350mg 6%</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mb-0">
                                <h6 class="mb-24">More Details</h6>
                                <ul class="mt-32">
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-gray-500"> Lunarlon midsole delivers ultra-plush responsiveness</span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-gray-500"> Encapsulated Air-Sole heel unit for lightweight cushioning</span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-gray-500"> Colour Shown: Ale Brown/Black/Goldtone/Ale Brown</span>
                                    </li>
                                    <li class="text-gray-400 mb-14 flex-align gap-14">
                                        <span class="w-20 h-20 bg-main-50 text-main-600 text-xs flex-center rounded-circle">
                                            <i class="ph ph-check"></i>
                                        </span>
                                        <span class="text-gray-500"> Style: 805899-202</span>
                                    </li>
                                </ul>
                            </div> 
                        --}}

                        </div>
                        <div class="tab-pane fade" id="pills-reviews" role="tabpanel" aria-labelledby="pills-reviews-tab" tabindex="0">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <h6 class="mb-24">Product Reviews</h6>
                                    @if($product->reviews->isEmpty())
                                        <p class="text-gray-600 text-center py-4">No reviews yet for this product. Be the first to leave one!</p>
                                    @else
                                        {{-- Display Top 3 Reviews --}}
                                        <div class="top-reviews-section mb-56">
                                            <h3 class="text-xl font-bold mb-4">Top Rated Reviews</h3>
                                                @foreach($topReviews as $review)
                                                <div class="d-flex align-items-start gap-24 pb-44 border-bottom border-gray-100 mb-44">
                                                    @if($review->user->profile_image) 
                                                        <img src="{{ asset('uploads/profile_images/' . $review->user->profile_image) }}" alt="Profile" class="w-52 h-52 object-fit-cover rounded-circle flex-shrink-0" > 
                                                    @else 
                                                        <div class="w-52 h-52 object-fit-cover rounded-circle flex-shrink-0 bg-gray-600 text-white flex-center text-lg fw-bold"> 
                                                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }} 
                                                        </div> 
                                                    @endif
                                                    <div class="flex-grow-1">
                                                        <div class="flex-between align-items-start gap-8 ">
                                                            <div>
                                                                <h6 class="mb-12 text-md">{{ $review->user->name}}</h6>
                                                                <div class="flex-align gap-8">
                                                                    {{-- Star Rating Display --}}
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <svg width="20" height="20" viewBox="0 0 24 24" style="margin-right: 2px;" xmlns="http://www.w3.org/2000/svg" fill="{{ $i <= $review->rating ? '#ffc107' : '#ccc' }}">
                                                                            <path d="M12 .587l3.668 7.568L24 9.423l-6 5.846 1.417 8.254L12 18.897l-7.417 4.626L6 15.269 0 9.423l8.332-1.268z"/>
                                                                        </svg>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                            <span class="text-gray-800 text-xs">{{ $review->created_at->format('F d, Y') }}</span>
                                                        </div>
                                                        
                                                        <p class="text-gray-700">{{ $review->comment }}</p>
                                                        
                                                        @if ($review->image)
                                                            <div class="review-image mt-3">
                                                                <img src="{{ asset('uploads/reviews/' . $review->image) }}" alt="Review Image"
                                                                    class="review-thumb"
                                                                    onclick="openImageModal('{{ asset('uploads/reviews/' . $review->image) }}')">
                                                            </div>
                                                        @endif 
                                                        
                                                        <div class="flex-align gap-20 mt-44">
                                                            @auth
                                                                {{-- Like/Unlike Functionality --}}
                                                                @if (auth()->user()->isLikedBy($review))
                                                                    {{-- User has already liked it (Show Unlike button) --}}
                                                                    <form method="POST" action="{{ route('reviews.unlike', $review->id) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="flex-align gap-12 text-main-600 fw-bold">
                                                                            <i class="ph-fill ph-thumbs-up"></i>
                                                                            Liked ({{ $review->likes_count }})
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    {{-- User has not liked it (Show Like button) --}}
                                                                    <form method="POST" action="{{ route('reviews.like', $review->id) }}">
                                                                        @csrf
                                                                        <button type="submit" class="flex-align gap-12 text-gray-700 hover-text-main-600">
                                                                            <i class="ph-bold ph-thumbs-up"></i>
                                                                            Like ({{ $review->likes_count }})
                                                                        </button>
                                                                    </form>
                                                                @endif

                                                                {{-- 🗑️ ADMIN-ONLY Delete Button --}}
                                                                {{-- Check if the current user's utype is ADM --}}
                                                                @if (auth()->user()->utype === 'ADM')
                                                                    <form method="POST" action="{{ route('reviews.delete', $review->id) }}" onsubmit="return confirm('ADMIN ACTION: Are you sure you want to delete this review?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="flex-align gap-12 text-danger hover-text-danger fw-bold">
                                                                            <i class="ph-bold ph-trash"></i> 
                                                                            Delete
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @else
                                                                {{-- Not logged in (Show static count and a link to login) --}}
                                                                <a href="{{ route('login') }}" class="flex-align gap-12 text-gray-700 hover-text-main-600">
                                                                    <i class="ph-bold ph-thumbs-up"></i>
                                                                    Like ({{ $review->likes_count }})
                                                                </a>
                                                            @endauth
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="text-center mt-24">
                                                <a href="{{ route('shop.product.reviews', $product->slug) }}" class="btn btn-outline-main">See All Reviews</a>
                                            </div>
                                        </div>

                                        {{-- The original loop for all reviews is now removed, as the new controller logic provides a limited set. --}}
                                    @endif
                                    <div class="mt-56">
                                        @auth
                                            @php
                                                $hasDeliveredOrder = auth()->user()->hasDeliveredOrderForProduct($product->id);
                                            @endphp

                                            @if(!$hasDeliveredOrder)
                                                <div class="col-md-12">
                                                    <p class="text-gray-700 mb-3">You can write a review once your order has been delivered.</p>
                                                    <a class="btn btn-main bg-main-600 rounded-pill mt-48 disabled">Write a Review</a>
                                                </div>
                                            @else
                                                @if($userReview)
                                                    <div class="col-md-12">
                                                        <p class="text-gray-700 mb-3">You have already submitted a review for this product.</p>
                                                        <a class="btn btn-main bg-main-600 hover-bg-main-50 hover-text-main-600 rounded-pill mt-48" href="{{ route('reviews.edit', $userReview->id) }}">
                                                            Edit Your Review
                                                        </a>
                                                        <form method="POST" action="{{ route('reviews.delete', $userReview->id) }}" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger hover-bg-danger-50 hover-text-danger-600 rounded-pill mt-48">Delete Your Review</button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="col-md-12">
                                                        <p class="text-gray-700 mb-3">Be the first to share your thoughts on this product!</p>
                                                        <a class="btn btn-main bg-main-600 hover-bg-main-50 hover-text-main-600 rounded-pill mt-48" href="{{ route('validate.review', ['product_slug' => $product->slug]) }}">
                                                            Write a Review Now
                                                        </a>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            <div class="col-md-12">
                                                <p class="text-gray-700 mb-3">Please <a href="{{ route('login') }}" class="text-main-600 hover:underline">log in</a> to leave a review.</p>
                                                <a href="{{ route('validate.review', ['product_slug' => $product->slug]) }}" class="btn btn-main bg-main-600 hover-bg-main-50 hover-text-main-600 rounded-pill mt-48">
                                                    Write a Review (Login Required)
                                                </a>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="ms-xxl-5">
                                        <h6 class="mb-24">Customers Feedback</h6>
                                        <div class="d-flex flex-wrap gap-44">
                                            <div class="border border-gray-100 rounded-8 px-40 py-52 flex-center flex-column flex-shrink-0 text-center">
                                                <h2 class="mb-6 text-main-600">{{ $medianRating }}</h2>
                                                <div class="flex-center gap-8">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <?php
                                                            $fillColor = '#ccc'; // Default empty star color
                                                            $isHalfStar = false; // Flag to determine if it's a half-star

                                                            if ($medianRating >= $i) {
                                                                $fillColor = '#ffc107'; // Full star color (yellow/gold)
                                                            } elseif ($medianRating > ($i - 1) && $medianRating < $i) {
                                                                // This condition checks for a fractional median rating that falls within this star's range
                                                                $isHalfStar = true;
                                                                // The fill will be handled by the gradient
                                                            }
                                                        ?>
                                                        <svg width="20" height="20" viewBox="0 0 24 24" style="margin-right: 2px;" xmlns="http://www.w3.org/2000/svg"
                                                            @if($isHalfStar) fill="url(#halfStarGradient-{{ $product->id }}-{{ $i }})"
                                                            @else fill="{{ $fillColor }}" @endif>
                                                            @if($isHalfStar)
                                                                <defs>
                                                                    <linearGradient id="halfStarGradient-{{ $product->id }}-{{ $i }}" x1="0%" y1="0%" x2="100%" y2="0%">
                                                                        {{-- First stop: full color up to 50% --}}
                                                                        <stop offset="50%" style="stop-color:#ffc107;stop-opacity:1" />
                                                                        {{-- Second stop: empty color from 50% --}}
                                                                        <stop offset="50%" style="stop-color:#ccc;stop-opacity:1" />
                                                                    </linearGradient>
                                                                </defs>
                                                            @endif
                                                            <path d="M12 .587l3.668 7.568L24 9.423l-6 5.846 1.417 8.254L12 18.897l-7.417 4.626L6 15.269 0 9.423l8.332-1.268z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="mt-16 text-gray-500">Average Product Rating</span>
                                            </div>
                                            <div class="border border-gray-100 rounded-8 px-24 py-40 flex-grow-1">
                                                @php
                                                    $totalReviews = $product->reviews->count();
                                                    $starCounts = [];
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        $starCounts[$i] = $product->reviews->where('rating', $i)->count();
                                                    }
                                                @endphp

                                                @for ($i = 5; $i >= 1; $i--)
                                                    @php
                                                        $count = $starCounts[$i] ?? 0;
                                                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                                    @endphp
                                                    <div class="flex-align gap-8 @if($i > 1) mb-20 @else mb-0 @endif">
                                                        <span class="text-gray-900 flex-shrink-0">{{ $i }}</span>
                                                        <div class="progress w-100 bg-gray-100 rounded-pill h-8" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar bg-main-600 rounded-pill" style="width: {{ $percentage }}%"></div>
                                                        </div>
                                                        <div class="flex-align gap-4">
                                                            <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                                        </div>
                                                        <span class="text-gray-900 flex-shrink-0" style="width: 40px;">{{ $count }}</span>
                                                        <span class="text-gray-500 flex-shrink-0" style="width: 50px;">({{ round($percentage) }}%)</span>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>  
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>

<!-- ========================== Similar Product Start ============================= -->
    <section class="new-arrival pb-80">
        <div class="container container-lg">
            <div class="section-heading">
                <div class="flex-between flex-wrap gap-8">
                    <h5 class="mb-0">You Might Also Like</h5>
                    <div class="flex-align gap-16">
                        <a href="{{ route('shop.index') }}" class="text-sm fw-medium text-gray-700 hover-text-main-600 hover-text-decoration-underline">All Products</a>
                        <div class="flex-align gap-8">
                            <button type="button" id="new-arrival-prev" class="slick-prev slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1" >
                                <i class="ph ph-caret-left"></i>
                            </button>
                            <button type="button" id="new-arrival-next" class="slick-next slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1" >
                                <i class="ph ph-caret-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="new-arrival__slider arrow-style-two">
              @foreach($rproducts as $rproduct)
                <?php
                  $ratings = $rproduct->reviews->pluck('rating')->toArray();
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
                <div>
                    <div class="product-card h-100 p-8 border border-gray-100 hover-border-main-600 rounded-16 position-relative transition-2">
                        <a href="{{route('shop.product.details',['product_slug'=>$rproduct->slug])}}" class="product-card__thumb flex-center">
                            <img src="{{asset('uploads/products')}}/{{$rproduct->image}}" alt="{{$rproduct->name}}">
                        </a>
                        <div class="product-card__content p-sm-2 w-100">
                            <h6 class="title text-lg fw-semibold mt-12 mb-8">
                                <a href="{{route('shop.product.details',['product_slug'=>$rproduct->slug])}}" class="link text-line-2">{{$rproduct->name}}</a>
                            </h6>   
                            <div class="flex-align gap-4">
                                <span class="text-gray-500 text-xs">{{$rproduct->category->name}}</span>
                            </div>

                            <div class="product-card__content mt-12">
                                <div class="product-card__price mb-8">
                                  @if ($rproduct->sale_price)
                                        <span
                                            class="text-gray-400 text-md fw-semibold text-decoration-line-through">원{{ $rproduct->regular_price }}</span>
                                        <span class="text-heading text-md fw-semibold ">원{{ $rproduct->sale_price }}<span
                                                class="text-gray-500 fw-normal">/Kg</span> </span>
                                    @else
                                        원{{ $rproduct->regular_price }}
                                    @endif
                                </div>
                                <div class="flex-align gap-6">
                                    <span class="text-xs fw-bold text-gray-600">{{ $medianRating }}</span>
                                    <span class="text-15 fw-bold text-warning-600 d-flex"><i
                                            class="ph-fill ph-star"></i></span>
                                    <span class="text-xs fw-bold text-gray-600">({{ $rproduct->reviews->count() }})</span>
                                </div>
                                <div class="carts" style="display: flex">
                                    @if (Cart::instance('cart')->content()->where('id', $rproduct->id)->count() > 0)
                                        <a href="{{ route('cart.index') }}"
                                            class="product-card__cart btn bg-main-50 text-main-600 hover-bg-main-600 hover-text-white py-11 px-24 rounded-pill flex-align gap-8 mt-24 w-100 justify-content-center">
                                            Go To Cart <i class="ph ph-shopping-cart"></i>
                                        </a>
                                    @else
                                        <form name="addtocart-form" method="post" action="{{ route('cart.add') }}">
                                            @csrf
                                            <input type="hidden" name="id"
                                                value="{{ $rproduct->id }}" />
                                            <input type="hidden" name="quantity" value="1" />
                                            <input type="hidden" name="name"
                                                value="{{ $rproduct->name }}" />
                                            <input type="hidden" name="price"
                                                value="{{ $rproduct->sale_price == '' ? $rproduct->regular_price : $rproduct->sale_price }}" />
                                            <button type = "submit" data-aside="cartDrawer" title="Add To Cart" @if ($rproduct->quantity <= 0 || $rproduct->stock_status == 'outstock') disabled @endif class="product-card__cart btn bg-main-50 text-main-600 hover-bg-main-600 hover-text-white py-11 px-24 rounded-pill flex-align gap-8 mt-24 w-100 justify-content-center">
                                                @if($rproduct->quantity <= 0 || $rproduct->stock_status == 'outstock')
                                                Out of Stock
                                                @else
                                                    Add To Cart<i class="ph ph-shopping-cart"></i>
                                                @endif
                                            </button>
                                        </form>
                                    @endif
                                    @if (Cart::instance('wishlist')->content()->where('id', $rproduct->id)->count() > 0)
                                        <form method="POST"
                                            action="{{ route('wishlist.item.remove', ['rowId' => Cart::instance('wishlist')->content()->where('id', $rproduct->id)->first()->rowId]) }}">
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
                                                value="{{ $rproduct->id }}" />
                                            <input type="hidden" name="quantity" value="1" />
                                            <input type="hidden" name="name"
                                                value="{{ $rproduct->name }}" />
                                            <input type="hidden" name="price"
                                                value="{{ $rproduct->sale_price == '' ? $rproduct->regular_price : $rproduct->sale_price }}" />
                                            <button type = "submit"
                                                class="product-card__cart btn bg-warning-50 text-warning-600 hover-bg-warning-600 hover-text-white py-11 px-24 rounded-pill flex-align gap-8 mt-24 w-100 justify-content-center"
                                                data-aside="cartDrawer" title="Add To Wishlist"><i
                                                    class="ph ph-heart"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              @endforeach  
            </div>
        </div>
    </section>
<!-- ========================== Similar Product End ============================= -->

<!-- 🖼️ Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>
<!-- 💅 CSS -->
<style>
.review-thumb {
    width: 120px;
    height: 120px;
    border-radius: 8px;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.2s ease;
}
.review-thumb:hover {
    transform: scale(1.05);
}
.image-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    padding-top: 70px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.85);
}
.image-modal img {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 85vh;
    border-radius: 10px;
}
.image-modal .close {
    position: absolute;
    top: 20px;
    right: 40px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}
.image-modal .close:hover {
    color: #ccc;
}
</style>
<script>
function openImageModal(src) {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    modal.style.display = "block";
    modalImg.src = src;
}
function closeImageModal() {
    document.getElementById("imageModal").style.display = "none";
}
</script>
@endsection