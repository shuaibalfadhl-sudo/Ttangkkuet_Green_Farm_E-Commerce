@extends('layouts.apps')

@section('content')
<div class="breadcrumb py-26 bg-color-one">
    <div class="container container-lg">
        <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
            <h6 class="mb-0">Profile Page</h6>
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
                <li class="text-sm text-neutral-600">
                    Profile
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    /* Sidebar visibility for different screen sizes */
    @media (min-width: 992px) {
        .mobile-toggler {
            display: none !important; 
        }
        .desktop-sidebar {
            display: block !important;
        }
    }
    @media (max-width: 991.98px) {
        .desktop-sidebar {
            display: none;
        }
    }

    /* Sidebar item styles */
    .list-group-item.active {
        z-index: 2;
        color: #fff;
        background-color: #299E60;
        border-color: #299E60;
    }
    .list-group-item {
        color: #898989;
        border-bottom: none;
        transition: all 0.2s ease-in-out;
    }
    .list-group-item:hover {
        color: #fff;
        background-color: #299E60;
        border-color: #299E60;
    }
    .list-group-item i {
        margin-right: 0.5rem;
    }

    /* Sticky sidebar */
    .sidebar-wrapper {
        position: sticky;
        top: 100px; /* Adjust if your navbar height differs */
        padding-bottom: 2rem;
    }

    /* Scrollable content area */
    .scrollable-content {
        max-height: calc(100vh - 120px); /* Adjust to your layout height */
        overflow-y: auto;
        padding-right: 1rem;
    }
</style>

<div class="container-fluid">
    <div class="row min-vh-auto">
        <!-- Sidebar -->
        <div class="col-lg-3 p-0 desktop-sidebar">
            <div class="sidebar-wrapper">
                <h4 class="px-3 pb-10 pt-10 text-main">My Profile</h4>
                <div class="list-group list-group-flush">
                    <a href="{{ route('user.index') }}" 
                       class="list-group-item list-group-item-action py-4 fs-5 mt-4 {{ request()->routeIs('user.index') ? 'active' : '' }}">
                        <i class="ph ph-user-circle"></i> Profile
                    </a>
                    <a href="{{ route('user.password') }}" 
                       class="list-group-item list-group-item-action py-4 fs-5 mt-4 {{ request()->routeIs('user.password') ? 'active' : '' }}">
                        <i class="ph ph-lock"></i> Password
                    </a>
                    <a href="{{ route('user.address') }}" 
                       class="list-group-item list-group-item-action py-4 fs-5 mt-4 {{ request()->routeIs('user.address') || request()->routeIs('user.address.edit') ? 'active' : '' }}">
                        <i class="ph ph-map-pin"></i> Address
                    </a>
                    <a href="{{ route('user.orders') }}" 
                       class="list-group-item list-group-item-action py-4 fs-5 mt-4 {{ request()->routeIs('user.orders') || request()->routeIs('user.order.details') ? 'active' : '' }}">
                        <i class="ph ph-shopping-bag"></i> Orders
                    </a>
                    <a href="{{ route('cart.index') }}" 
                       class="list-group-item list-group-item-action py-4 fs-5 mt-4 {{ request()->routeIs('cart.index') ? 'active' : '' }}">
                        <i class="ph ph-shopping-cart"></i> Cart
                    </a>
                    <a href="{{ route('user.messages.index') }}" 
                       class="list-group-item list-group-item-action py-4 fs-5 mt-4 {{ request()->routeIs('user.messages.index') || request()->routeIs('user.messages.index') ? 'active' : '' }}">
                        <i class="ph ph-chat-circle-dots"></i> Message
                    </a>
                    <a href="#" 
                       class="list-group-item list-group-item-action py-4 fs-5 mt-4 text-danger" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ph ph-sign-out"></i> Log Out
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </a>
                </div>
            </div>
        </div>

        <!-- Scrollable Content -->
        <div class="col-lg-9 scrollable-content">
            @yield('contents')
        </div>
    </div>
</div>
@endsection
