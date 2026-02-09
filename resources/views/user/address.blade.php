@extends('user.account-nav')
@section('contents')
<div class="col-lg-9 mt-30">
    <div class="dashboard-content">
        <div class="flex-between mb-24">
            <h4 class="mb-0">Shipping Addresses</h4>
            <a href="{{ route('user.address.edit') }}" class="btn btn-main">Add/Edit Address</a>
        </div>
        @if ($addresses->count() > 0)
            <div class="row g-4">
                @foreach ($addresses as $address)
                    <div class="col-md-6 padding-20">
                        <div class="row">
                            {{-- Display existing address details --}}
                            <div class="col-sm-6"><h6 class="text-lg mb-24">Name: <p class="text-gray-500">{{$address->name}}</p></h6></div>
                            <div class="col-sm-6"><h6 class="text-lg mb-24">Zone: <p class="text-gray-500">{{$address->address}}</p></h6></div>
                            <div class="col-sm-6"><h6 class="text-lg mb-24">District: <p class="text-gray-500">{{$address->locality}}</p></h6></div>
                            <div class="col-sm-6"><h6 class="text-lg mb-24">City: <p class="text-gray-500">{{$address->city}}</p></h6></div>
                            <div class="col-sm-6"><h6 class="text-lg mb-24">Province: <p class="text-gray-500">{{$address->state}}</p></h6></div>
                            <div class="col-sm-6"><h6 class="text-lg mb-24">Landmark: <p class="text-gray-500">{{$address->landmark}}</p></h6></div>
                            <div class="col-sm-6"><h6 class="text-lg mb-24">Zip Code: <p class="text-gray-500">{{$address->zip}}</p></h6></div>
                            <div class="col-sm-6"><h6 class="text-lg mb-24">Contact: <p class="text-gray-500">{{$address->phone}}</p></h6></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">You have not saved any addresses yet.</p>
        @endif
    </div>
</div>
@endsection