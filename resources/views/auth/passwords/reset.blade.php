@extends('layouts.apps')
@section('content')
<div class="container py-80">
	<div class="max-w-2xl mx-auto bg-white rounded-8 p-32 shadow-sm">
		<h2 class="mb-16 text-2xl fw-semibold">Reset Password</h2>

		@if (session('status'))
			<div class="alert alert-success mb-16">
				{{ session('status') }}
			</div>
		@endif

		@if ($errors->any())
			<div class="alert alert-danger mb-16">
				<ul class="mb-0">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form method="POST" action="{{ route('password.update') }}">
			@csrf
			<input type="hidden" name="token" value="{{ $token }}">

			<div class="mb-16">
				<label class="form-label">Email Address</label>
				<input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required class="common-input w-100 @error('email') is-invalid @enderror" autofocus>
				@error('email')
					<span class="text-danger small">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-16">
				<label class="form-label">Password</label>
				<input id="password" type="password" name="password" required class="common-input w-100 @error('password') is-invalid @enderror">
				@error('password')
					<span class="text-danger small">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-24">
				<label class="form-label">Confirm Password</label>
				<input id="password-confirm" type="password" name="password_confirmation" required class="common-input w-100">
			</div>

			<div class="d-flex gap-12">
				<button type="submit" class="btn btn-main">Reset Password</button>
				<a href="{{ route('login') }}" class="btn btn-secondary">Back to Login</a>
			</div>
		</form>
	</div>
</div>
@endsection

