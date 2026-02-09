@extends('layouts.admin')
@section('content')
<style>
    .table-transaction>tbody>tr:nth-of-type(odd) {
        --bs-table-accent-bg: #fff !important;
    }
</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Order Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Order Details</div>
                </li>
            </ul>
        </div>
        <div class="wg-box mt-5">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Details</h5>
                </div>
                <a class="tf-button style-1 w208" href="{{route('admin.orders')}}">Back</a>
                @if (
                    $order->status === 'canceled' && 
                    $order->transaction && 
                    $order->transaction->status === 'approved' && 
                    $order->transaction->mode === 'card'
                )
                    {{-- Refund for card payments --}}
                    <form action="{{ route('orders.refund', $order->id) }}" method="POST" onsubmit="return confirm('Refund this payment?');">
                        @csrf
                        <button type="submit" class="button style-1 w208 bg-danger text-white">
                            Refund Payment
                        </button>
                    </form>

                @elseif (
                    $order->status === 'canceled' && 
                    $order->transaction->status === 'approved' && 
                    in_array($order->transaction->mode, ['paypal', 'cod'])
                )
                    {{-- Mark as refunded for PayPal or COD --}}
                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" onsubmit="return confirm('Mark this order as refunded?');">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="refunded">
                        <button type="submit" class="button style-1 w208 bg-warning text-white">
                            Mark as Refunded
                        </button>
                    </form>
                @endif
            </div>
            <div class="table-responsive"> 
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Order No.</th>
                            <td>{{$order->id}}</td>
                            <th>Mobile No.</th>
                            <td>{{$order->phone}}</td>
                            <th>Zip</th>
                            <td>{{$order->zip}}</td>
                        </tr>
                        <tr>
                            <th>Order Date</th>
                            <td>{{$order->created_at}}</td>
                            <th>Delivered Date</th>
                            <td>{{$order->delivered_date}}</td>
                            <th>Canceled Date</th>
                            <td>{{$order->canceled_date}}</td>
                        </tr>
                        <tr>
                            <th>Order Status</th>
                            <td colspan="5">
                                @if($order->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                @elseif($order->status == 'preparing')
                                    <span class="badge bg-info">Preparing</span>
                                @elseif($order->status == 'shipped')
                                    <span class="badge bg-primary">Shipped</span>
                                @elseif($order->status == 'canceled')
                                    <span class="badge bg-danger">Canceled</span>
                                @else
                                    <span class="badge bg-warning">Ordered</span>
                                @endif
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="divider"></div>
        </div>
        
        <div class="wg-box mt-5">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Items</h5>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Return Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderItems as $item)
                        <tr>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{asset('uploads/products/thumbnails')}}/{{$item->product->image}}" alt="{{$item->product->name}}" class="image">
                                </div>
                                <div class="name">
                                    <a href="{{route('shop.product.details',['product_slug'=>$item->product->slug])}}" target="_blank" class="body-title-2">{{$item->product->name}}</a>
                                </div>
                            </td>
                            <td class="text-center">원{{$item->price}}</td>
                            <td class="text-center">{{$item->quantity}}</td>
                            <td class="text-center">{{$item->product->SKU}}</td>
                            <td class="text-center">{{$item->product->category->name}}</td>
                            <td class="text-center">{{$item->rstatus == 0 ? "No":"Yes"}}</td>
                            <td class="text-center">
                                <div class="list-icon-function view-icon">
                                    <div class="item eye">
                                        <i class="icon-eye"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{$orderItems->links('pagination::bootstrap-5')}}
            </div>
        </div>
        <div class="wg-box mt-5">
            <h5>Shipping Address</h5>
            <div class="my-account__address-item col-md-6">
                <div class="my-account__address-item__detail">
                    <p>{{$order->name}}</p>
                    <p>{{$order->address}}</p>
                    <p>{{$order->locality}}</p>
                    <p>{{$order->city}}, {{$order->state}} </p>
                    <p>{{$order->landmark}}</p>
                    <p>{{$order->zip}}</p>
                    <br>
                    <p>Mobile : {{$order->phone}}</p>
                </div>
            </div>
        </div>
        <div class="wg-box mt-5">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Transactions</h5>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-transaction">
                    <tbody>
                        <tr>
                            <th>Subtotal</th>
                            <td>원{{$order->subtotal}}</td>
                            <th>Tax</th>
                            <td>원{{$order->tax}}</td>
                            <th>Discount</th>
                            <td>원{{$order->discount}}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>원{{$order->total}}</td>
                            <th>Payment Mode</th>
                            <td>
                                @if($order->transaction)
                                    @switch($order->transaction->mode)
                                        @case('cod')
                                            Pick-up
                                            @break
                                        @case('paypal')
                                            Direct Bank
                                            @break
                                        @case('card')
                                            Credit Card
                                            @break
                                        @default
                                            {{ ucfirst($order->transaction->mode) }}
                                    @endswitch
                                @else
                                    N/A
                                @endif
                            </td>
                            <th>Status</th>
                            <td>
                                @if($transaction->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($transaction->status == 'declined')
                                    <span class="badge bg-danger">Declined</span>
                                @elseif($transaction->status == 'refunded')
                                    <span class="badge bg-secondary">Refunded</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($order->transaction->mode == 'check' || $order->transaction->mode == 'cod')
        <div class="wg-box mt-5">
            <h5>Receipt</h5>
            <div class="my-account__address-item col-md-6">
                <div class="my-account__address-item__detail">
                    <img src="{{ $order->receipt_image ? asset('uploads/receipts/' . $order->receipt_image) : '' }}" alt="">
                </div>
            </div>
        </div>
        @endif

        <div class="wg-box mt-5">
            <h5>Update Order Status</h5>
            <form action="{{route('admin.order.status.update')}}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="order_id" value="{{$order->id}}" />
                <div class="row">
                    <div class="col-md-3">
                        <div class="select">
                            <select name="order_status" name="order_status">
                            <option value="ordered" {{$order->status == 'ordered' ? "selected":""}}>Ordered</option>
                            <option value="preparing" {{$order->status == 'preparing' ? "selected":""}}>Preparing</option>
                            <option value="shipped" {{$order->status == 'shipped' ? "selected":""}}>Shipped</option>
                            <option value="delivered" {{$order->status == 'delivered' ? "selected":""}}>Delivered</option>
                            <option value="canceled" {{$order->status == 'canceled' ? "selected":""}}>Canceled</option>
                        </select>
                        </div>
                    </div>
                    @if($order->status !== 'shipped' && $order->status !== 'preparing')
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-primary tf-button w208">Update Status</button>
                        </div>
                    @endif
                </div>
            </form>
            
            {{-- 1. Show this form when the order is ready to be shipped --}}
            @if($order->status == 'preparing') 
            <div class="row"><h5>Tracking Number</h5></div>
                <div class="row"> 
                    <div class="col-md-3">
                    {{-- 2. Target the 'admin.order.shipped' route --}}
                    <form action="{{ route('admin.order.shipped') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        {{-- Hidden input to pass the Order ID --}}
                        <input type="hidden" name="order_id" value="{{ $order->id }}" />
                        
                        {{-- Input for the Tracking Number --}}
                        <div class="mb-3">
                            {{-- 3. Correct the input name and type. The value should be empty. --}}
                            <input type="text" 
                                name="tracking_number" 
                                id="tracking_number" 
                                class="form-control" 
                                placeholder="Enter tracking number"
                                required />
                        </div>
                        </div>
                        {{-- 4. Correct the button text --}}
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-primary tf-button w208">
                                Mark as Shipped
                            </button>
                        </div>
                    </form>
                </div>
            @endif
            
            {{-- Optional: Show the Mark as Delivered button only after shipping --}}
            @if($order->status == 'shipped')
                <div class="row">
                    <div class="col-md-3">
                    <div>
                            <select name="order_status" name="order_status" disabled>
                            <option value="shipped" selected>{{ $order->tracking_number ?? 'N/A' }}</option>
                        </select>
                    </div>
                    </div>
                    <div class="col-md-5">
                    <form action="{{ route('admin.order.delivered') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        {{-- Hidden input to pass the Order ID --}}
                        <input type="hidden" name="order_id" value="{{ $order->id }}" />
                        <button type="submit" class="btn btn-success tf-button w208">
                            Mark as Delivered
                        </button>
                        
                    </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $(function(){
            @if(Session::has('status')) 
                swal( "Success", "{{ Session::get('status') }}", 'success', { 
                    button: true, 
                    timer: 5000,
                    dangerMode: false,
                });
            @endif
        });
    </script>
@endpush
