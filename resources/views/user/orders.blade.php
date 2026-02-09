@extends('user.account-nav')

@section('contents') 
<div class="container py-4">
    <h2 class="mb-4">Order History</h2>

    {{-- Search & Filters --}}
    <div class="mb-4 d-flex flex-wrap align-items-center gap-2">
        <form id="search-filter-form" method="GET" class="d-flex flex-wrap gap-2 w-100">
            <input type="text" name="search" class="form-control" placeholder="Search by Order ID..." value="{{ request('search') }}" style="max-width: 200px;">
            
            {{-- Status Filter --}}
            <select name="status" class="form-select" onchange="this.form.submit()" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Pending</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            {{-- Payment Mode Filter --}}
            <select name="payment_mode" class="form-select" onchange="this.form.submit()" style="max-width: 150px;">
                <option value="">All Payment Modes</option>
                <option value="card" {{ request('payment_mode') == 'card' ? 'selected' : '' }}>Credit Card</option>
                <option value="cod" {{ request('payment_mode') == 'cod' ? 'selected' : '' }}>Pick-up</option>
                <option value="paypal" {{ request('payment_mode') == 'paypal' ? 'selected' : '' }}>Direct Bank</option>
            </select>

            <button type="submit" class="btn btn-main">Filter</button>
        </form>
    </div>

    <div class="card mb-4 shadow-sm border-0">
        @forelse ($orders as $order)
            <div class="card-body bg-light mb-10">
                <div class="row align-items-center mb-3">
                    <div class="col-md-6">
                        <h5 class="card-title mb-0">Order #{{ $order->id }}</h5>
                        <small class="text-muted">Placed on {{ $order->created_at->format('M d, Y') }}</small>
                    </div>
                    <div class="col-md-6 text-md-end">
                        @if($order->status == 'ordered') 
                            <span class="badge bg-warning text-dark fs-6">Pending</span>
                        @elseif($order->status == 'delivered') 
                            <span class="badge bg-success fs-6">Delivered</span>
                        @elseif($order->status == 'preparing') 
                            <span class="badge bg-info fs-6">Preparing</span>
                        @elseif($order->status == 'shipped') 
                            <span class="badge bg-primary fs-6">Shipped</span>
                        @else 
                            <span class="badge bg-danger fs-6">Cancelled</span>
                        @endif
                    </div>
                </div>

                <div class="row g-0 align-items-center"> 
                    <div class="col-md-7 col-8 ps-3">
                        <h6 class="mb-1">Products Purchased:</h6>
                        @if ($order->orderItems && $order->orderItems->count() > 0)
                            @foreach ($order->orderItems as $item)
                                <p class="text-muted mb-0 small">
                                    {{ $item->product->name ?? 'Product Name N/A' }} 
                                    (Qty: {{ $item->quantity ?? 1 }})
                                </p>
                            @endforeach
                        @else
                            <p class="text-muted mb-1 small">No items found for this order.</p>
                        @endif
                    </div>
                    
                    <div class="col-md-3 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('user.order.details', $order->id) }}" class="btn btn-sm text-main hover-bg-main-600 hover-text-main-50">View Details</a>
                        <a href="{{ route('shop.index') }}" class="btn btn-sm text-main hover-bg-main-600 hover-text-main-50">Reorder</a>
                        @if($order->status == 'delivered')
                        <a href="{{route('user.print',['order_id'=>$order->id])}}" class="btn btn-sm text-main hover-bg-main-600 hover-text-main-50">Download Invoice</a>
                        @endif
                    </div>
                </div>
                
                <hr>
                
                <div class="row small text-muted">
                    <div class="col-md-6">
                        <p class="mb-0">Total: <span class="fw-bold text-dark">원{{ number_format($order->total) }}</span></p>
                        <p class="mb-0">Delivery Fee: 
                            <span class="fw-bold text-dark">
                            @if(floatval($order->delivery_fee) > 0)
                                원{{ number_format(floatval($order->delivery_fee), 0) }}
                            @else
                                Free Delivery
                            @endif
                            </span>
                        </p>
                        {{-- Payment Mode & Status --}}
                        @if($order->transaction)
                        <p class="mb-0">Payment Mode: 
                            @if($order->transaction->mode == 'cod') Pick-up
                            @elseif($order->transaction->mode == 'paypal') Direct Bank
                            @else Credit Card
                            @endif
                        </p>
                        <p class="mb-0">Payment Status:
                            @if($order->transaction->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($order->transaction->status == 'declined')
                                <span class="badge bg-danger">Declined</span>
                            @elseif($order->transaction->status == 'refunded')
                                <span class="badge bg-secondary">Refunded</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                        @endif
                    </div>
                    @if($order->status == 'shipped')
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0">Tracking: <a href="https://www.cjlogistics.com/ko/tool/parcel/tracking">#{{$order->tracking_number ?? 'N/A'}}</a></p>
                    </div>
                    @endif
                </div>
                <hr> 
            </div>
        @empty
            <p class="text-center text-muted">No orders found.</p>
        @endforelse

        <div class="justify-content-center m-10">
            {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div> 
</div>
@endsection
