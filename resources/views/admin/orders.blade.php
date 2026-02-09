@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Orders</h3>
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
                    <div class="text-tiny">Orders</div>
                </li>
            </ul>
        </div>  
        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                {{-- Main Filter and Search Wrapper --}}
                <div class="flex items-center gap10 flex-wrap">
                    {{-- Search Form Container --}}
                    <div class="wg-filter" style="position: relative;">
                        <form class="form-search wg-filter" action="{{ url()->current() }}" method="GET" id="search-order-form">
                            <fieldset class="name" style="min-width: 250px;">
                                <input type="text" placeholder="Search orders..." class="" name="search"
                                    id="order-search-input"
                                    tabindex="2" value="{{ request('search') }}">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                            
                            <div id="search-results-dropdown" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 100; background: white; border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                {{-- Live results injected here --}}
                            </div>
                        </form>
                    </div>

                    {{-- Filters --}}
                    <form id="filter-form" action="{{ url()->current() }}" method="GET" class="flex wg-filter items-center gap10">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        
                        {{-- Status Filter --}}
                        <select name="status" class="wg-filter" onchange="this.form.submit();" style="min-width: 150px;">
                            <option value="">Status</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>

                        {{-- Payment Mode Filter --}}
                        <select name="payment_mode" class="wg-filter" onchange="this.form.submit();" style="min-width: 150px;">
                            <option value="">Payment Mode</option>
                            <option value="card" {{ request('payment_mode') == 'card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="cod" {{ request('payment_mode') == 'cod' ? 'selected' : '' }}>Pick-up</option>
                            <option value="paypal" {{ request('payment_mode') == 'paypal' ? 'selected' : '' }}>Direct Bank</option>
                        </select>

                        {{-- Transaction Status Filter --}}
                        <select name="transaction_status" class="wg-filter" onchange="this.form.submit();" style="min-width: 150px;">
                            <option value="">Payment Status</option>
                            <option value="approved" {{ request('transaction_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('transaction_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="declined" {{ request('transaction_status') == 'declined' ? 'selected' : '' }}>Declined</option>
                            <option value="refunded" {{ request('transaction_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Orders Table --}}
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width:70px">OrderNo</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Tax</th>
                                <th class="text-center">Total</th>  
                                <th class="text-center">Status</th>
                                <th class="text-center">Order Date</th>
                                <th class="text-center">Total Items</th>
                                <th class="text-center">Payment Status</th>
                                <th class="text-center">Delivered On</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($orders->isEmpty())
                                <tr>
                                    <td colspan="12" class="text-center">No orders found matching the criteria.</td>
                                </tr>
                            @endif
                            @foreach ($orders as $order)
                            <tr>
                                <td class="text-center">{{$order->id}}</td>
                                <td class="text-center">{{$order->name}}</td>
                                <td class="text-center">{{$order->phone}}</td>
                                <td class="text-center">원{{ number_format($order->subtotal, 2) }}</td>
                                <td class="text-center">원{{ number_format($order->tax, 2) }}</td>
                                <td class="text-center">원{{ number_format($order->total, 2) }}</td>  
                                <td class="text-center">
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
                                <td class="text-center">{{$order->created_at->format('Y-m-d')}}</td>
                                <td class="text-center">{{$order->orderItems->count()}}</td>
                                <td class="text-center">
                                    @if($order->transaction)
                                        @if($order->transaction->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($order->transaction->status == 'declined')
                                            <span class="badge bg-danger">Declined</span>
                                        @elseif($order->transaction->status == 'refunded')
                                            <span class="badge bg-secondary">Refunded</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-dark">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">{{$order->delivered_date ? \Carbon\Carbon::parse($order->delivered_date)->format('Y-m-d') : 'N/A'}}</td>
                                <td>
                                    <div class="list-icon-function">
                                        <a href="{{route('admin.order.details',['order_id'=>$order->id])}}">
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>
                                        </a>
                                        @if($order->status == 'delivered')
                                        <a href="{{route('admin.print',['order_id'=>$order->id])}}"> 
                                            <div class="item eye">
                                                <i class="icon-download"></i>
                                            </div>  
                                        </a>
                                        @endif
                                        <form action="{{route('admin.order.delete', ['order_id'=>$order->id])}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="item text-danger delete">
                                                <i class="icon-trash-2"></i>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{$orders->appends(request()->query())->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){
        // SweetAlert Delete Confirmation
        $('.delete').on('click', function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title:"Are you sure?",
                text:"Once deleted, you will not be able to recover this data",
                icon:"warning",
                buttons: true,
                dangerMode: true,
            }).then(function(willDelete){
                if(willDelete){
                    form.submit();
                }
            });
        });
        @if(Session::has('status')) 
            swal( "Success", "{{ Session::get('status') }}", 'success', { 
                button: true, 
                timer: 5000,
                dangerMode: false,
            });
        @endif

        // Live Search
        const searchInput = $('#order-search-input');
        const resultsDropdown = $('#search-results-dropdown');
        const searchForm = $('#search-order-form');

        searchInput.on('keyup', function() {
            const query = $(this).val();
            if (query.length === 0 || isNaN(query)) {
                resultsDropdown.hide().empty();
                return;
            }
            $.ajax({
                url: '{{ route('admin.order.livesearch') }}', 
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    resultsDropdown.empty();
                    if (data.length > 0) {
                        let list = $('<ul style="list-style: none; padding: 0; margin: 0;"></ul>');
                        $.each(data, function(index, order) {
                            let display_text = 'Order #' + order.id + ' (' + (order.name || order.phone || 'N/A') + ')';
                            let listItem = $('<li class="live-search-item" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee; font-size: 16px;">' + display_text + '</li>');
                            listItem.on('click', function() {
                                searchInput.val(order.id);
                                resultsDropdown.hide().empty();
                                searchForm.submit();
                            });
                            listItem.hover(
                                function() { $(this).css('background-color', '#f0f0f0'); },
                                function() { $(this).css('background-color', 'white'); }
                            );
                            list.append(listItem);
                        });
                        resultsDropdown.append(list).show();
                    } else {
                        resultsDropdown.html('<div style="padding: 10px; color: #666;">No orders found matching ID "' + query + '".</div>').show();
                    }
                },
                error: function(error) {
                    console.log('Error during order live search:', error);
                }
            });
        });

        $(document).on('click', function(e) {
            if (!searchForm.is(e.target) && searchForm.has(e.target).length === 0) {
                resultsDropdown.hide().empty();
            }
        });
    });
</script>
@endpush
