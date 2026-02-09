@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <h3>Edit User Role: {{ $user->name }}</h3>
        <div class="wg-box">
            <form action="{{ route('admin.user.update_role', ['id' => $user->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-10">
                    <label for="utype" class="form-label">Select New Role</label>
                    <select name="utype" id="utype" class="wg-filter" required>
                        <option value="USR" {{ $user->utype == 'USR' ? 'selected' : '' }}>Customer (USR)</option>
                        <option value="ADM" {{ $user->utype == 'ADM' ? 'selected' : '' }}>Admin (ADM)</option>
                        <option value="RDR" {{ $user->utype == 'RDR' ? 'selected' : '' }}>Rider (RDR)</option>
                    </select>
                </div>
                
                @error('utype')
                    <div class="text-danger mb-4">{{ $message }}</div>
                @enderror
                <div class="d-flex justify-content-between">
                <button type="submit" class="tf-button style-1 w208 mb-10">Update Role</button>
                <a href="{{ route('admin.users') }}" class="tf-button style-2 w208">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection