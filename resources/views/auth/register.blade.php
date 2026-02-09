@extends('layouts.apps')

@section('content')
<section class="account py-80">
    <div class="container container-lg">
        <form action="{{ route('register') }}" method="POST" name="login-form" >
            @csrf
            <div class="row gy-4">
                <!-- Register Card Start -->
                  <div class="col-xl-6 mx-auto">
                      <div class="border border-gray-100 hover-border-main-600 transition-1 rounded-16 px-24 py-40">
                          <h6 class="text-xl mb-32">Register</h6>
                          <div class="mb-24">
                              <label for="usernameTwo" class="text-neutral-900 text-lg mb-8 fw-medium">Username <span class="text-danger">*</span> </label>
                              <input type="text" class="common-input @error('name') is-invalid @enderror" id="usernameTwo" placeholder="Write a username" name="name" value="{{ old('name') }}" required="" autocomplete="name">
                              @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                          </div>
                          <div class="mb-24">
                              <label for="emailTwo" class="text-neutral-900 text-lg mb-8 fw-medium">Email address <span class="text-danger">*</span> </label>
                              <input type="email" class="common-input @error('email') is-invalid @enderror" id="emailTwo" placeholder="Enter Email Address" name="email" value="{{ old('email') }}" required="">
                              @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                          </div>
                          <div class="mb-24">
                              <label for="mobile" class="text-neutral-900 text-lg mb-8 fw-medium">Mobile number <span class="text-danger">*</span> </label>
                              <input type="number" class="common-input @error('mobile') is-invalid @enderror" id="mobile" placeholder="Enter Mobile Number" name="mobile" value="{{ old('mobile') }}" required="">
                              @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                          </div>
                          <div class="mb-24">
                              <label for="enter-password" class="text-neutral-900 text-lg mb-8 fw-medium">Password <span class="text-danger">*</span></label>
                              <div class="position-relative">
                                  <input type="password" class="common-input @error('password') is-invalid @enderror" id="enter-password" placeholder="Enter Password" name="password" required="">
                                  <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y cursor-pointer ph ph-eye-slash" id="#enter-password"></span>
                              </div>
                              @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                          </div>
                          <div class="mb-24">
                              <label for="enter-password" class="text-neutral-900 text-lg mb-8 fw-medium">Confirm Password <span class="text-danger">*</span></label>
                              <div class="position-relative">
                                  <input type="password" class="common-input" id="password-confirm" placeholder="Re-Enter Password" name="password_confirmation" required="" autocomplete="new-password">
                                  <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y cursor-pointer ph ph-eye-slash" id="#password-confirm"></span>
                              </div>
                          </div>
                          <div class="my-32 form-check">
                            <input class="form-check-input" type="checkbox" id="privacy_policy" required>
                            <label class="form-check-label text-gray-700" for="privacy_policy">
                                I agree to the
                                <a href="{{ route('home.privacy.policy') }}" target="_blank"
                                   class="text-main-600 text-decoration-underline">Privacy Policy</a>.
                            </label>
                        </div>
                          <div class="mt-48">
                              <button type="submit" class="btn btn-main py-18 px-40 w-100">Register</button>
                          </div>
                          <div class="customer-option mt-4 text-center">
                            <span class="text-secondary">Have an account?</span>
                            <a href="{{route('login')}}" class="text-main-600 text-sm fw-semibold hover-text-decoration-underline">Login to your Account</a>
                          </div>
                      </div>
                  </div>
                  <!-- Register Card End -->           
            </div>
        </form>
    </div>
 </section>

@endsection
