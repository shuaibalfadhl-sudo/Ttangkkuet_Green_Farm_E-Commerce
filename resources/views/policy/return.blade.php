@extends('layouts.apps')

@section('content')
<div class="container container-lg my-5 mt-10">
    <div class="bg-white rounded-10 shadow-lg border border-light p-30 p-md-5" style="max-width: 900px; margin: 0 auto;">
        <h3 class="fw-bold text-dark mb-4" style="border-bottom: 2px solid #01a224; padding-bottom: 8px;">
            Return & Refund Policy
        </h3>

        {{-- Section 1 --}}
        <div class="mb-4 pb-3 border-bottom">
            <h5 class="fw-bold text-main mb-2">1. Issues from Shipping Damage or Delay</h5>
            <p class="mb-3">
                If your order is damaged or significantly delayed <strong>during transportation</strong>, 
                the responsibility lies with the shipping carrier, not our company.
            </p>
            <div class="p-3 rounded-2" style="background-color: #eafdee;">
                <p class="fw-bold mb-1">Action Required:</p>
                <p class="mb-0">
                    You must contact the <strong>shipping carrier directly</strong> to file a claim and request a refund 
                    based on their specific insurance policy. We will provide all necessary shipment information to assist your claim.
                </p>
            </div>
        </div>

        {{-- Section 2 --}}
        <div class="mb-4 pb-3 border-bottom">
            <h5 class="fw-bold text-main mb-2">2. Manufacturing or Product Defect</h5>
            <p class="mb-3">
                If the product arrives with a quality issue that existed <strong>before</strong> it was shipped from our warehouse, 
                we will handle the investigation.
            </p>
            <div class="p-3 rounded-2" style="background-color: #eafdee;">
                <p class="fw-bold mb-1">Action Required:</p>
                <p class="mb-0">
                    Contact us immediately via our support email, including your <strong>order number</strong>, 
                    a detailed description of the defect, and clear <strong>photographs or video</strong> evidence.
                </p>
            </div>
        </div>

        {{-- Section 3 --}}
        <div>
            <h5 class="fw-bold text-main mb-2">3. Our Guarantee & Resolution</h5>
            <p class="mb-3">
                For verified defects, we guarantee a fair resolution. We conduct a proper investigation to verify all requests.
            </p>

            <div class="ms-3">
                <p class="fw-bold mb-2">Confirmed Defects receive:</p>
                <ul class="mb-0"> 
                    <li>A <strong>Replacement Item</strong>, if preferred and available.</li> 
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
