@extends('layouts.admin')

@section('content') 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
        .config-card {
            box-shadow: 0 4px 8px rgba(0,0,0,.05);
            border-radius: .5rem;
        }
        .upload-box {
            border: 2px dashed #ccc;
            padding: 2rem;
            text-align: center;
            border-radius: .3rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .upload-box:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }
        .upload-icon {
            font-size: 3rem;
            color: #6c757d;
        }
        /* Custom inactive link style for better visibility */
        .nav-pills .nav-link:not(.active) {
            color: #6c757d; /* grey color */
        }
    </style>
    <div class="container py-5">
        <h3 class="text-center mb-4">Manage all your website configurations in one place</h3>

        <ul class="nav nav-pills justify-content-center mb-4" id="configTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="advertisement-tab" data-bs-toggle="tab" data-bs-target="#advertisement" type="button" role="tab" aria-controls="advertisement" aria-selected="true"><i class="bi bi-megaphone me-1"></i> Advertisement</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="logo-tab" data-bs-toggle="tab" data-bs-target="#logo" type="button" role="tab" aria-controls="logo" aria-selected="false"><i class="bi bi-gem me-1"></i> Logo</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="logo-tab" data-bs-toggle="tab" data-bs-target="#delivery" type="button" role="tab" aria-controls="delivery" aria-selected="false"><i class="bi bi-truck me-1"></i> Delivery Fee</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false"><i class="bi bi-telephone me-1"></i> Contact Number & Email</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button" role="tab" aria-controls="bank" aria-selected="false"><i class="bi bi-bank me-1"></i> Bank Accounts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="socials-tab" data-bs-toggle="tab" data-bs-target="#socials" type="button" role="tab" aria-controls="socials" aria-selected="false"><i class="bi bi-share me-1"></i> Socials</button>
            </li>
            
        </ul>

        <div class="card p-4 config-card">
                <div class="tab-content" id="configTabContent">

                    <div class="tab-pane fade show active" id="advertisement" role="tabpanel" aria-labelledby="advertisement-tab">
                        <form class="" action="{{route('admin.ad.update')}}" method="POST" enctype="multipart/form-data">
                        <h4 class="mb-4">Advertisement Image</h4>  
                        @csrf
                        <fieldset> 
                            <div class="upload-image flex-grow">
                                
                                <div id="upload-file" class="item up-load">
                                    <label class="uploadfile" for="myFile">
                                    <div class="item" id="imgpreview" style="{{ $ads && $ads->image ? '' : 'display:none' }}">
                                        @if ($ads && $ads->image)
                                            <img src="{{ asset('uploads/ads/' . $ads->image) }}" class="effect8" alt="">
                                        @endif
                                    </div>
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="body-text">Drop your images here or select <span
                                                class="tf-color">click to browse</span></span>
                                        <input type="file" id="myFile" name="image" accept="image/*">
                                    </label>
                                </div>
                            </div>
                        </fieldset> 
                        @error('image')
                        <span class="alert alert-danger text-center">{{$message}}</span>
                        @enderror
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                            </div>
                        </form>
                    </div>
                    {{-- Logo --}}
                    <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab">
                        <form class="" action="{{route('admin.logo.update')}}" method="POST" enctype="multipart/form-data">
                        <h4 class="mb-4">Logo Settings</h4>  
                        @csrf
                        <p class="mb-10">Main Logo</p>
                        <fieldset class="mb-10"> 
                            <div class="upload-image flex-grow"> 
                                <div id="upload-file" class="item up-load">
                                    <label class="uploadfile" for="mainLogo">
                                    <div class="item" id="mainLogoPreview" style="{{ $logo && $logo->main_logo ? '' : 'display:none' }}">
                                        <img src="{{ $logo && $logo->main_logo ? asset('uploads/logo/main/' . $logo->main_logo) : '' }}" class="effect8" alt="" style="max-width:auto;">
                                    </div>
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="body-text">Drop your images here or select <span
                                                class="tf-color">click to browse</span></span>
                                        <input type="file" id="mainLogo" name="main_logo" accept="image/*">
                                    </label>
                                </div>
                            </div>
                        </fieldset> 
                        @error('main_logo')
                        <span class="alert alert-danger text-center">{{$message}}</span>
                        @enderror
                        <p class="mb-10">Sub Logo</p>
                        <fieldset class="mb-10"> 
                            <div class="upload-image flex-grow"> 
                                <div id="upload-file" class="item up-load">
                                    <label class="uploadfile" for="subLogo">
                                    <div class="item" id="subLogoPreview" style="{{ $logo && $logo->sub_logo ? '' : 'display:none' }}">
                                        <img src="{{ $logo && $logo->sub_logo ? asset('uploads/logo/sub/' . $logo->sub_logo) : '' }}" class="effect8" alt="" style="max-width:auto;">
                                    </div>
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="body-text">Drop your images here or select <span
                                                class="tf-color">click to browse</span></span>
                                        <input type="file" id="subLogo" name="sub_logo" accept="image/*">
                                    </label>
                                </div>
                            </div>
                        </fieldset> 
                        @error('image')
                        <span class="alert alert-danger text-center">{{$message}}</span>
                        @enderror
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                            </div>
                        </form>
                    </div>
                    {{-- Delivery --}}
                    <div class="tab-pane fade" id="delivery" role="tabpanel" aria-labelledby="logo-tab">
                        <h4 class="mb-4">Delivery Information</h4>

                        <form action="{{ route('admin.delivery.update') }}" method="POST">
                            @csrf

                            <div class="row mb-4">

                                {{-- Delivery Fee --}}
                                <div class="col-md-6 mb-3">
                                    <label for="delivery_charge" class="form-label">Delivery Fee</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            name="delivery_fee"
                                            id="delivery_charge"
                                            step="0.01"
                                            value="{{ old('delivery_fee', $delivery->delivery_fee ?? '') }}"
                                            placeholder="e.g., 50.00"
                                            required>
                                    </div>
                                </div>

                                {{-- Minimum Order Amount --}}
                                <div class="col-md-6 mb-3">
                                    <label for="min_order_amount" class="form-label">Minimum Order Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-cart-check"></i></span>
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            name="minimum_order_amount"
                                            id="min_order_amount"
                                            step="0.01"
                                            value="{{ old('minimum_order_amount', $delivery->minimum_order_amount ?? '') }}"
                                            placeholder="e.g., 500.00">
                                    </div>
                                </div>

                                <div class="col-12 text-end mt-3">
                                    <button class="btn btn-primary" type="submit">Save changes</button>
                                </div>

                            </div>
                        </form>
                    </div> 
                    {{-- Contact Information --}}
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <h4 class="mb-4">Contact Information</h4>
                        <form action="{{ route('admin.contact.info.update') }}" method="POST">
                            @csrf
                            <div class="row mb-4">

                                {{-- Phone --}}
                                <div class="col-md-6 mb-3">
                                    <label for="cellphoneNumber" class="form-label">Cellphone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                        <input 
                                            type="tel" 
                                            class="form-control" 
                                            id="cellphoneNumber" 
                                            name="phone" 
                                            value="{{ old('phone', $contactInfo->phone ?? '') }}" 
                                            placeholder="e.g., +010-9381-2595" 
                                            required>
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6 mb-3">
                                    <label for="ownerEmail" class="form-label">Owner's Email Account</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input 
                                            type="email" 
                                            class="form-control" 
                                            id="ownerEmail" 
                                            name="email" 
                                            value="{{ old('email', $contactInfo->email ?? '') }}" 
                                            placeholder="e.g., contact@gmail.com" 
                                            required>
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div class="col-md-12 mb-3">
                                    <label for="farmAddress" class="form-label">Farm Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="farmAddress" 
                                            name="address" 
                                            value="{{ old('address', $contactInfo->address ?? '') }}" 
                                            placeholder="e.g., Barangay Example, City, Province" 
                                            required>
                                    </div>
                                </div>

                                <div class="col-12 text-end mt-3">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>

                            </div>
                        </form>
                    </div>

                    {{-- Bank Accounts --}}
                    <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
                        <h4 class="mb-4">Bank Accounts</h4>

                        <form action="{{ route('admin.bank.update') }}" method="POST">
                            @csrf

                            <div class="row mb-4">
                                {{-- Bank 1 --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Bank Account 1</label>
                                    <input 
                                        type="text" 
                                        class="form-control mb-2" 
                                        name="bank_name_one" 
                                        placeholder="Bank Name (e.g., BDO, Metrobank)" 
                                        value="{{ old('bank_name_one', $bankInfo->bank_name_one ?? '') }}"
                                        required
                                    >

                                    <input 
                                        type="text" 
                                        class="form-control mb-2" 
                                        name="account_number_one" 
                                        placeholder="Account Number"
                                        value="{{ old('account_number_one', $bankInfo->account_number_one ?? '') }}"
                                        required
                                    >

                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="account_holder_one" 
                                        placeholder="Account Holder"
                                        value="{{ old('account_holder_one', $bankInfo->account_holder_one ?? '') }}"
                                        required
                                    >
                                </div>

                                {{-- Bank 2 --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Bank Account 2</label>
                                    <input 
                                        type="text" 
                                        class="form-control mb-2" 
                                        name="bank_name_two" 
                                        placeholder="Bank Name (e.g., BPI, PNB)"
                                        value="{{ old('bank_name_two', $bankInfo->bank_name_two ?? '') }}"
                                    >

                                    <input 
                                        type="text" 
                                        class="form-control mb-2" 
                                        name="account_number_two" 
                                        placeholder="Account Number"
                                        value="{{ old('account_number_two', $bankInfo->account_number_two ?? '') }}"
                                    >

                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="account_holder_two" 
                                        placeholder="Account Holder"
                                        value="{{ old('account_holder_two', $bankInfo->account_holder_two ?? '') }}"
                                    >
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary btn-lg" type="submit">Save changes</button>
                            </div>
                        </form>
                    </div> 
                    {{-- Social Media Links --}}
                    <div class="tab-pane fade" id="socials" role="tabpanel" aria-labelledby="socials-tab">
    <h4 class="mb-4">Social Media Links</h4>

    <form action="{{ route('admin.socials.update') }}" method="POST">
        @csrf

        <div class="row mb-4">

            {{-- Facebook --}}
            <div class="col-md-6 mb-3">
                <label for="facebookLink" class="form-label">Facebook (FB) URL</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                    <input 
                        type="url" 
                        class="form-control" 
                        id="facebookLink" 
                        name="facebook" 
                        placeholder="https://facebook.com/yourpage"
                        value="{{ old('facebook', $socialLinks->facebook ?? '') }}"
                    >
                </div>
            </div>

            {{-- Twitter --}}
            <div class="col-md-6 mb-3">
                <label for="twitterLink" class="form-label">Twitter/X URL</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-twitter-x"></i></span>
                    <input 
                        type="url" 
                        class="form-control" 
                        id="twitterLink" 
                        name="twitter" 
                        placeholder="https://twitter.com/yourhandle"
                        value="{{ old('twitter', $socialLinks->twitter ?? '') }}"
                    >
                </div>
            </div>

            {{-- Instagram --}}
            <div class="col-md-6 mb-3">
                <label for="instagramLink" class="form-label">Instagram URL</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                    <input 
                        type="url" 
                        class="form-control" 
                        id="instagramLink" 
                        name="instagram" 
                        placeholder="https://instagram.com/yourhandle"
                        value="{{ old('instagram', $socialLinks->instagram ?? '') }}"
                    >
                </div>
            </div>

            {{-- Kakao --}}
            <div class="col-md-6 mb-3">
                <label for="kakaoLink" class="form-label">Kakao ID/Link</label>
                <div class="input-group">
                    <span class="input-group-text">K</span>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="kakaoLink" 
                        name="kakaotalk" 
                        placeholder="KakaoTalk ID or Channel Link"
                        value="{{ old('kakaotalk', $socialLinks->kakaotalk ?? '') }}"
                    >
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-primary btn-lg" type="submit">Save changes</button>
        </div>
    </form>
</div>

                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
@push('scripts')
    <script>
        $(function(){
            $("#myFile").on("change",function(e){
                const photoInp = $("#myFile");
                const [file] = this.files;
                if(file){
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });
            $("#mainLogo").on("change", function(e){
                const [file] = this.files;
                if (file) {
                    $("#mainLogoPreview img").attr('src', URL.createObjectURL(file));
                    $("#mainLogoPreview").show();
                }
            });

            $("#subLogo").on("change", function(e){
                const [file] = this.files;
                if (file) {
                    $("#subLogoPreview img").attr('src', URL.createObjectURL(file));
                    $("#subLogoPreview").show();
                }
            });
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