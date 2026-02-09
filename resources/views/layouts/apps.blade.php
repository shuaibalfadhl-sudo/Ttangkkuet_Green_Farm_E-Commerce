<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title> Ttangkkuet Green Farm</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}" type="image/x-icon">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- select 2 -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <!-- Slick -->
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
    <!-- Wow -->
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
    <!-- Main css -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    @stack('styles')
</head>

<body>

    <!--==================== Preloader Start ====================-->
    <style>
        .preloader {
    display: none;
    position: fixed;
    inset: 0;
    background: white;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
    </style>
    <div class="preloader">
        <img src="{{ asset('assets/images/icon/preloader.gif') }}" alt="">
        <!-- <div class="loader"></div> -->
    </div>
    <!--==================== Preloader End ====================-->

    <!--==================== Overlay Start ====================-->
    <div class="overlay"></div>
    <!--==================== Overlay End ====================-->

    <!--==================== Sidebar Overlay End ====================-->
    <div class="side-overlay"></div>
    <!--==================== Sidebar Overlay End ====================-->

    <!-- ==================== Scroll to Top End Here ==================== -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- ==================== Scroll to Top End Here ==================== -->

    <!-- ==================== Message pop up ==================== -->
    

    <!-- ==================== Message pop up end ==================== -->

    <!-- ==================== Search Box Start Here ==================== -->
    <form action="{{ route('shop.index') }}" method="GET" class="search-box">
        @csrf
        <button type="button"
            class="search-box__close position-absolute inset-block-start-0 inset-inline-end-0 m-16 w-48 h-48 border border-gray-100 rounded-circle flex-center text-white hover-text-gray-800 hover-bg-white text-2xl transition-1">
            <i class="ph ph-x"></i>
        </button>
        <div class="container">
            <div class="position-relative">
                <input type="text" name="query" class="form-control py-16 px-24 text-xl rounded-pill pe-64"
                    placeholder="Search for a product or brand">
                <button type="submit"
                    class="w-48 h-48 bg-main-600 rounded-circle flex-center text-xl text-white position-absolute top-50 translate-middle-y inset-inline-end-0 me-8">
                    <i class="ph ph-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </form>
    <!-- ==================== Search Box End Here ==================== -->

    <!-- ==================== Mobile Menu Start Here ==================== -->
    <div class="mobile-menu scroll-sm d-lg-none d-block">
        <button type="button" class="close-button"> <i class="ph ph-x"></i> </button>
        <a href="{{ route('home.index') }}" class="mobile-menu__logo" id="mobile-logo-inner">
            @if (!empty($logo) && !empty($logo->main_logo))
                <img
                    id="logo_mobile_1"
                    alt="Site Logo"
                    src="{{ asset('uploads/logo/main/' . $logo->main_logo) }}"
                    data-light="{{ asset('uploads/logo/main/' . $logo->main_logo) }}"
                    data-dark="{{ asset('uploads/logo/main/' . $logo->main_logo) }}">
            @else
                <img
                    id="logo_mobile_1"
                    alt="Default Logo"
                    src="{{ asset('assets/images/logo2.png') }}"
                    data-light="{{ asset('assets/images/logo2.png') }}"
                    data-dark="{{ asset('assets/images/logo2.png') }}">
            @endif
        </a>  
        <!-- Nav Menu Start -->
        <ul class="nav-menu flex-align nav-menu--mobile">
            @if(Auth::check())
                <li class="nav-menu__item">
                    <a href="{{ Auth::user()->utype === 'ADM' ? route('admin.index') : route('user.index') }}" class="nav-menu__link"><i class="ph ph-user-circle"></i> Profile</a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{ route('home.index') }}" class="nav-menu__link"><i class="ph ph-house"></i> Home</a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{route('cart.index')}}" class="nav-menu__link flex-align gap-4"> <span class="d-flex flex-align gap-4"> <i class="ph ph-shopping-cart"></i> Cart 
                        </span>
                        @if (Cart::instance('cart')->content()->count() > 0)
                            <span
                                class="w-16 h-16 flex-center rounded-circle bg-main-600 text-white text-xs"> {{ Cart::instance('cart')->content()->count() }}
                            </span>
                        @endif
                    </a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{route('wishlist.index')}}" class="nav-menu__link flex-align gap-4"> <span class="d-flex flex-align gap-4"> <i class="ph ph-heart"></i> Wishlist 
                        </span>
                        @if (Cart::instance('wishlist')->content()->count() > 0)
                            <span
                                class="w-16 h-16 flex-center rounded-circle bg-main-600 text-white text-xs"> {{ Cart::instance('wishlist')->content()->count() }}
                            </span>
                        @endif
                    </a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{ route('user.password') }}" class="nav-menu__link"><i class="ph ph-lock"></i> Password</a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{ route('user.address') }}" class="nav-menu__link"><i class="ph ph-map-pin"></i> Address</a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{ route('user.orders') }}" class="nav-menu__link"><i class="ph ph-shopping-bag"></i> Order</a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{ route('user.messages.index') }}" class="nav-menu__link"><i class="ph ph-chat-circle-dots"></i> Message</a>
                </li>
                <li class="on-hover-item nav-menu__item">
                    <a href="{{ route('shop.index') }}" class="nav-menu__link"><i class="ph ph-shopping-bag"></i> Shop</a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{ route('home.contact') }}" class="nav-menu__link"><i class="ph ph-phone"></i> Contact Us</a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{ route('home.about') }}" class="nav-menu__link"><i class="ph ph-info"></i> About Us</a>
                </li>
                <li class="nav-menu__item">
                    <a href="" class="nav-menu__link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ph ph-sign-out"></i> Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @else
                <li class="nav-menu__item">
                    <a href="{{ route('login') }}" class="nav-menu__link"><i class="ph ph-user-circle"></i> Login</a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{ route('home.index') }}" class="nav-menu__link"><i class="ph ph-house"></i> Home</a>
                </li>
                <li class="on-hover-item nav-menu__item">
                    <a href="{{ route('shop.index') }}" class="nav-menu__link"><i class="ph ph-shopping-bag"></i> Shop</a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{ route('home.contact') }}" class="nav-menu__link"><i class="ph ph-phone"></i> Contact Us</a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{ route('home.about') }}" class="nav-menu__link"><i class="ph ph-info"></i> About Us</a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{route('cart.index')}}" class="nav-menu__link flex-align gap-4"> <span class="d-flex flex-align gap-4"> <i class="ph ph-shopping-cart"></i> Cart 
                        </span>
                        @if (Cart::instance('cart')->content()->count() > 0)
                            <span
                                class="w-16 h-16 flex-center rounded-circle bg-main-600 text-white text-xs"> {{ Cart::instance('cart')->content()->count() }}
                            </span>
                        @endif
                    </a>
                </li>
                <li class="nav-menu__item">
                    <a href="{{route('wishlist.index')}}" class="nav-menu__link flex-align gap-4"> <span class="d-flex flex-align gap-4"> <i class="ph ph-heart"></i> Wishlist 
                        </span>
                        @if (Cart::instance('wishlist')->content()->count() > 0)
                            <span
                                class="w-16 h-16 flex-center rounded-circle bg-main-600 text-white text-xs"> {{ Cart::instance('wishlist')->content()->count() }}
                            </span>
                        @endif
                    </a>
                </li> 
            @endif
        </ul>
        <!-- Nav Menu End -->
        
    </div>
    </div>
    </div>
    <!-- ==================== Mobile Menu End Here ==================== -->


    <!-- ======================= Middle Top Start ========================= -->
    <div class="header-top bg-main-600 flex-between">
        <div class="container container-lg">
            <div class="flex-between flex-wrap gap-8">
                <ul class="flex-align flex-wrap d-none d-md-flex">
                    <li class="border-right-item"><a href="{{ route('home.about') }}"
                            class="text-white text-sm hover-text-decoration-underline">About us</a></li>
                    <li class="border-right-item"><a href="{{ route('home.delivery.policy') }}"
                            class="text-white text-sm hover-text-decoration-underline">Free Delivery</a></li>
                    <li class="border-right-item"><a href="{{ route('home.return.policy') }}"
                            class="text-white text-sm hover-text-decoration-underline">Returns Policy</a></li>
                </ul>
                <ul class="header-top__right flex-align flex-wrap">
                    <li class="on-hover-item has-submenu arrow-white border-right-item">
                        @guest
                            <a href="{{ route('login') }}" class="text-white text-sm py-8 flex-align gap-6">
                                <span class="icon text-md d-flex"> <i class="ph ph-user-circle"></i> </span>
                                <span class="hover-text-decoration-underline">Login</span>
                            </a>
                        @else
                            {{-- Link that shows the logged-in user's name and serves as the dropdown trigger --}}
                            <a class="selected-text text-white text-sm py-8 flex-align gap-6">
                                <span class="icon text-md d-flex"> <i class="ph ph-user-circle"></i> </span>
                                <span class="hover-text-decoration-underline">{{ Auth::user()->name }}</span>
                            </a>

                            {{-- Dropdown Menu for Logged-In User --}}
                            <ul class="selectable-text-list on-hover-dropdown common-dropdown common-dropdown--sm px-0 py-8 end-0 start-auto">
                                @if (Auth::user()->utype === 'ADM')
                                <li>
                                    {{-- Determine the profile route based on user type (ADM for Admin, otherwise User) --}}
                                    <a href="{{ Auth::user()->utype === 'ADM' ? route('admin.index') : route('user.index') }}"
                                        class="hover-bg-gray-100 text-gray-500 text-xs py-6 px-16 flex-align gap-8 rounded-0">
                                        <span class="icon text-md d-flex"> <i class="ph ph-user"></i> </span>
                                        Admin Profile
                                    </a>
                                </li>
                                <li>
                                    {{-- Determine the profile route based on user type (ADM for Admin, otherwise User) --}}
                                    <a href="{{route('user.index') }}"
                                        class="hover-bg-gray-100 text-gray-500 text-xs py-6 px-16 flex-align gap-8 rounded-0">
                                        <span class="icon text-md d-flex"> <i class="ph ph-user"></i> </span>
                                        User Profile
                                    </a>
                                </li>
                                {{-- 2. Logout Link --}}
                                <li>
                                    {{-- This link will submit a form to the 'logout' route --}}
                                    <a href="{{ route('logout') }}"
                                        class="hover-bg-gray-100 text-gray-500 text-xs py-6 px-16 flex-align gap-8 rounded-0"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span class="icon text-md d-flex"> <i class="ph ph-sign-out"></i> </span>
                                        Logout
                                    </a>
                                </li>
                                @else
                                <li>
                                    {{-- Determine the profile route based on user type (ADM for Admin, otherwise User) --}}
                                    <a href="{{ Auth::user()->utype === 'ADM' ? route('admin.index') : route('user.index') }}"
                                        class="hover-bg-gray-100 text-gray-500 text-xs py-6 px-16 flex-align gap-8 rounded-0">
                                        <span class="icon text-md d-flex"> <i class="ph ph-user"></i> </span>
                                        View Profile
                                    </a>
                                </li>

                                {{-- 2. Logout Link --}}
                                <li>
                                    {{-- This link will submit a form to the 'logout' route --}}
                                    <a href="{{ route('logout') }}"
                                        class="hover-bg-gray-100 text-gray-500 text-xs py-6 px-16 flex-align gap-8 rounded-0"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span class="icon text-md d-flex"> <i class="ph ph-sign-out"></i> </span>
                                        Logout
                                    </a>
                                </li>
                                @endif
                            </ul>
                                
                            {{-- Hidden Logout Form for Laravel's POST request requirement --}}
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>

                        @endguest
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- ======================= Middle Top End ========================= -->

    <!-- ======================= Middle Header Start ========================= -->
    <header class="header-middle bg-color-one border-bottom border-gray-100">
        <div class="container container-lg">
            <nav class="header-inner flex-between">
                <!-- Logo Start -->
                <div class="logo">
                    <a href="{{ route('home.index') }}" class="link" id="site-logo-inner">
                        @if (!empty($logo) && !empty($logo->main_logo))
                            <img
                                id="logo_header_2"
                                alt="Site Logo"
                                src="{{ asset('uploads/logo/main/' . $logo->main_logo) }}"
                                data-light="{{ asset('uploads/logo/main/' . $logo->main_logo) }}"
                                data-dark="{{ asset('uploads/logo/main/' . $logo->main_logo) }}">
                        @else
                            <img
                                id="logo_header_2"
                                alt="Default Logo"
                                src="{{ asset('assets/images/logo2.png') }}"
                                data-light="{{ asset('assets/images/logo2.png') }}"
                                data-dark="{{ asset('assets/images/logo2.png') }}">
                        @endif
                    </a>
                </div> 
                <!-- Logo End  -->

                <!-- form search Start -->
                <div class="search-category d-flex h-48 select-border-end-0 radius-end-0 search-form d-sm-flex d-none">
                    <form action="{{ route('shop.index') }}" method="GET" class="d-flex w-100">
                        <div class="search-form__wrapper position-relative w-100">
                            <input type="text" name="query" class="search-form__input common-input py-13 ps-16 pe-18 rounded-end-pill pe-44" placeholder="Search for a product or brand">
                            <button type="submit" class="w-32 h-32 bg-main-600 rounded-circle flex-center text-xl text-white position-absolute top-50 translate-middle-y inset-inline-end-0 me-8">
                                <i class="ph ph-magnifying-glass"></i>
                            </button>
                            <div id="box-content-search"></div>
                        </div>
                    </form>
                </div>
                <style>
                    #box-content-search {
                        display: none;
                        /* Hidden by default */
                        position: absolute;
                        top: 100%;
                        /* Position it right below the search input */
                        left: 0;
                        z-index: 10;
                        /* Ensure it appears on top of other content */
                        width: 100%;
                        /* Make it the same width as the search bar */
                        background-color: #fff;
                        /* White background */
                        border: 1px solid #e0e0e0;
                        border-top: none;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        border-radius: 0 0 8px 8px;
                        /* Rounded corners at the bottom */
                        padding: 10px;
                        margin-top: -1px;
                        /* Overlap with the border of the input field */
                    }
                </style>
                <!-- form search end -->

                <!-- Header Middle Right start -->
                <div class="header-right flex-align d-lg-block d-none me-30">
                    <div class="flex-align flex-wrap gap-12">
                        <button type="button" class="search-icon flex-align d-lg-none d-flex gap-4 item-hover">
                            <span class="text-2xl text-gray-700 d-flex position-relative item-hover__text">
                                <i class="ph ph-magnifying-glass"></i>
                            </span>
                        </button>
                        <a href="{{ route('wishlist.index') }}" class="flex-align gap-4 item-hover">
                            <span class="text-2xl text-gray-700 d-flex position-relative me-6 mt-6 item-hover__text">
                                <i class="ph ph-heart"></i>
                                @if (Cart::instance('wishlist')->content()->count() > 0)
                                    <span
                                        class="w-16 h-16 flex-center rounded-circle bg-main-600 text-white text-xs position-absolute top-n6 end-n4">{{ Cart::instance('wishlist')->content()->count() }}</span>
                                @endif
                            </span>
                            <span class="text-md text-gray-500 item-hover__text d-none d-lg-flex">Wishlist</span>
                        </a>
                        <span class="d-none d-lg-flex"></span>
                        <a href="{{ route('cart.index') }}" class="flex-align gap-4 item-hover">
                            <span class="text-2xl text-gray-700 d-flex position-relative me-6 mt-6 item-hover__text ">
                                <i class="ph ph-shopping-cart-simple"></i>
                                @if (Cart::instance('cart')->content()->count() > 0)
                                    <span
                                        class="w-16 h-16 flex-center rounded-circle bg-main-600 text-white text-xs position-absolute top-n6 end-n4">{{ Cart::instance('cart')->content()->count() }}</span>
                                @endif
                            </span>
                            <span class="text-md text-gray-500 item-hover__text d-none d-lg-flex">Cart</span>
                        </a>
                    </div>
                </div>
                <!-- Header Middle Right End  -->
            </nav>
        </div>
    </header>
    <!-- ======================= Middle Header End ========================= -->

    <!-- ==================== Header Start Here ==================== -->
    <header class="header bg-white border-bottom border-gray-100">
        <div class="container container-lg">
            <nav class="header-inner d-flex justify-content-between gap-8">
                <div class="flex-align menu-category-wrapper">

                    <!-- Category Dropdown Start -->
                    <div class="category on-hover-item">
                        <button type="button"
                            class="category__button flex-align gap-8 fw-medium p-16 border-end border-start border-gray-100 text-heading">
                            <span class="icon text-2xl d-xs-flex d-none"><i class="ph ph-dots-nine"></i></span>
                            <span class="d-sm-flex d-none">All Categories</span>
                            <span class="arrow-icon text-xl d-flex"><i class="ph ph-caret-down"></i></span>
                        </button>

                        <div
                            class="responsive-dropdown on-hover-dropdown common-dropdown nav-submenu p-0 submenus-submenu-wrapper">

                            <button type="button"
                                class="close-responsive-dropdown rounded-circle text-xl position-absolute inset-inline-end-0 inset-block-start-0 mt-4 me-8 d-lg-none d-flex">
                                <i class="ph ph-x"></i> </button>

                            <!-- Logo Start -->
                            <div class="logo px-16 d-lg-none d-block">
                                <a href="{{ route('home.index') }}" class="link">
                                    <img src="assets/images/logo2.png" alt="Logo">
                                </a>
                            </div>
                            <!-- Logo End -->

                            <ul class="scroll-sm p-0 py-8 w-300 max-h-400 overflow-y-auto">
                                @foreach ($categories as $category)
                                    <li class="has-submenus-submenu">
                                        <a href="{{ route('shop.index', ['categories' => $category->id]) }}"
                                            class="text-gray-500 text-15 py-12 px-16 flex-align gap-8 rounded-0">
                                            <span class="text-xl d-flex"><i class="ph ph-carrot"></i></span>
                                            <span>{{ $category->name }}</span>
                                            <span class="icon text-md d-flex ms-auto"><i
                                                    class="ph ph-caret-right"></i></span>
                                        </a>

                                        <div class="submenus-submenu py-16">
                                            <h6 class="text-lg px-16 submenus-submenu__title">{{ $category->name }}
                                            </h6>
                                            <ul class="submenus-submenu__list max-h-300 overflow-y-auto scroll-sm">
                                                @foreach ($category->products as $product)
                                                    <li>
                                                        <a
                                                            href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">{{ $product->name }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- Category Dropdown End  -->

                    <!-- Menu Start  -->
                    <div class="header-menu d-lg-block d-none">
                        <!-- Nav Menu Start -->
                        <ul class="nav-menu flex-align ">
                            <li class="nav-menu__item">
                                <a href="{{ route('home.index') }}" class="nav-menu__link">Home</a>
                            </li>
                            <li class="on-hover-item nav-menu__item">
                                <a href="{{ route('shop.index') }}" class="nav-menu__link">Shop</a>
                            </li>
                            <li class="nav-menu__item">
                                <a href="{{ route('home.contact') }}" class="nav-menu__link">Contact Us</a>
                            </li>
                        </ul>
                        <!-- Nav Menu End -->
                    </div>
                    <!-- Menu End  -->
                </div>

                <!-- Header Mobile Right start -->
                <div class="header-right flex-align">
                    <div id="google_language_change" class="m-10"></div>
                    <a href="tel:{{ preg_replace('/\D/', '', $contactInfo->phone ?? '') }}"
                        class="bg-main-600 text-white p-12 h-100 hover-bg-main-800 flex-align gap-8 text-lg d-lg-flex d-none">
                        <div class="d-flex text-32"><i class="ph ph-phone-call"></i></div>
                        {{ $contactInfo->phone ?? 'No phone number yet' }}
                    </a>
                    <div class="me-16 d-lg-none d-block">
                        <div class="flex-align flex-wrap gap-12">
                            <button type="button" class="search-icon flex-align d-lg-none d-flex gap-4 item-hover">
                                <span class="text-2xl text-gray-700 d-flex position-relative item-hover__text">
                                    <i class="ph ph-magnifying-glass"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="toggle-mobileMenu d-lg-none ms-3n text-gray-800 text-4xl d-flex"> <i
                            class="ph ph-list"></i> </button>
                </div>
                <!-- Header Right End  -->
            </nav>
        </div>
    </header>
    <!-- ==================== Header End Here ==================== -->
    @yield('content')
    <!-- ==================== Footer Start Here ==================== -->
    <footer class="footer py-120">
        <img src="assets/images/bg/body-bottom-bg.png" alt="BG" class="body-bottom-bg">
        <div class="container container-lg">
            <div class="footer-item-wrapper d-flex align-items-start flex-wrap">
                <div class="footer-item">
                    <div class="footer-item__logo">
                        <a href="{{ route('home.index') }}" id="footer-logo-inner">
                            @if (!empty($logo) && !empty($logo->main_logo))
                                <img
                                    id="logo_footer_1"
                                    alt="Site Logo"
                                    src="{{ asset('uploads/logo/main/' . $logo->main_logo) }}"
                                    data-light="{{ asset('uploads/logo/main/' . $logo->main_logo) }}"
                                    data-dark="{{ asset('uploads/logo/main/' . $logo->main_logo) }}">
                            @else
                                <img
                                    id="logo_footer_1"
                                    alt="Default Logo"
                                    src="{{ asset('assets/images/logo2.png') }}"
                                    data-light="{{ asset('assets/images/logo2.png') }}"
                                    data-dark="{{ asset('assets/images/logo2.png') }}">
                            @endif
                        </a>
                    </div>
                    <p class="mb-24">We at ttangkuet green farm, a team of farmers bringing our fresh products.</p>
                    <div class="flex-align gap-16 mb-16">
                        <span
                            class="w-32 h-32 flex-center rounded-circle bg-main-600 text-white text-md flex-shrink-0"><i
                                class="ph-fill ph-map-pin"></i></span>
                        <span class="text-md text-gray-900">
                            {{ $contactInfo->address ?? 'No address available' }}
                        </span>
                    </div>
                    <div class="flex-align gap-16 mb-16">
                        <span
                            class="w-32 h-32 flex-center rounded-circle bg-main-600 text-white text-md flex-shrink-0"><i
                                class="ph-fill ph-phone-call"></i></span>
                        <div class="flex-align gap-16 flex-wrap">
                            <a href="tel:{{ $contactInfo->phone ?? '' }}"
                            class="text-md text-gray-900 hover-text-main-600">
                            {{ $contactInfo->phone ?? 'No phone number yet' }}
                            </a>
                        </div>
                    </div>
                    <div class="flex-align gap-16 mb-16">
                        <span
                            class="w-32 h-32 flex-center rounded-circle bg-main-600 text-white text-md flex-shrink-0"><i
                                class="ph-fill ph-envelope"></i></span>
                        <a href="mailto:{{ $contactInfo->email ?? '' }}"
                        class="text-md text-gray-900 hover-text-main-600">
                        {{ $contactInfo->email ?? 'No email set' }}
                        </a>
                    </div>
                </div>

                <div class="footer-item">
                    <h6 class="footer-item__title">Information</h6>
                    <ul class="footer-menu">
                        <li class="mb-16">
                            <a href="{{route('home.privacy.policy')}}" class="text-gray-600 hover-text-main-600">Privacy Policy</a>
                        </li>
                        <li class="mb-16">
                            <a href="{{route('home.delivery.policy')}}" class="text-gray-600 hover-text-main-600">Free Delivery</a>
                        </li>
                        <li class="">
                            <a href="{{route('home.return.policy')}}" class="text-gray-600 hover-text-main-600">Return Policy</a>
                        </li> 
                    </ul>
                </div>

                <div class="footer-item">
                    <h6 class="footer-item__title">Customer Support</h6>
                    <ul class="footer-menu"> 
                        <li class="mb-16">
                            <a href="{{ route('user.messages.index') }}" class="text-gray-600 hover-text-main-600">Costumer Supprort</a>
                        </li>
                        <li class="mb-16">
                            <a href="{{ route('home.contact') }}" class="text-gray-600 hover-text-main-600">Contact
                                Us</a>
                        </li>
                        <li class="">
                            <a href="{{ route('shop.index') }}" class="text-gray-600 hover-text-main-600">Shop</a>
                        </li>
                    </ul>
                </div>
                @guest
                    <div class="footer-item">
                        <h6 class="footer-item__title">My Account</h6>
                        <ul class="footer-menu">
                            <li class="mb-16">
                                <a href="{{ route('login') }}" class="text-gray-600 hover-text-main-600">My Account</a>
                            </li>
                            <li class="mb-16">
                                <a href="{{ route('login') }}" class="text-gray-600 hover-text-main-600">Order
                                    History</a>
                            </li>
                            <li class="mb-16">
                                <a href="{{ route('cart.index') }}" class="text-gray-600 hover-text-main-600">Shoping
                                    Cart</a>
                            </li>
                            <li class="">
                                <a href="{{ route('wishlist.index') }}"
                                    class="text-gray-600 hover-text-main-600">Wishlist</a>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="footer-item">
                        <h6 class="footer-item__title">My Account</h6>
                        <ul class="footer-menu">
                            <li class="mb-16">
                                <a href="{{ Auth::user()->utype === 'ADM' ? route('admin.index') : route('user.index') }}"
                                    class="text-gray-600 hover-text-main-600">My Account</a>
                            </li>
                            <li class="mb-16">
                                <a href="{{ route('user.orders') }}" class="text-gray-600 hover-text-main-600">Order
                                    History</a>
                            </li>
                            <li class="mb-16">
                                <a href="{{ route('cart.index') }}" class="text-gray-600 hover-text-main-600">Shoping
                                    Cart</a>
                            </li>
                            <li class="">
                                <a href="{{ route('wishlist.index') }}"
                                    class="text-gray-600 hover-text-main-600">Wishlist</a>
                            </li>
                        </ul>
                    </div>
                @endguest

                <div class="footer-item">
                    <h6 class="footer-item__title">Categories</h6>
                    <ul class="footer-menu">
                        @foreach ($categories as $category)
                            <li class="mb-16">
                                <a href="{{ route('shop.index', ['categories' => $category->id]) }}"
                                    class="text-gray-600 hover-text-main-600">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="footer-item">
                    <h6 class="">Shop on The Go</h6>
                    <p class="mb-16">Ttangkkuet App is available. Get it now</p>

                    <ul class="flex-align gap-16">
                        @if(!empty($socialLinks?->facebook))
                            <li>
                                <a href="{{ $socialLinks->facebook }}" target="_blank"
                                    class="w-44 h-44 flex-center bg-main-100 text-main-600 text-xl rounded-circle hover-bg-main-600 hover-text-white">
                                    <i class="ph-fill ph-facebook-logo"></i>
                                </a>
                            </li>
                        @endif

                        @if(!empty($socialLinks?->twitter))
                            <li>
                                <a href="{{ $socialLinks->twitter }}" target="_blank"
                                    class="w-44 h-44 flex-center bg-main-100 text-main-600 text-xl rounded-circle hover-bg-main-600 hover-text-white">
                                    <i class="ph-fill ph-twitter-logo"></i>
                                </a>
                            </li>
                        @endif

                        @if(!empty($socialLinks?->instagram))
                            <li>
                                <a href="{{ $socialLinks->instagram }}" target="_blank"
                                    class="w-44 h-44 flex-center bg-main-100 text-main-600 text-xl rounded-circle hover-bg-main-600 hover-text-white">
                                    <i class="ph-fill ph-instagram-logo"></i>
                                </a>
                            </li>
                        @endif

                        @if(!empty($socialLinks?->kakaotalk))
                            <li>
                                <a href="{{ $socialLinks->kakaotalk }}" target="_blank"
                                    class="w-44 h-44 flex-center bg-main-100 text-main-600 text-xl rounded-circle hover-bg-main-600 hover-text-white">
                                    <i class="ph-fill ph-kakaotalk-logo">K</i>
                                </a>
                            </li>
                        @endif
                    </ul>

                </div>
            </div>
        </div>
    </footer>

    <!-- bottom Footer -->
    <div class="bottom-footer bg-color-one py-8">
        <div class="container container-lg">
            <div class="bottom-footer__inner flex-between flex-wrap gap-16 py-16">
                <p class="bottom-footer__text ">Ttangkkuet eCommerce &copy; 2025. All Rights Reserved </p>
            </div>
        </div>
    </div>
    <!-- ==================== Footer End Here ==================== -->



    <!-- Jquery js -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="{{ asset('assets/js/boostrap.bundle.min.js') }}"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="{{ asset('assets/js/phosphor-icon.js') }}"></script>
    <!-- Select 2 -->
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <!-- Slick js -->
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <!-- Slick js -->
    <script src="{{ asset('assets/js/count-down.js') }}"></script>
    <!-- wow js -->
    <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- main js -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
    
    <!-- Laravel Echo and Pusher JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @vite(['resources/js/app.js'])

    
    <!-- Google Translate -->
    <script type="text/javascript" src="http://translate.google.com/translate_a/element.js?cb=loadGoogleTranslate"></script>
    <script type="text/javascript">
        function loadGoogleTranslate() {
            new google.translate.TranslateElement({
                    pageLanguage: 'en'
                },
                "google_language_change"
            );
        }
    </script>
    <!-- AJAX for search -->
    <script>
    $(function() {
        const searchInput = $(".search-form__input");
        const resultsContainer = $("#box-content-search");

        // Show the dropdown on focus if there's a query
        searchInput.on("focus", function() {
            if ($(this).val().length > 2) {
                resultsContainer.show();
            }
        });

        // Hide the dropdown on blur (when user clicks away)
        // A small delay is added to allow a user to click a result link
        searchInput.on("blur", function() {
            setTimeout(function() {
                resultsContainer.hide();
            }, 200);
        });

        searchInput.on("keyup", function() {
            const searchQuery = $(this).val();
            const searchCategory = $(".js-example-basic-single").val();

            if (searchQuery.length > 0) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('home.search') }}",
                    data: {
                        query: searchQuery,
                        category: searchCategory
                    },
                    dataType: 'json',
                    success: function(data) {
                        resultsContainer.html(''); // Clear previous results
                        if (data.length > 0) {
                            $.each(data, function(index, item) {
                                var url = "{{ route('shop.product.details', ['product_slug' => 'product_slug_pls']) }}";
                                var link = url.replace('product_slug_pls', item.slug);

                                // Check for sale price and determine the price to display
                                var priceHtml = '';
                                if (item.sale_price) {
                                    priceHtml = `
                                        <span class="text-gray-400 text-md fw-semibold text-decoration-line-through">$${item.regular_price}</span>
                                        <span class="text-heading text-md fw-semibold ">$${item.sale_price}<span class="text-gray-500 fw-normal">/Qty</span></span>
                                    `;
                                } else {
                                    priceHtml = `<span class="text-heading text-md fw-semibold ">$${item.regular_price}<span class="text-gray-500 fw-normal">/Qty</span></span>`;
                                }

                                resultsContainer.append(`
                                    <ul>
                                        <li class="mb-10">
                                            <div class="divider"><hr></div>
                                        </li>
                                        <li class="product-item gap14 mb-10 w-100 hover-bg-main-50 hover-text-main" style="display: flex; align-items: center; cursor: pointer;" onclick="window.location.href='${link}'">
                                            <div class="product-image m-10">
                                                <img src="{{ asset('uploads/products/thumbnails') }}/${item.image}" alt="${item.image}">
                                            </div>
                                            <div class="product-details w-100">
                                                <h6 class="body-text product-name">${item.name}</h6> 
                                                <div class="product-price">
                                                    ${priceHtml}
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-10">
                                            <div class="divider"><hr></div>
                                        </li>
                                    </ul>
                                `);
                            });
                            resultsContainer.show();
                        } else {
                            resultsContainer.html('<p>No products found.</p>').show();
                        }
                    }
                });
            } else {
                resultsContainer.hide();
            }
        });
    });
    //redirect search icon click
    document.addEventListener('DOMContentLoaded', function() {
    const searchToggle = document.getElementById('searchToggle');
    const searchBox = document.getElementById('searchBox');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    // Toggle search box visibility
    searchToggle.addEventListener('click', function() {
        searchBox.classList.toggle('d-none');
        searchBox.classList.toggle('show');
        if (searchBox.classList.contains('show')) searchInput.focus();
    });

    // Fetch search suggestions via AJAX
    searchInput.addEventListener('keyup', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        fetch(`/search?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                searchResults.innerHTML = '';
                if (data.length === 0) {
                    searchResults.style.display = 'none';
                    return;
                }

                data.forEach(product => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = product.name;
                    li.addEventListener('click', () => {
                        window.location.href = `{{ route('shop.index') }}?query=${encodeURIComponent(product.name)}`;
                    });
                    searchResults.appendChild(li);
                });
                searchResults.style.display = 'block';
            })
            .catch(err => console.error('Search error:', err));
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchBox.contains(e.target) && !searchToggle.contains(e.target)) {
            searchBox.classList.add('d-none');
            searchBox.classList.remove('show');
            searchResults.style.display = 'none';
        }
    });
});
 document.addEventListener("DOMContentLoaded", function () {
    const preloader = document.querySelector(".preloader");

    // Hide loader on full reload
    if (performance.navigation.type === 1) {
        preloader.style.display = "none";
    }

    // Restore scroll position
    if (sessionStorage.getItem("scrollPosition")) {
        window.scrollTo(0, sessionStorage.getItem("scrollPosition"));
        sessionStorage.removeItem("scrollPosition");
    }

    // Detect if the last page and current page are the same
    const previousPage = sessionStorage.getItem("previousPage");
    const currentPage = window.location.pathname;

    // If same page → no preloader
    if (previousPage === currentPage) {
        preloader.style.display = "none";
    }

    // Save scroll and current page before unload
    window.addEventListener("beforeunload", function (e) {
        sessionStorage.setItem("scrollPosition", window.scrollY);
        sessionStorage.setItem("previousPage", currentPage);

        const nextUrl = document.activeElement?.href || '';
        const currentUrl = window.location.href;

        // Only show loader if navigating to a different page
        if (nextUrl && nextUrl !== currentUrl) {
            preloader.style.display = "flex";
        } else {
            // Stay silent (no loader) if redirect()->back or same URL
            preloader.style.display = "none";
        }
    });
});
</script>
</body>

</html>
