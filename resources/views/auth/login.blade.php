@extends('layouts.apps')
@section('content')

<section class="account py-80">
    <div class="container container-lg">
        <form action="{{route('login')}}" method="POST" name="login-form" >
            @csrf
            <div class="row gy-4">

                <!-- Login Card Start -->
                <div class="col-xl-6 pe-xl-5 mx-auto">
                    <div class="border border-gray-100 hover-border-main-600 transition-1 rounded-16 px-24 py-40 h-100">
                        <h6 class="text-xl mb-32">Login</h6>
                        <div class="mb-24">
                            <label for="username" class="text-neutral-900 text-lg mb-8 fw-medium">Email address</label>
                            <input type="text" class="common-input @error('email') is-invalid @enderror" id="username" value="{{ old('email') }}" placeholder="Enter Email" required="" autocomplete="email" name="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-24">
                            <label for="password" class="text-neutral-900 text-lg mb-8 fw-medium">Password</label>
                            <div class="position-relative">
                                <input type="password" class="common-input @error('password') is-invalid @enderror" id="password" placeholder="Enter Password" value="{{ old('password') }}" required="" name="password">
                                <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y cursor-pointer ph ph-eye-slash" id="#password"></span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-24 mt-48">
                            <div class="flex-align gap-48 flex-wrap">
                                <button type="submit" class="btn btn-main py-18 px-40 w-100">Log in</button>
                            </div>
                        </div>
                        <div class="mt-48">
                            <a href="{{route('password.request')}}" class="text-danger-600 text-sm fw-semibold hover-text-decoration-underline">Forgot your password?</a> 
                        </div><div class="mt-48"> 
                            <span class="text-secondary">No account yet?</span>
                            <a href="{{route('register')}}" class="text-main-600 text-sm fw-semibold hover-text-decoration-underline">Create Account</a>
                        </div>
                    </div>
                </div>
                <!-- Login Card End -->                
            </div>
        </form>
    </div>
 </section>

@endsection
