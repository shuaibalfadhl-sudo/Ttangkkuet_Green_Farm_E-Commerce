@extends('layouts.apps')
@section('content')
<div class="container py-5 bg-main-600 mt-10 border-0 rounded-10">
    <div class="bg-custom-green p-4 p-md-5 text-white rounded-3"> 
        {{-- Top Row: Title and Description --}}
        <div class="row align-items-center mb-4 mb-md-5">
            <div class="col-md-5 d-flex align-items-center mb-3 mb-md-0">
                <div class="icon-placeholder d-flex align-items-center justify-content-center">
                    {{-- Icon Placeholder: Using an SVG for a perfect match --}}

                </div>
                <h2 class="fw-bold d-inline-block display-5 lh-sm text-main-50">Free Delivery<br>Eligibility</h2>
            </div>
            
            <div class="col-md-7">
                <p class="lead mb-0 fs-5">
                    We are pleased to offer <b>Free Delivery</b> when you meet one of the following criteria!
                </p>
            </div>
        </div>

        {{-- Criteria Row: Two Cards Side-by-Side --}}
        <div class="row g-4 mb-4">
            
            {{-- Card 1: Weight Criteria --}}
            <div class="col-lg-6">
                <div class="card card-custom-bg h-100 border-0 p-3 p-md-4">
                    <div class="card-body text-dark">
                        <h3 class="card-title fw-bold text-success mb-3">10 kg or More</h3>
                        <p class="card-text fs-6">
                            Your total purchase contains products with a cumulative weight of 10 kg or more.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Card 2: Price Criteria --}}
            <div class="col-lg-6">
                <div class="card card-custom-bg h-100 border-0 p-3 p-md-4">
                    <div class="card-body text-dark">
                        <h3 class="card-title fw-bold text-success mb-3">
                            {{ number_format($delivery->minimum_order_amount) }} 원 or More
                        </h3>
                        <p class="card-text fs-6">
                            The total price of your order is {{ number_format($delivery->minimum_order_amount) }} 원 or more.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Note --}}
        <div class="row">
            <div class="col-12 text-center pt-3">
                <p class="mb-0 fs-6">
                    The free delivery service will be applied automatically at checkout once either condition is met.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection