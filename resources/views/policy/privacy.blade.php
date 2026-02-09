@extends('layouts.apps')

@section('content')
<style>
    .bg-light-blue {
    background-color: #eef5ff;
}

.policy-section p,
.policy-section li {
    font-size: 15px;
    color: #333;
    line-height: 1.6;
}

.policy-section h5 {
    border-left: 3px solid #0d6efd;
    padding-left: 8px;
}

</style>
<div class="container container-lg my-5 mt-10">
    <div class="bg-white rounded-3 shadow-lg border border-light p-30 p-md-5" style="max-width: 900px; margin: 0 auto;">
        
        {{-- Title --}}
        <h3 class="fw-bold text-dark mb-3">Privacy Policy</h3>
        <p class="text-muted mb-4">
            <strong>Effective Date:</strong> October 13, 2025 | 
            <strong>Last Updated:</strong> October 13, 2025
        </p>

        {{-- Section 1 --}}
        <div class="mb-4 pb-3 border-bottom">
            <h5 class="fw-bold text-main mb-2">1. Information We Collect</h5>

            <div class="p-3 rounded-2" style="background-color: #eafdee;">
                <div class="row">
                    <div class="col-md-6">
                        <p class="fw-bold mb-1">Personal Information</p>
                        <p class="mb-0">
                            Name, email, phone, shipping/billing address, and securely processed payment details.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2 --}}
        <div class="mb-4 pb-3 border-bottom">
            <h5 class="fw-bold text-main mb-2">2. How We Use Your Data</h5>
            <ul class="mb-0" style="background-color: #eafdee;">
                <li><strong>Order Fulfillment:</strong> Processing transactions and arranging delivery.</li>
                <li><strong>Site Improvement:</strong> Analyzing usage to optimize the website.</li>
                <li><strong>Marketing:</strong> Sending promotional emails.</li>
                <li><strong>Security:</strong> Screening orders for fraud and risk.</li>
            </ul>
        </div>

        {{-- Section 3 --}}
        <div class="mb-4 pb-3 border-bottom">
            <h5 class="fw-bold text-main mb-2">3. Sharing Your Information</h5>
            <p class="mb-3">We share information only with entities essential to our operations:</p>
            <ul class="mb-0" style="background-color: #eafdee;">
                <li><strong>Service Providers:</strong> Payment Processors, Shipping Carriers, and Email Platforms.</li>
                <li><strong>Analytics:</strong> Google Analytics.</li>
                <li><strong>Legal:</strong> When required by law.</li>
            </ul>
        </div>

        {{-- Section 4 --}}
        <div class="mb-4 pb-3 border-bottom">
            <h5 class="fw-bold text-main mb-2">4. Your Rights and Choices</h5>
            <p>You retain control over your data:</p>
            <ul class="mb-0" style="background-color: #eafdee;">
                <li><strong>Access/Correction/Deletion:</strong> You can request to view, update, or delete your Personal Information.</li>
                <li><strong>Opt-Out:</strong> You can unsubscribe from marketing communications at any time.</li>
            </ul>
        </div>

        {{-- Contact --}}
        <div class="pt-2">
            <h6 class="fw-bold mb-2">Contact Us</h6>
            <p class="mb-1">
                For privacy concerns or to exercise your rights, please contact our Privacy Officer:
            </p>
            <p class="mb-0">
                <strong>Email:</strong> <a href="">{{ $contactInfo->email ?? '' }}</a>
            </p>
        </div>
    </div>
</div>
@endsection
