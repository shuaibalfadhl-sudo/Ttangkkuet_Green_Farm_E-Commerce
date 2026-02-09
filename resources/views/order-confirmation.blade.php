@extends('layouts.apps')
@section('content')
<style>
  
.checkout-form {
  display: flex;
  gap: 3.625rem; }
  @media (max-width: 1199.98px) {
    .checkout-form {
      flex-direction: column; } }
  .checkout-form .billing-info__wrapper {
    padding-top: 3.125rem;
    flex-grow: 1; }
    .checkout-form .billing-info__wrapper .form-floating > label, .checkout-form .billing-info__wrapper .form-label-fixed > .form-label {
      color: #767676; }
  .checkout-form .checkout__totals-wrapper .sticky-content {
    padding-top: 3.125rem; }
  .checkout-form .checkout__totals-wrapper .btn-checkout {
    width: 100%;
    height: 3.75rem;
    font-size: 0.875rem; }
  .checkout-form .checkout__payment-methods {
    border: 1px solid #e4e4e4;
    margin-bottom: 1.25rem;
    padding: 2.5rem 2.5rem 1.5rem;
    width: 26.25rem; }
    @media (max-width: 1199.98px) {
      .checkout-form .checkout__payment-methods {
        width: 100%; } }
    .checkout-form .checkout__payment-methods label {
      font-size: 1rem;
      line-height: 1.5rem; }
      .checkout-form .checkout__payment-methods label .option-detail {
        font-size: 0.875rem;
        margin: 0.625rem 0 0;
        display: none; }
    .checkout-form .checkout__payment-methods .form-check-input:checked ~ label .option-detail {
      display: block; }
    .checkout-form .checkout__payment-methods .policy-text {
      font-size: 0.75rem;
      line-height: 1.5rem; }
      .checkout-form .checkout__payment-methods .policy-text > a {
        color: #c32929; }

.checkout__totals {
  border: 1px solid #222;
  margin-bottom: 1.25rem;
  padding: 2.5rem 2.5rem 0.5rem;
  width: 26.25rem; }
  @media (max-width: 1199.98px) {
    .checkout__totals {
      width: 100%; } }
  .checkout__totals > h3, .checkout__totals > .h3 {
    font-size: 1rem;
    text-transform: uppercase;
    margin-bottom: 1.25rem; }
  .checkout__totals table {
    width: 100%; }
  .checkout__totals .checkout-cart-items thead th {
    border-bottom: 1px solid #e4e4e4;
    padding: 0.875rem 0;
    font-size: 0.875rem;
    font-weight: 500; }
  .checkout__totals .checkout-cart-items tbody td {
    padding: 0.40625rem 0;
    color: #767676; }
  .checkout__totals .checkout-cart-items tbody tr:first-child td {
    padding-top: 0.8125rem; }
  .checkout__totals .checkout-cart-items tbody tr:last-child td {
    padding-bottom: 0.8125rem;
    border-bottom: 1px solid #e4e4e4; }
  .checkout__totals .checkout-totals th, .checkout__totals .checkout-totals td {
    border-bottom: 1px solid #e4e4e4;
    padding: 0.875rem 0;
    font-size: 0.875rem;
    font-weight: 500; }
  .checkout__totals .checkout-totals tr:last-child th, .checkout__totals .checkout-totals tr:last-child td {
    border-bottom: 0; }

.order-complete {
  width: 56.25rem;
  max-width: 100%;
  margin: 3.125rem auto;
  display: flex;
  flex-direction: column;
  gap: 2.25rem; }
  .order-complete__message {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center; }
    .order-complete__message svg {
      margin-bottom: 1.25rem; }
    .order-complete__message h3, .order-complete__message .h3 {
      font-size: 2.1875rem;
      text-align: center; }
    .order-complete__message p {
      color: #767676;
      margin-bottom: 0;
      text-align: center; }
  .order-complete .order-info {
    width: 100%;
    border: 2px dashed #767676;
    padding: 2.5rem;
    display: flex;
    gap: 1rem; }
    @media (max-width: 767.98px) {
      .order-complete .order-info {
        flex-direction: column; } }
    .order-complete .order-info__item {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
      flex-grow: 1; }
      .order-complete .order-info__item label {
        font-size: 0.875rem;
        font-weight: 400;
        color: #767676; }
      .order-complete .order-info__item span {
        font-size: 1rem;
        font-weight: 500; }
  .order-complete .checkout__totals {
    width: 100%; }
    .order-complete .checkout__totals .checkout-cart-items thead th:last-child {
      text-align: right; }
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
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Order Sent</h2> 
        <div class="p-10 mt-4">
          @if($order->transaction->mode == 'paypal' || $order->transaction->mode == 'cod')
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
          @endif
            <div class="order-complete">
        <div class="order-complete__message">
          <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="40" cy="40" r="40" fill="#299E60" />
            <path
              d="M52.9743 35.7612C52.9743 35.3426 52.8069 34.9241 52.5056 34.6228L50.2288 32.346C49.9275 32.0446 49.5089 31.8772 49.0904 31.8772C48.6719 31.8772 48.2533 32.0446 47.952 32.346L36.9699 43.3449L32.048 38.4062C31.7467 38.1049 31.3281 37.9375 30.9096 37.9375C30.4911 37.9375 30.0725 38.1049 29.7712 38.4062L27.4944 40.683C27.1931 40.9844 27.0257 41.4029 27.0257 41.8214C27.0257 42.24 27.1931 42.6585 27.4944 42.9598L33.5547 49.0201L35.8315 51.2969C36.1328 51.5982 36.5513 51.7656 36.9699 51.7656C37.3884 51.7656 37.8069 51.5982 38.1083 51.2969L40.385 49.0201L52.5056 36.8996C52.8069 36.5982 52.9743 36.1797 52.9743 35.7612Z"
              fill="white" />
          </svg>
          <h3>Your order is completed!</h3>
          <p>Thank you. Your order has been received.</p>
        </div>
        <div class="order-info">
          <div class="order-info__item">
            <label>Order Number</label>
            <span>{{$order->id}}</span>
          </div>
          <div class="order-info__item">
            <label>Date</label>
            <span>{{$order->created_at}}</span>
          </div>
          <div class="order-info__item">
            <label>Total</label>
            <span>원{{ number_format(floatval($order->total), 0)}}</span>
          </div>
          <div class="order-info__item">
            <label>Payment Method</label>
            @if($order->transaction->mode == 'cod')
                <span>Cash on Delivery</span>
            @elseif($order->transaction->mode == 'paypal')
                <span>Direct Bank</span>
            @else
                <span>Pick-up</span>
            @endif
          </div>
        </div>
        <div class="checkout__totals-wrapper">
          <div class="checkout__totals">
            <h3>Order Details</h3>
            <table class="checkout-cart-items">
              <thead>
                <tr>
                  <th>PRODUCT</th>
                  <th>SUBTOTAL</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                  <td>
                    {{$item->product->name}} x {{$item->quantity}}
                  </td>
                  <td>
                    원{{ number_format(floatval($item->price), 0)}}
                  </td>
                </tr>
                
              </tbody>
            </table>
            <table class="checkout-totals">
              <tbody>
                <tr>
                  <th>SUBTOTAL</th>
                  <td>원{{ number_format(floatval($order->subtotal), 0)}}</td>
                </tr>
                <tr>
                  <th>DISCOUNT</th>
                  <td>원{{$order->discount}}</td>
                </tr>
                <tr>
                  <th>SHIPPING</th>
                  <td>
                    @if(floatval($order->delivery_fee) > 0)
                        원{{ number_format(floatval($order->delivery_fee), 0) }}
                    @else
                        Free Delivery
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>TOTAL</th>
                  <td>원{{ number_format(floatval($order->total), 0)}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @if($order->transaction->mode == 'paypal' || $order->transaction->mode == 'cod')
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
          {{-- End Bank Transfer Details --}}
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
          .upload-box:hover {
            background-color: #f8f9fa;
          }
        </style>
      @endif
      <hr class="mb-4">
      
    </section>
  </main>
@endsection