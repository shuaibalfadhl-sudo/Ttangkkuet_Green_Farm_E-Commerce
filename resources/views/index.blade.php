@extends('layouts.apps')
@section('content')
    <!-- ============================ Banner Section start =============================== -->
    <style>
        /* ... (Your existing container and slider styles) ... */

        .hero-container {
            width: 100%;
            height: 600px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            box-sizing: border-box;
            background-color: #e6f0d9;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .slider-wrapper {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .slider-track {
            display: flex;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .slider-slide {
            flex-shrink: 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            box-sizing: border-box;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

        /* Update CSS */
        .hero-content-left {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            text-align: left;
            padding: 40px;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
        }

        /* Base styles for all content children: Hidden and offset for transition start */
        /* NOTE: We select the children directly here. */
        .hero-content-left h1,
        .hero-content-left .hero-subtitle,
        .hero-content-left .hero-buttons {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1.5s ease-in-out, transform 1.5s ease-in-out;
        }

        /* 🌟 FIX: Active state for text content is now triggered by the individual element having the '.active' class. */
        .hero-content-left h1.active,
        .hero-content-left .hero-subtitle.active,
        .hero-content-left .hero-buttons.active {
            opacity: 1;
            transform: translateY(0);
        }

        .hero-title {
            font-size: 3rem;
            font-weight: bold;
            color: #333;
            line-height: 1.2;
            margin: 0 0 10px;
        }

        .hero-title span {
            color: #4CAF50;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: #666;
            margin: 0 0 20px;
            display: flex;
            align-items: center;
        }

        .hero-buttons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .buy-now-button {
            display: inline-flex;
            align-items: center;
            padding: 15px 30px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
            transition: background-color 0.3s, transform 0.2s;
        }

        .buy-now-button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .buy-now-button img {
            margin-right: 10px;
        }

        .hero-image-right {
            position: relative;
            z-index: 1;
        }

        /* Base styles for image: Hidden and offset for transition start */
        .hero-image-right img {
            width: 100%;
            height: auto;
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1.5s ease-in-out, transform 1.5s ease-in-out;
            box-shadow: 0 0 15px 5px rgba(76, 175, 80, 0.6);
            border-radius: 10px;
        }
        
        /* Active State for Image Content */
        .hero-image-right.active img {
            opacity: 1;
            transform: translateY(0);
        }

        /* ... (The rest of your CSS, including navigation, dots, and media queries) ... */

        .slider-navigation {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            padding: 0 20px;
            box-sizing: border-box;
            z-index: 2;
        }

        .nav-button {
            background-color: rgba(0, 0, 0, 0);
            color: rgb(0, 105, 4);
            border: none;
            padding: 20px 15px;
            font-size: 1.8rem;
            cursor: pointer;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .nav-button:hover {
            background-color: rgba(0, 0, 0, 0);
        }

        .slider-dots {
            text-align: center;
            padding: 10px 0;
            position: absolute;
            bottom: 20px;
            width: 100%;
            z-index: 2;
        }

        .dot {
            height: 12px;
            width: 12px;
            margin: 0 6px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.2s;
            cursor: pointer;
        }

        .dot.active {
            background-color: #4CAF50;
            transform: scale(1.2);
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .hero-container {
                flex-direction: column;
                height: auto;
                text-align: center;
                padding-top: 50px;
                padding-bottom: 50px;
            }

            .hero-content-left {
                align-items: center;
                text-align: center;
                padding: 20px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                flex-direction: column;
                text-align: center;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .slider-slide {
                flex-direction: column;
                height: auto;
                padding: 20px 0;
            }

            .hero-image-right img {
                max-width: 80%;
                margin-top: 20px;
            }

            .nav-button {
                padding: 15px 10px;
            }
        }

        @keyframes slow-rotate {
            0% {
                transform: rotate(-10deg);
            }

            50% {
                transform: rotate(10deg);
            }

            100% {
                transform: rotate(-10deg);
            }
        }
    </style>
    <div class="hero-container">
        <div class="slider-wrapper">
            <div class="slider-track">
                @foreach ($slides as $slide)
                    <div class="slider-slide"
                        style="background-image: url('{{ asset('uploads/slides/bgimage') }}/{{ $slide->bgimage }}'); background-size:cover;background-repeat: no-repeat; background-position: center center;">
                        <div class="hero-content-left">
                            <h1 class="hero-title">
                                {{ $slide->title }}<br>
                                <span>{{ $slide->subtitle }}</span>
                            </h1>
                            <div class="hero-subtitle">
                                <span>
                                    {{ $slide->tagline }}
                                </span>
                            </div>
                            <div class="hero-buttons">
                                <a href="{{ $slide->link }}" class="buy-now-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-baggage-claim">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                                        <path d="M3 6h18" />
                                        <path d="M16 10a4 4 0 0 1-8 0" />
                                    </svg>
                                    Buy Now
                                </a>
                            </div>
                        </div>
                        <div class="hero-image-right">
                            <img src="{{ asset('uploads/slides') }}/{{ $slide->image }}" alt="Food Item">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="slider-navigation">
            <button class="nav-button prev-button">❮</button>
            <button class="nav-button next-button">❯</button>
        </div>

        <div class="slider-dots"></div>
    </div> 
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sliderTrack = document.querySelector(".slider-track");
            const slides = document.querySelectorAll(".slider-slide");
            const prevButton = document.querySelector(".prev-button");
            const nextButton = document.querySelector(".next-button");
            const dotsContainer = document.querySelector(".slider-dots");
            let currentSlide = 0;
            let slideInterval;

            // Create a dot for each slide
            slides.forEach((slide, index) => {
                const dot = document.createElement("span");
                dot.classList.add("dot");
                dot.setAttribute("data-slide-index", index);
                dot.addEventListener("click", () => {
                    goToSlide(index);
                    resetAutoSlide();
                });
                dotsContainer.appendChild(dot);
            });

            const moveSlider = () => {
                if (slides.length > 0) {
                    const slideWidth = slides[0].offsetWidth;
                    sliderTrack.style.transition = 'transform 0.5s ease-in-out';
                    sliderTrack.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
                }
            };

            const goToSlide = (index) => {
                // 1. Reset/Fade Out ALL content and images (by removing 'active')
                // Targets ALL active content and image containers across all slides
                document.querySelectorAll(".hero-content-left h1.active, .hero-content-left .hero-subtitle.active, .hero-content-left .hero-buttons.active").forEach(element => {
                    element.classList.remove("active");
                });
                document.querySelectorAll(".hero-image-right.active").forEach(imageContainer => {
                    imageContainer.classList.remove("active"); 
                });

                // 2. Set the new slide index and move the slider
                currentSlide = index;
                moveSlider();
                updateDots();

                // 3. Staggered fade-in for content and image on the new slide
                setTimeout(() => {
                    const currentSlideElement = slides[currentSlide];
                    const currentText = currentSlideElement.querySelector(".hero-content-left");
                    const currentImageContainer = currentSlideElement.querySelector(".hero-image-right");
                    
                    // --- Text Content Animation (Staggered) ---
                    if (currentText) {
                        const h1 = currentText.querySelector('.hero-title');
                        const subtitle = currentText.querySelector('.hero-subtitle');
                        const buttons = currentText.querySelector('.hero-buttons');

                        // H1 (Title)
                        if (h1) h1.classList.add("active");

                        // Subtitle (with delay)
                        setTimeout(() => {
                            if (subtitle) subtitle.classList.add("active");
                        }, 500); 

                        // Buttons (with more delay)
                        setTimeout(() => {
                            if (buttons) buttons.classList.add("active");
                        }, 1000); 
                    }
                    
                    // --- Image Animation ---
                    if (currentImageContainer) {
                        currentImageContainer.classList.add("active");
                    }
                }, 50); // Small initial delay to ensure slide move starts first
            };

            const updateDots = () => {
                document.querySelectorAll(".dot").forEach(dot => dot.classList.remove("active"));
                if (dotsContainer.children[currentSlide]) {
                    dotsContainer.children[currentSlide].classList.add("active");
                }
            };

            const nextSlide = () => {
                currentSlide = (currentSlide < slides.length - 1) ? currentSlide + 1 : 0;
                goToSlide(currentSlide);
            };

            const startAutoSlide = () => {
                stopAutoSlide();
                slideInterval = setInterval(() => {
                    nextSlide();
                }, 6000);
            };

            const resetAutoSlide = () => {
                stopAutoSlide();
                startAutoSlide();
            };

            const stopAutoSlide = () => {
                clearInterval(slideInterval);
            };

            prevButton.addEventListener("click", () => {
                currentSlide = (currentSlide > 0) ? currentSlide - 1 : slides.length - 1;
                goToSlide(currentSlide);
                resetAutoSlide();
            });

            nextButton.addEventListener("click", () => {
                nextSlide();
                resetAutoSlide();
            });

            // Initialize the slider and start auto-play
            goToSlide(0);
            startAutoSlide();
        });
    </script>
    <!-- ============================ Banner Section End =============================== -->

    <!-- ============================ Feature Section start =============================== -->
    <div class="feature" id="featureSection">
        <div class="container container-lg">
            <div class="position-relative arrow-center">
                <div class="flex-align">
                    <button type="button" id="feature-item-wrapper-prev"
                        class="slick-prev slick-arrow flex-center rounded-circle bg-white text-xl hover-bg-main-600 hover-text-white transition-1">
                        <i class="ph ph-caret-left"></i>
                    </button>
                    <button type="button" id="feature-item-wrapper-next"
                        class="slick-next slick-arrow flex-center rounded-circle bg-white text-xl hover-bg-main-600 hover-text-white transition-1">
                        <i class="ph ph-caret-right"></i>
                    </button>
                </div>
                <div class="feature-item-wrapper">
                    @foreach ($categories as $category)
                        <div class="feature-item text-center">
                            <div class="feature-item__thumb rounded-circle">
                                <a href="{{ route('shop.index', ['categories' => $category->id]) }}"
                                    class="w-100 h-100 flex-center">
                                    <img src="{{ asset('uploads/categories') }}/{{ $category->image }}" alt="">
                                </a>
                            </div>
                            <div class="feature-item__content mt-16">
                                <h6 class="text-lg mb-8"><a
                                        href="{{ route('shop.index', ['categories' => $category->id]) }}"
                                        class="text-inherit">{{ $category->name }}</a></h6>
                                <span class="text-sm text-gray-400">{{ $category->products->count() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- ============================ Feature Section End =============================== -->

    <!-- ========================= flash sales Start ================================ -->
    <section class="flash-sales pt-80">
        <div class="container container-lg">
            <div class="section-heading">
                <div class="flex-between flex-wrap gap-8">
                    <h5 class="mb-0">Sales Today</h5>
                    <div class="flex-align gap-16">
                        <a href="{{ route('shop.index') }}"
                            class="text-sm fw-medium text-gray-700 hover-text-main-600 hover-text-decoration-underline">View
                            All Deals</a>
                        <div class="flex-align gap-8">
                            <button type="button" id="flash-prev"
                                class="slick-prev slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1">
                                <i class="ph ph-caret-left"></i>
                            </button>
                            <button type="button" id="flash-next"
                                class="slick-next slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1">
                                <i class="ph ph-caret-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flash-sales__slider arrow-style-two">
                @foreach ($sproducts as $sproduct)
                <?php
                    $ratings = $sproduct->reviews->pluck('rating')->toArray();
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
                        <div
                            class="flash-sales-item rounded-16 overflow-hidden z-1 position-relative flex-align flex-0 justify-content-between gap-8">
                            <img src="assets/images/bg/flash-sale-bg1.png" alt=""
                                class="position-absolute inset-block-start-0 inset-inline-start-0 w-100 h-100 object-fit-cover z-n1 flash-sales-item__bg">
                            <div class="flash-sales-item__thumb d-sm-block d-none" style="height:40%; width:50%;">
                                <img src="{{ asset('uploads/products') }}/{{ $sproduct->image }}" alt="">
                            </div>
                            <div class="flash-sales-item__content ms-sm-auto">
                                <h6 class="text-32 mb-20">{{ $sproduct->name }}</h6>
                                <div class="reviews-group d-flex">
                                    <span class="text-xs fw-bold text-gray-600">{{ $medianRating }}</span>
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
                                            @if($isHalfStar) fill="url(#halfStarGradient-{{ $sproduct->id }}-{{ $i }})"
                                            @else fill="{{ $fillColor }}" @endif>
                                            @if($isHalfStar)
                                                <defs>
                                                    {{-- Define a linear gradient for the half-filled star --}}
                                                    {{-- id needs to be unique, so we use product ID and star index --}}
                                                    <linearGradient id="halfStarGradient-{{ $sproduct->id }}-{{ $i }}" x1="0%" y1="0%" x2="100%" y2="0%">
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
                                    <span class="text-xs fw-bold text-gray-600">({{ $sproduct->reviews->count() }})</span>
                                </div>
                                <div class="countdown" id="countdown1">
                                    <p>{{ $sproduct->category->name }}</p>
                                    @if ($sproduct->sale_price)
                                        원<s>{{number_format(floatval($sproduct->regular_price), 0)}}</s> 원{{number_format(floatval($sproduct->sale_price), 0)}}
                                    @else
                                        원{{number_format(floatval($sproduct->regular_price), 0)}}
                                    @endif
                                </div>
                                <a href="{{ route('shop.product.details', ['product_slug' => $sproduct->slug]) }}"
                                    class="btn btn-main d-inline-flex align-items-center rounded-pill gap-8 mt-24">
                                    Shop Now
                                    <span class="icon text-xl d-flex"><i class="ph ph-arrow-right"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div>
                </div>
            </div>
        </div>
    </section>

    <div class="product mt-24">
        <div class="container container-lg">
            <div class="row gy-4 g-12">
                @foreach ($sproducts as $sproduct)
                <?php
                    $ratings = $sproduct->reviews->pluck('rating')->toArray();
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
                    <div class="col-xxl-2 col-lg-3 col-sm-4 col-6">
                        <div
                            class="product-card px-8 py-16 border border-gray-100 hover-border-main-600 rounded-16 position-relative transition-2">
                            @if (Cart::instance('cart')->content()->where('id', $sproduct->id)->count() > 0)
                                <a href="{{ route('cart.index') }}"
                                    class="product-card__cart btn bg-main-50 text-main-600 hover-bg-main-600 hover-text-white py-11 px-24 rounded-pill flex-align gap-8 position-absolute inset-block-start-0 inset-inline-end-0 me-16 mt-16">
                                    Go to cart <i class="ph ph-shopping-cart"></i>
                                </a>
                            @else
                                <form name="addtocart-form" method="post" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $sproduct->id }}" />
                                    <input type="hidden" name="quantity" value="1" />
                                    <input type="hidden" name="name" value="{{ $sproduct->name }}" />
                                    <input type="hidden" name="price"
                                        value="{{ $sproduct->sale_price == '' ? $sproduct->regular_price : $sproduct->sale_price }}" />
                                    <button type = "submit"@if($sproduct->quantity <= 0 || $sproduct->stock_status == 'outstock') disabled @endif
                                        class="product-card__cart btn bg-main-50 text-main-600 hover-bg-main-600 hover-text-white py-11 px-24 rounded-pill flex-align gap-8 position-absolute inset-block-start-0 inset-inline-end-0 me-16 mt-16"
                                        data-aside="cartDrawer" title="Add To Cart">
                                            @if($sproduct->quantity <= 0 || $sproduct->stock_status == 'outstock')
                                                Out of Stock
                                            @else
                                                Add To Cart<i class="ph ph-shopping-cart"></i>
                                            @endif
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('shop.product.details', ['product_slug' => $sproduct->slug]) }}"
                                class="product-card__thumb flex-center">
                                <img src="{{ asset('uploads/products') }}/{{ $sproduct->image }}" alt="">
                            </a>
                            <div class="product-card__content mt-12">
                                <div class="product-card__price mb-16">
                                    @if ($sproduct->sale_price)
                                        <span
                                            class="text-gray-400 text-md fw-semibold text-decoration-line-through">원{{number_format(floatval($sproduct->regular_price), 0)}}</span>
                                        <span class="text-heading text-md fw-semibold ">원{{number_format(floatval($sproduct->sale_price), 0)}}<span
                                                class="text-gray-500 fw-normal">/Kg</span> </span>
                                    @else
                                        원{{number_format(floatval($sproduct->regular_price), 0)}}
                                    @endif
                                </div>
                                <div class="flex-align gap-6">
                                    <span class="text-xs fw-bold text-gray-600">{{ $medianRating }}</span>
                                    <span class="text-15 fw-bold text-warning-600 d-flex"><i
                                            class="ph-fill ph-star"></i></span>
                                    <span class="text-xs fw-bold text-gray-600">({{ $sproduct->reviews->count() }})</span>
                                </div>
                                <h6 class="title text-lg fw-semibold mt-12 mb-8">
                                    <a href="{{ route('shop.product.details', ['product_slug' => $sproduct->slug]) }}"
                                        class="link text-line-2">{{ $sproduct->name }}</a>
                                </h6>
                                <div class="flex-align gap-4">
                                    <span class="text-gray-500 text-xs">{{ $sproduct->category->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- ========================= flash sales End ================================ -->

    <!-- ========================= Featured Start ================================ -->
    <section class="hot-deals pt-80">
        <div class="container container-lg">
            <div class="section-heading">
                <div class="flex-between flex-wrap gap-8">
                    <h5 class="mb-0">Featured Products</h5>
                    <div class="flex-align gap-16">
                        <a href="{{ route('shop.index') }}"
                            class="text-sm fw-medium text-gray-700 hover-text-main-600 hover-text-decoration-underline">View
                            All Deals</a>
                        <div class="flex-align gap-8">
                            <button type="button" id="deals-prev"
                                class="slick-prev slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1">
                                <i class="ph ph-caret-left"></i>
                            </button>
                            <button type="button" id="deals-next"
                                class="slick-next slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1">
                                <i class="ph ph-caret-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-12">
                <div class="col-md-4">
                    <div class="hot-deals position-relative rounded-16 bg-main-600 overflow-hidden p-28 z-1 text-center">
                        <img src="assets/images/shape/offer-shape.png" alt=""
                            class="position-absolute inset-block-start-0 inset-inline-start-0 z-n1 w-100 h-100 opacity-6">
                        <div class="hot-deals__thumb">
                            @if ($ads && $ads->image)
                                <img src="{{ asset('uploads/ads/' . $ads->image) }}" class="effect8" alt="">
                            @else
                            <img src="{{ asset('assets/images/freedelivery.jpg') }}" alt="">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="hot-deals-slider arrow-style-two">
                        @foreach ($fproducts as $fproduct)
                        <?php
                    $ratings = $fproduct->reviews->pluck('rating')->toArray();
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
                                <div
                                    class="product-card h-100 p-8 border border-gray-100 hover-border-main-600 rounded-16 position-relative transition-2">
                                    <span class="product-card__badge bg-info-600 px-8 py-4 text-sm text-white">Featured
                                    </span>
                                    <a href="{{ route('shop.product.details', ['product_slug' => $fproduct->slug]) }}"
                                        class="product-card__thumb flex-center">
                                        <img src="{{ asset('uploads/products') }}/{{ $fproduct->image }}"
                                            alt="">
                                    </a>
                                    <div class="product-card__content p-sm-2 w-100">
                                        <h6 class="title text-lg fw-semibold mt-12 mb-8">
                                            <a href="{{ route('shop.product.details', ['product_slug' => $fproduct->slug]) }}"
                                                class="link text-line-2">{{ $fproduct->name }}</a>
                                        </h6>
                                        <div class="flex-align gap-4">
                                            <span class="text-gray-500 text-xs">{{ $fproduct->category->name }}</span>
                                        </div>

                                        <div class="product-card__content mt-12">
                                            <div class="product-card__price mb-8">
                                                @if ($fproduct->sale_price)
                                                    <span
                                                        class="text-gray-400 text-md fw-semibold text-decoration-line-through">원{{number_format(floatval($fproduct->regular_price), 0)}}</span>
                                                    <span
                                                        class="text-heading text-md fw-semibold ">원{{number_format(floatval($fproduct->sale_price), 0)  }}<span
                                                            class="text-gray-500 fw-normal">/Kg</span> </span>
                                                @else
                                                    원{{number_format(floatval($fproduct->regular_price), 0)}}
                                                @endif
                                            </div>
                                            <div class="flex-align gap-6">
                                                <span class="text-xs fw-bold text-gray-600">{{ $medianRating }}</span>
                                                <span class="text-15 fw-bold text-warning-600 d-flex"><i
                                                        class="ph-fill ph-star"></i></span>
                                                <span
                                                    class="text-xs fw-bold text-gray-600">({{ $fproduct->reviews->count() }})</span>
                                            </div>
                                            <div class="carts" style="display: flex">
                                                @if (Cart::instance('cart')->content()->where('id', $fproduct->id)->count() > 0)
                                                    <a href="{{ route('cart.index') }}"
                                                        class="product-card__cart btn bg-main-50 text-main-600 hover-bg-main-600 hover-text-white py-11 px-24 rounded-pill flex-align gap-8 mt-24 w-100 justify-content-center">
                                                        Go To Cart <i class="ph ph-shopping-cart"></i>
                                                    </a>
                                                @else
                                                    <form name="addtocart-form" method="post" class="w-100"
                                                        action="{{ route('cart.add') }}"> 
                                                        @csrf
                                                        <input type="hidden" name="id"
                                                            value="{{ $fproduct->id }}" />
                                                        <input type="hidden" name="quantity" value="1" />
                                                        <input type="hidden" name="name"
                                                            value="{{ $fproduct->name }}" />
                                                        <input type="hidden" name="price"
                                                            value="{{ $fproduct->sale_price == '' ? $fproduct->regular_price : $fproduct->sale_price }}" />
                                                        <button type = "submit" @if($fproduct->quantity <= 0 || $fproduct->stock_status == 'outstock') disabled @endif data-aside="cartDrawer" title="Add To Cart" class="product-card__cart btn bg-main-50 text-main-600 hover-bg-main-600 hover-text-white py-11 px-24 rounded-pill flex-align gap-8 mt-24 w-100 justify-content-center">
                                                            @if($fproduct->quantity <= 0 || $fproduct->stock_status == 'outstock')
                                                                Out of Stock
                                                            @else
                                                                Add To Cart<i class="ph ph-shopping-cart"></i>
                                                            @endif
                                                        </button>
                                                    </form>
                                                @endif
                                                @if (Cart::instance('wishlist')->content()->where('id', $fproduct->id)->count() > 0)
                                                    <form method="POST"
                                                        action="{{ route('wishlist.item.remove', ['rowId' => Cart::instance('wishlist')->content()->where('id', $fproduct->id)->first()->rowId]) }}">
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
                                                            value="{{ $fproduct->id }}" />
                                                        <input type="hidden" name="quantity" value="1" />
                                                        <input type="hidden" name="name"
                                                            value="{{ $fproduct->name }}" />
                                                        <input type="hidden" name="price"
                                                            value="{{ $fproduct->sale_price == '' ? $fproduct->regular_price : $fproduct->sale_price }}" />
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
            </div>
        </div>
    </section>
    <!-- ========================= hot-deals End ================================ -->

    <!-- ========================= Recommended Start ================================ -->
    
    <section class="recommended pt-80 pb-80">
        <div class="container container-lg">
            <div class="section-heading flex-between flex-wrap gap-16">
                <h5 class="mb-0">Recommended for you</h5>
                <ul class="nav common-tab nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-all-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all"
                            aria-selected="true">All</button>
                    </li>
                    @foreach ($categories as $category)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="{{ $category->id }}" data-bs-toggle="pill"
                                data-bs-target="#{{ $category->name }}" type="button" role="tab"
                                aria-controls="{{ $category->name }}"
                                aria-selected="false">{{ $category->name }}</button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab"
                    tabindex="0">
                    <div class="row g-12">
                        @foreach ($products as $product)
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
                            <div class="col-xxl-2 col-lg-3 col-sm-4 col-6">
                                <div
                                    class="product-card h-100 p-8 border border-gray-100 hover-border-main-600 rounded-16 position-relative transition-2">
                                    <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}"
                                        class="product-card__thumb flex-center">
                                        <img src="{{ asset('uploads/products') }}/{{ $product->image }}" alt="">
                                    </a>
                                    <div class="product-card__content p-sm-2 w-100">
                                        <h6 class="title text-lg fw-semibold mt-12 mb-8">
                                            <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}"
                                                class="link text-line-2">{{ $product->name }}</a>
                                        </h6>
                                        <div class="flex-align gap-4">
                                            <span class="text-main-600 text-md d-flex"><i
                                                    class="ph-fill ph-storefront"></i></span>
                                            <span class="text-gray-500 text-xs">{{ $product->category->name }}</span>
                                        </div>

                                        <div class="product-card__content mt-12">
                                            <div class="product-card__price mb-8">
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
                                            <div class="flex-align gap-6">
                                                <span class="text-xs fw-bold text-gray-600">{{ $medianRating }}</span>
                                                <span class="text-15 fw-bold text-warning-600 d-flex"><i
                                                        class="ph-fill ph-star"></i></span>
                                                <span
                                                    class="text-xs fw-bold text-gray-600">({{ $product->reviews->count() }})</span>
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
                                                        <button type = "submit" data-aside="cartDrawer" @if($product->quantity <= 0 || $product->stock_status == 'outstock') disabled @endif title="Add To Cart" class="product-card__cart btn bg-main-50 text-main-600 hover-bg-main-600 hover-text-white py-11 px-24 rounded-pill flex-align gap-8 mt-24 w-100 justify-content-center">
                                                            @if($product->quantity <= 0 || $product->stock_status == 'outstock')
                                                                Out of Stock
                                                            @else
                                                                Add To Cart<i class="ph ph-shopping-cart"></i>
                                                            @endif
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
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @foreach ($categories as $category)
                    <div class="tab-pane fade" id="{{ $category->name }}" role="tabpanel"
                        aria-labelledby="{{ $category->id }}" tabindex="0">
                        <div class="row g-12">
                            @foreach ($category->products as $product)
                                <div class="col-xxl-2 col-lg-3 col-sm-4 col-6">
                                    <div
                                        class="product-card h-100 p-8 border border-gray-100 hover-border-main-600 rounded-16 position-relative transition-2">
                                        <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}"
                                            class="product-card__thumb flex-center">
                                            <img src="{{ asset('uploads/products') }}/{{ $product->image }}"
                                                alt="">
                                        </a>
                                        <div class="product-card__content p-sm-2 w-100">
                                            <h6 class="title text-lg fw-semibold mt-12 mb-8">
                                                <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}"
                                                    class="link text-line-2">{{ $product->name }}</a>
                                            </h6>
                                            <div class="flex-align gap-4">
                                                <span class="text-main-600 text-md d-flex"><i
                                                        class="ph-fill ph-storefront"></i></span>
                                                <span class="text-gray-500 text-xs">{{ $product->category->name }}</span>
                                            </div>

                                            <div class="product-card__content mt-12">
                                                <div class="product-card__price mb-8">
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
                                                <div class="flex-align gap-6">
                                                    <span class="text-xs fw-bold text-gray-600">{{ $medianRating }}</span>
                                                    <span class="text-15 fw-bold text-warning-600 d-flex"><i
                                                            class="ph-fill ph-star"></i></span>
                                                    <span
                                                        class="text-xs fw-bold text-gray-600">({{ $product->reviews->count() }})</span>
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
                                                        <button type = "submit" data-aside="cartDrawer" @if($product->quantity <= 0 || $product->stock_status == 'outstock') disabled @endif title="Add To Cart" class="product-card__cart btn bg-main-50 text-main-600 hover-bg-main-600 hover-text-main-50 py-11 px-24 rounded-pill flex-align gap-8 mt-24 w-100 justify-content-center">
                                                            @if($product->quantity <= 0 || $product->stock_status == 'outstock')
                                                                Out of Stock
                                                            @else
                                                                Add To Cart<i class="ph ph-shopping-cart"></i>
                                                            @endif 
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
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <div class = "flex item-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>
@endsection
