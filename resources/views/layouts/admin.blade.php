<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ttangkkuet') }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="surfside media" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/animate.min.css') }} ">
    <link rel="stylesheet" type="text/css" href="{{asset('css/animation.css') }} ">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.css') }} ">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-select.min.css') }} ">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css') }} ">
    <link rel="stylesheet" href="{{asset('font/fonts.css') }} ">
    <link rel="stylesheet" href="{{asset('icon/style.css') }} ">
    <link rel="shortcut icon" href="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}">
    <link rel="apple-touch-icon-precomposed" href="{{asset('images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.min.css') }} ">
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom.css') }} ">
    @stack("styles")
</head>

<body class="body">
    <div id="wrapper">
        <div id="page" class="">
            <div class="layout-wrap">

                <!-- <div id="preload" class="preload-container">
    <div class="preloading">
        <span></span>
    </div>
</div> -->

    <div class="section-menu-left">
        <div class="box-logo">
            <a href="{{ route('home.index') }}" id="site-logo-inner">
                @if (!empty($logo) && !empty($logo->main_logo))
                    <img
                        id="logo_header_1"
                        alt="Site Logo"
                        src="{{ asset('uploads/logo/main/' . $logo->main_logo) }}"
                        data-light="{{ asset('uploads/logo/main/' . $logo->main_logo) }}"
                        data-dark="{{ asset('uploads/logo/main/' . $logo->main_logo) }}">
                @else
                    <img
                        id="logo_header_1"
                        alt="Default Logo"
                        src="{{ asset('assets/images/logo2.png') }}"
                        data-light="{{ asset('assets/images/logo2.png') }}"
                        data-dark="{{ asset('assets/images/logo2.png') }}">
                @endif
            </a>
            <div class="button-show-hide">
                <i class="icon-menu-left"></i>
            </div>
        </div>
        <div class="center">
            <div class="center-item">
                <div class="center-heading">Main Home</div>
                <ul class="menu-list">
                    <li class="menu-item">
                        <a href="{{route('admin.index')}}" class="">
                            <div class="icon"><i class="icon-grid"></i></div>
                            <div class="text">Dashboard</div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="center-item">
                <ul class="menu-list">
                    <li class="menu-item has-children">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-shopping-cart"></i></div>
                            <div class="text">Products</div>
                        </a>
                        <ul class="sub-menu">
                            <li class="sub-menu-item">
                                <a href="{{route('admin.product.add')}}" class="">
                                    <div class="text">Add Product</div>
                                </a>
                            </li>
                            <li class="sub-menu-item">
                                <a href="{{route('admin.products')}}" class="">
                                    <div class="text">Products</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- <li class="menu-item has-children">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-layers"></i></div>
                            <div class="text">Brand</div>
                        </a>
                        <ul class="sub-menu">
                            <li class="sub-menu-item">
                                <a href="{{route('admin.brand.add')}}" class="">
                                    <div class="text">New Brand</div>
                                </a>
                            </li>
                            <li class="sub-menu-item">
                                <a href="{{route('admin.brands')}}" class="">
                                    <div class="text">Brands</div>
                                </a>
                            </li>
                        </ul>
                    </li> --}}
                    <li class="menu-item has-children">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-layers"></i></div>
                            <div class="text">Category</div>
                        </a>
                        <ul class="sub-menu">
                            <li class="sub-menu-item">
                                <a href="{{route('admin.category.add')}}" class="">
                                    <div class="text">New Category</div>
                                </a>
                            </li>
                            <li class="sub-menu-item">
                                <a href="{{route('admin.categories')}}" class="">
                                    <div class="text">Categories</div>
                                </a>
                            </li>
                        </ul>
                    </li>   
                    <li class="menu-item">
                        <a href="{{route('admin.orders')}}" class="">
                            <div class="icon"><i class="icon-file-plus"></i></div>
                            <div class="text">Order</div>
                        </a> 
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.slides')}}" class="">
                            <div class="icon"><i class="icon-image"></i></div>
                            <div class="text">Slides</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.coupons')}}" class="">
                            <div class="icon"><i class="icon-grid"></i></div>
                            <div class="text">Coupons</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.messages.index')}}" class="">
                            <div class="icon"><i class="icon-mail"></i></div>
                            <div class="text">Messages</div>
                            @if ($unreadCount > 0)
                                <div class="number">
                                    <span class="text-tiny">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                </div>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.contacts')}}" class="">
                            <div class="icon"><i class="icon-mail"></i></div>
                            <div class="text">Mail</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('admin.users')}}" class="">
                            <div class="icon"><i class="icon-user"></i></div>
                            <div class="text">Users</div>
                        </a>
                    </li>   
                    <li class="menu-item">
                        <a href="{{route('admin.settings')}}" class="">
                            <div class="icon"><i class="icon-settings"></i></div>
                            <div class="text">Settings</div>
                        </a>
                    </li>   
                    <li class="menu-item">
                        <form method="POST" action="{{route('logout')}}" id="logout-form" >
                            @csrf
                        <a href="{{route('logout')}}" class="" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <div class="icon"><i class="icon-log-out"></i></div>
                            <div class="text">Logout</div>
                        </a>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    {{-- Mobile --}}
    <div class="section-content-right"> 
        <div class="header-dashboard">
            <div class="wrap">
                <div class="header-left">
                    <a>
                        <img class="" id="logo_header_mobile" alt="" src="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}"
                            data-light="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}" data-dark="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}"
                            data-width="154px" data-height="52px" data-retina="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}">
                    </a>
                    <div class="button-show-hide">
                        <i class="icon-menu-left"></i>
                    </div>  
                </div>
                <div class="header-grid">
                    <div class="popup-wrap message type-header">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="header-item">
                                    @php
                                    use Carbon\Carbon;
                                    use App\Models\Order;

                                    // Get start and end of the current week
                                    $startOfWeek = Carbon::now()->startOfWeek();
                                    $endOfWeek = Carbon::now()->endOfWeek();

                                    // Count orders within this week
                                    $pendingOrdersCount = Order::where('status', 'ordered')
                                        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                                        ->count();

                                    $deliveredOrdersCount = Order::where('status', 'delivered')
                                        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                                        ->count();

                                    $notificationsCount = $deliveredOrdersCount + $pendingOrdersCount;
                                    @endphp
                                    @if($notificationsCount > 0) 
                                        <span class="text-tiny">{{ $notificationsCount > 9 ? '9+' : $notificationsCount }}</span> 
                                    @endif
                                    <i class="icon-bell"></i>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end has-content"
                                aria-labelledby="dropdownMenuButton2">
                                <li>
                                    <h6>Notifications</h6>
                                </li>  
                                <li>
                                    <div class="message-item item-3">
                                        <div class="image">
                                            <i class="icon-noti-3"></i>
                                        </div>
                                        <div>
                                            <div class="body-title-2">Product Status</div>
                                            <div class="text-tiny">There are {{$deliveredOrdersCount}} product that is running low</div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="message-item item-4">
                                        <div class="image">
                                            <i class="icon-noti-4"></i>
                                        </div>
                                        <div>
                                            <div class="body-title-2">Order pending: <span>{{$pendingOrdersCount}}</span>
                                            </div>
                                            <div class="text-tiny">There are {{$pendingOrdersCount}} pending orders</div>
                                        </div>
                                    </div>
                                </li>
                                <li><a href="{{route('admin.orders')}}" class="tf-button w-full">View all</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="popup-wrap user type-header">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="header-user wg-user">
                                    <span class="image">
                                        <img src="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}" alt="">
                                    </span>
                                    <span class="flex flex-column">
                                        <span class="body-title mb-2">{{Auth::user()->name}}</span>
                                        <span class="text-tiny">Admin</span>
                                    </span>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end has-content"
                                aria-labelledby="dropdownMenuButton3">
                                <li>
                                    <a href="{{route('admin.users')}}" class="user-item">
                                        <div class="icon">
                                            <i class="icon-user"></i>
                                        </div>
                                        <div class="body-title-2">Accounts</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.messages.index')}}" class="user-item">
                                        <div class="icon">
                                            <i class="icon-mail"></i>
                                        </div>
                                        <div class="body-title-2">Inbox</div>
                                        @if ($unreadCount > 0)
                                            <div class="number">
                                                <span class="cart-amount d-block position-absolute js-cart-items-count">
                                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                </li> 
                                <li>
                                    <form method="POST" action="{{route('logout')}}" id="logout-form" >
                                    @csrf
                                        <a href="{{route('logout')}}" class="user-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <div class="icon">
                                            <i class="icon-log-out"></i>
                                        </div>
                                        <div class="body-title-2">Log out</div>
                                        </a>
                                    </form> 
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    
        <div class="main-content">

            @yield('content')

            <div class="bottom-page">
                <div class="body-text">땅끝그린팜</div>
            </div>
        </div>  
    </div>
            </div>
        </div>
    </div>

    <script src="{{asset ('js/jquery.min.js') }}"></script>
    <script src="{{asset ('js/bootstrap.min.js') }}"></script>
    <script src="{{asset ('js/bootstrap-select.min.js') }}"></script>   
    <script src="{{asset ('js/sweetalert.min.js') }}"></script>    
    <script src="{{asset ('js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{asset ('js/main.js') }}"></script>
    <script>
    $(function(){
      $("#search-input").on("keyup",function(){
        var searchQuery = $(this).val();
        if(searchQuery.length > 1){
          $.ajax({
            type: "GET",
            url: "{{route('admin.search')}}",
            data: {query: searchQuery},
            dataType: 'json',
            success: function(data){
              $("#box-content-search").html('');
              $.each(data,function(index,item){
                var url = "{{route('admin.product.edit',['id'=>'product_id'])}}";
                var link = url.replace('product_id',item.id);

                $("#box-content-search").append(`
                <ul>
                    <a href="${link}">
                    <li class="product-item gap14 mb-10">
                        <div class="image no-bg">
                            <img src="/uploads/products/thumbnails/${item.image}" alt="${item.image}">
                        </div>
                        <div class="flex items-center justify-between gap20 flex-grow">
                            <div class="name">
                               <p class="body-text">${item.name}</p>
                            </div>
                        </div>
                    </li>
                    </a>
                    <li class="mb-10">
                        <div class="divider"></div>
                    </li>
                </ul>
                `);
              });
            }
          });
        }
      });
    });
  </script>
    
    @stack("scripts")

</body>

</html>
