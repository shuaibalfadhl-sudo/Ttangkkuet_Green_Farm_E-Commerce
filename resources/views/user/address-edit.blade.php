@extends('user.account-nav')
@section('contents')                               
            <div class="col-lg-9">
                <div class="dashboard-content">
                    <h4 class="mb-24">Edit Your Address</h4>
                    <p class="text-gray-600 mb-32">Update your default shipping address below.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-24">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.address.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" id="name" name="name" class="common-input" value="{{ old('name', $address->name ?? '') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" id="phone" name="phone" class="common-input" value="{{ old('phone', $address->phone ?? '') }}" required>
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Zone</label>
                                <input type="text" id="address" name="address" class="common-input" value="{{ old('address', $address->address ?? '') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="locality" class="form-label">Locality / District</label>
                                <input type="text" id="locality" name="locality" class="common-input" value="{{ old('locality', $address->locality ?? '') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="landmark" class="form-label">Landmark (Optional)</label>
                                <input type="text" id="landmark" name="landmark" class="common-input" value="{{ old('landmark', $address->landmark ?? '') }}">
                            </div>
                            <div class="col-sm-4">
                                <label for="city" class="form-label">City</label>
                                <input type="text" id="city" name="city" class="common-input" value="{{ old('city', $address->city ?? '') }}" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="state" class="form-label">Province</label>
                                <input type="text" id="state" name="state" class="common-input" value="{{ old('state', $address->state ?? '') }}" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="zip" class="form-label">Zip Code</label>
                                <input type="text" id="zip" name="zip" class="common-input" value="{{ old('zip', $address->zip ?? '') }}" required>
                            </div>
                            <div class="col-12 mt-32">
                                <button type="submit" class="btn btn-main">Save Address</button>
                                <a href="{{ route('user.address') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
@endsection