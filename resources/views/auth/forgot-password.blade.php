@extends('layouts.apps')
@section('content')
<div class="container py-80">
	<h2 class="mb-24">Forgot your password?</h2>

	@if(session('status'))
		<div class="alert alert-success mb-16">
			{{ session('status') }}
		</div>
	@endif

	@if($errors->has('email'))
		<div class="alert alert-danger mb-16">
			{{ $errors->first('email') }}
		</div>
	@endif

	<form method="POST" action="{{ route('password.email') }}">
		@csrf
		<div class="mb-16">
			<label class="form-label">Email</label>
			<input type="email" name="email" value="{{ old('email') }}" class="common-input w-100" required autofocus>
		</div>

		<button type="submit" class="btn btn-main">Send password reset link</button>
		<a href="{{ route('login') }}" class="btn btn-link ms-12">Back to login</a>
	</form>
</div>
@endsection
