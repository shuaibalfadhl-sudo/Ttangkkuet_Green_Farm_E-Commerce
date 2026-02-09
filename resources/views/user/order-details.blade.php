@extends('user.account-nav')
@section('contents')
    <style>
        /* Custom styling for the ordered details header card */
            .order-details-card .card-header {
                background-color: #f8f9fa; /* Light background for header */
                border-bottom: 1px solid #dee2e6;
            }

            /* Style for the custom data boxes (using Bootstrap Grid) */
            .detail-box {
                padding: 10px;
                background-color: #e9ecef; /* A darker gray for the background */
                border-radius: 0.25rem;
                margin-bottom: 1rem;
            }
            .detail-box small {
                font-size: 0.75rem;
                color: #6c757d;
                display: block;
            }
            .bank-details {
            background-color: #f7f7f7;
            border: 1px solid #e4e4e4;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 0.25rem;
            }
            .bank-details h5 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #222;
            }
            .bank-details p {
            margin-bottom: 0.5rem;
            font-size: 0.9375rem;
            }
            .bank-details .account-row {
            display: flex;
            justify-content: space-between;
            padding: 0.3rem 0;
            border-bottom: 1px dotted #ccc;
            }
            .bank-details .account-row:last-child {
            border-bottom: none;
            }
            .bank-details .account-label {
            font-weight: 500;
            color: #000000;
            }
            .bank-details .account-number {
            font-weight: 700;
            color: #4f4b4b; /* Using the color from your provided CSS */
            }
    </style> 
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Order Details: #{{ $order->id }}</h1>
        <a href="{{ route('user.orders') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
        @if(Session::has('status'))
            <p class = "alert alert-success">{{Session::get('status')}}</p>
        @endif
    </div>
    <div class="card shadow-sm mb-5 order-details-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Order Summary</h5>
            @if($order->status == 'ordered') <span class="badge bg-warning text-dark fs-6">Pending</span>
            @elseif($order->status == 'delivered') <span class="badge bg-success fs-6">Delivered</span>
            @else <span class="badge bg-danger fs-6">Cancelled</span>
            @endif
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4 col-sm-6">
                    <div class="detail-box">
                        <small>Order No.</small>
                        <p class="fw-bold mb-0">#{{ $order->id }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="detail-box">
                        <small>Mobile No.</small>
                        <p class="fw-bold mb-0">{{$order->phone}}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="detail-box">
                        <small>Zip</small>
                        <p class="fw-bold mb-0">{{$order->zip}}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="detail-box">
                        <small>Order Date</small>
                        <p class="fw-bold mb-0">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="detail-box">
                        <small>Delivered Date</small>
                        <p class="mb-0">{{$order->delivered_date}}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="detail-box">
                        <small>Canceled Date</small>
                        <p class="fw-bold text-danger mb-0">{{$order->canceled_date}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h3 class="h4 mb-10 mt-10">Ordered Items</h3>
    <div class="table-responsive mb-5">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th class="p-10">Name</th>
                    <th class="p-10">Price</th>
                    <th class="p-10">Qty</th>
                    <th class="p-10">SKU</th>
                    <th class="p-10">Category</th>
                    <th class="p-10">Return Status</th>
                    <th class="p-10">Action</th>
                </tr>
            </thead>
            <tbody> 
                @foreach ($orderItems as $item)
                <tr>
                    <td class="p-10">
                        <img src="{{asset('uploads/products/thumbnails')}}/{{$item->product->image}}" alt="{{$item->product->name}}" style="width: 40px; height: 40px; object-fit: cover; margin-right: 8px;" class="rounded">
                        {{$item->product->name}}
                    </td>
                    <td class="p-10">원{{ number_format(floatval($item->price), 0)}}</td>
                    <td class="p-10">{{$item->quantity}}</td>
                    <td class="p-10">{{$item->product->SKU}}</td>
                    <td class="p-10">{{$item->product->category->name}}</td>
                    <td class="p-10">{{$item->rstatus == 0 ? "No":"Yes"}}</td>
                    <td class="p-10">
                        <a href="{{route('shop.product.details',['product_slug'=>$item->product->slug])}}" class="btn btn-sm btn-outline-info" title="View Product">
                            <i class="ph ph-eye text-main"></i>
                        </a>
                        @if($order->status == 'delivered')
                        <a href="{{route('user.print',['order_id'=>$order->id])}}" class="btn btn-sm btn-outline-info" title="View Product">
                            <i class="ph ph-download text-main"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row g-4 mt-10">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Shipping Address</h5>
                </div> 
                <div class="card-body">
                    <p class="fw-bold mb-1">{{$order->name}}</p>
                    <p class="mb-1">{{$order->address}}</p>
                    <p class="mb-1">{{$order->locality}}</p>
                    <p class="mb-1">{{$order->city}}</p>
                    <p class="mb-3">{{$order->landmark}}</p>
                    <p class="mb-0 text-muted">Mobile: {{$order->phone}}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Transaction Summary</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span class="fw-bold">원{{ number_format(floatval($order->subtotal), 0)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Shipping:</span>
                            <span>
                                @if(floatval($order->delivery_fee) > 0)
                                    원{{ number_format(floatval($order->delivery_fee), 0) }}
                                @else
                                    Free Delivery
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Discount:</span>
                            <span>원{{ number_format(floatval($order->discount), 0)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span>원{{ number_format(floatval($order->total), 0)}}</span>
                        </li>
                    </ul>
                    <div class="mt-3">
                        <p class="mb-1 small">
                            Payment Mode: 
                            <span class="fw-bold">
                                @if($transaction->mode === 'cod')
                                    Pick-up
                                @elseif($transaction->mode === 'paypal')
                                    Direct Bank
                                @else
                                    {{ ucfirst($transaction->mode) }}
                                @endif
                            </span>
                        </p>
                        <p class="mb-0 small">Payment Status:
                        @if($transaction->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($transaction->status == 'declined')
                            <span class="badge bg-danger">Declined</span>
                        @elseif($transaction->status == 'refunded')
                            <span class="badge bg-secondary">Refunded</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                        </p>
                    </div>
                    <div class="mt-3">
                        @if($order->status == 'shipped')
                            <p class="mb-1 small">Tracking: <a href="https://www.cjlogistics.com/ko/tool/parcel/tracking" class="fw-bold">#{{$order->tracking_number ?? 'N/A'}}</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if($order->transaction->mode == 'check' || $order->transaction->mode == 'cod')
        <div class="card shadow-sm mb-5 order-details-card p-6">
          <h4 class="alert-heading">Check Payment Upload</h4>
          <p>Please upload a clear image or screenshot of your check payment below.</p>
          <hr>
        {{-- Bank Transfer Details from Business Card --}}
          <div class="bank-details">
            <h5>Bank Transfer Details</h5>
            <p class="mb-3">
              Please transfer the total amount (원{{ number_format(floatval($order->total), 0)}}) to one of the following accounts:
            </p>
            <p class="text-danger">Note: The payed costumer will be the priority and your order will not proccess until the payment is receiced</p>
            <div class="d-flex justify-content-between row g-4 mt-10">
                {{-- Bank Account 1 --}}
                <div class="card col-lg-6 p-4">
                    <div class="account-row">
                        <span class="account-label fw-bold">Bank Name 1:</span>
                        <span class="account-number">
                            {{ $bankInfo->bank_name_one ?? '—' }}
                        </span>
                    </div>
                    <div class="account-row">
                        <span class="account-label fw-bold">Account Number:</span>
                        <span class="account-number">
                            {{ $bankInfo->account_number_one ?? '—' }}
                        </span>
                    </div>
                    <div class="account-row">
                        <span class="account-label fw-bold">Account Holder:</span>
                        <span class="account-number">
                            {{ $bankInfo->account_holder_one ?? '—' }}
                        </span>
                    </div>
                </div>

                {{-- Bank Account 2 --}}
                <div class="card col-lg-6 p-4">
                    <div class="account-row">
                        <span class="account-label fw-bold">Bank Name 2:</span>
                        <span class="account-number">
                            {{ $bankInfo->bank_name_two ?? '—' }}
                        </span>
                    </div>
                    <div class="account-row">
                        <span class="account-label fw-bold">Account Number:</span>
                        <span class="account-number">
                            {{ $bankInfo->account_number_two ?? '—' }}
                        </span>
                    </div>
                    <div class="account-row">
                        <span class="account-label fw-bold">Account Holder:</span>
                        <span class="account-number">
                            {{ $bankInfo->account_holder_two ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>

          </div>
          {{-- End Bank Transfer Details --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
          <form action="{{ route('orders.uploadReceipt', $order->id) }}" method="POST" enctype="multipart/form-data">
              @csrf

              <label class="fw-bold">Upload Receipt Image <span class="text-danger">*</span></label>

              <!-- 🟦 Drag & Drop Upload Box -->
              <div id="drop-area" class="upload-box border border-primary border-dashed rounded text-center p-5" 
                  style="cursor:pointer; transition:0.2s; position:relative; overflow:hidden;">
                  
                  <!-- Placeholder -->
                  <div id="upload-placeholder" style="{{ $order->receipt_image ? 'display:none;' : '' }}">
                      <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                      <p class="mt-2 mb-0 text-muted">
                          Drop your image here or <span class="text-primary text-decoration-underline">click to browse</span>
                      </p>
                  </div>

                  <!-- Preview Image (shows if exists) -->
                  <img 
                      id="receipt-preview" 
                      src="{{ $order->receipt_image ? asset('uploads/receipts/' . $order->receipt_image) : '' }}" 
                      alt="Receipt Preview"
                      class="img-fluid rounded shadow-sm border"
                      style="{{ $order->receipt_image ? 'display:block;' : 'display:none;' }} max-height:250px; object-fit:contain; width:100%;"
                  >

                  <!-- Hidden Input -->
                  <input type="file" name="receipt_image" id="receipt_image" accept="image/*" 
                        class="d-none" onchange="previewReceipt(event)" required>
              </div>
                @error('receipt_image')
                    <span class="text-danger mt-2">{{ $message }}</span>
                @enderror
              <button type="submit" class="btn btn-primary mt-4">Upload Receipt</button>
          </form>
        </div>

        <!-- ✅ Script -->
        <script>
          const dropArea = document.getElementById('drop-area');
          const fileInput = document.getElementById('receipt_image');
          const previewImage = document.getElementById('receipt-preview');
          const placeholder = document.getElementById('upload-placeholder');

          // Click to browse
          dropArea.addEventListener('click', () => fileInput.click());

          // Highlight on drag
          ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, e => {
              e.preventDefault();
              e.stopPropagation();
              dropArea.classList.add('bg-light');
            });
          });

          // Remove highlight
          ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, e => {
              e.preventDefault();
              e.stopPropagation();
              dropArea.classList.remove('bg-light');
            });
          });

          // Handle drop
          dropArea.addEventListener('drop', e => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
              fileInput.files = files;
              previewReceipt({ target: fileInput });
            }
          });

          // Preview inside upload box
          function previewReceipt(event) {
            const file = event.target.files[0];
            if (file) {
              const reader = new FileReader();
              reader.onload = e => {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                placeholder.style.display = 'none';
              };
              reader.readAsDataURL(file);
            } else {
              previewImage.style.display = 'none';
              placeholder.style.display = 'block';
            }
          }
        </script>

        <!-- 🧩 Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <style>
          .border-dashed {
            border-style: dashed !important;
          }
        </style>
      @endif
        @if($order->status == 'ordered')
        <div class="wg-box mt-5 text-right">
            <form action="{{route('user.order.cancel')}}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="order_id" value="{{$order->id}}"/>
                <button type="button" class="btn btn-danger cancel-order">Cancel Order</button>
            </form>
        </div>
        @endif
    </div> 
{{-- <style>
    .pt-90 {
      padding-top: 90px !important;
    }

    .my-account .page-title {
      font-size: 1.5rem;
      font-weight: 700;
      text-transform: uppercase;
      margin-bottom: 40px;
      border-bottom: 1px solid;
      padding-bottom: 13px;
    }

    .my-account .wg-box {
      display: -webkit-box;
      display: -moz-box;
      display: -ms-flexbox;
      display: -webkit-flex;
      display: flex;
      padding: 24px;
      flex-direction: column;
      gap: 24px;
      border-radius: 12px;
      background: var(--White);
      box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
    }

    .bg-success {
      background-color: #40c710 !important;
    }

    .bg-danger {
      background-color: #f44032 !important;
    }

    .bg-warning {
      background-color: #f5d700 !important;
      color: #000;
    }

    .table-transaction>tbody>tr:nth-of-type(odd) {
      --bs-table-accent-bg: #fff !important;

    }

    .table-transaction th,
    .table-transaction td {
      padding: 0.625rem 1.5rem .25rem !important;
      color: #000 !important;
    }

    .table> :not(caption)>tr>th {
      padding: 0.625rem 1.5rem .25rem !important;
      background-color: #6a6e51 !important;
    }

    .table-bordered>:not(caption)>*>* {
      border-width: inherit;
      line-height: 32px;
      font-size: 14px;
      border: 1px solid #e1e1e1;
      vertical-align: middle;
    }

    .table-striped .image {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 50px;
      height: 50px;
      flex-shrink: 0;
      border-radius: 10px;
      overflow: hidden;
    }

    .table-striped td:nth-child(1) {
      min-width: 250px;
      padding-bottom: 7px;
    }

    .pname {
      display: flex;
      gap: 13px;
    }

    .table-bordered> :not(caption)>tr>th,
    .table-bordered> :not(caption)>tr>td {
      border-width: 1px 1px;
      border-color: #6a6e51;
    }
</style>
    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Order Details</h2>
            <div class="row">
                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>

                <div class="col-lg-10">
                        <div class="wg-box">
                            <div class="flex items-center justify-between gap10 flex-wrap">
                                <div class="row">
                                    <div class="col-6">
                                        <h5>Ordered Details</h5>
                                    </div>
                                    <div class="col-6 text-right">
                                        <a class="btn btn-sm btn-danger" href="{{route('user.orders')}}">Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                @if(Session::has('status'))
                                    <p class="alert alert-success">{{Session::get('status')}}</p>
                                @endif
                                <table class="table table-bordered table-striped ">
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
                        </div>
                        <div class="wg-box">
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
                                            <th class="text-center">Brand</th>
                                            <th class="text-center">Options</th>
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
                                            <td class="text-center">${{$item->price}}</td>
                                            <td class="text-center">{{$item->quantity}}</td>
                                            <td class="text-center">{{$item->product->SKU}}</td>
                                            <td class="text-center">{{$item->product->category->name}}</td>
                                            <td class="text-center">{{$item->product->brand->name}}</td>
                                            <td class="text-center">{{$item->option}}</td>
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
                                    <p>{{$order->city}}, {{$order->country}} </p>
                                    <p>{{$order->landmark}}</p>
                                    <p>{{$order->zip}}</p>
                                    <br>
                                    <p>Mobile : {{$order->phone}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="wg-box mt-5">
                            <h5>Transactions</h5>
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
                                        <td>{{$transaction->mode}}</td>
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
                        @if($order->status == 'ordered')
                        <div class="wg-box mt-5 text-right">
                            <form action="{{route('user.order.cancel')}}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="order_id" value="{{$order->id}}"/>
                                <button type="button" class="btn btn-danger cancel-order">Cancel Order</button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
        </section>
    </main> --}}
@endsection
@push('scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(function(){
            $('.cancel-order').on('click', function(e){
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title:"Are you sure?",
                    text:"Do you want to cancel this order?",
                    type:"warning",
                    buttons:["No","Yes"],
                    confirmButtonColor:'#FF0000'
                }).then(function(result){
                    if(result){
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush