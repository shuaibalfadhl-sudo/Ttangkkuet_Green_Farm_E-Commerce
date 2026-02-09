@extends('user.account-nav')
@section('contents')

<style>
    .profile-section {
        background-color: #fff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .profile-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 2rem;
        color: #000;
    }

    .profile-form-group {
        margin-bottom: 25px;
    }

    .profile-form-group label {
        font-size: 0.8rem;
        color: #888;
        text-transform: uppercase;
        margin-bottom: 5px;
        display: block;
        font-weight: 600;
    }

    .profile-form-group input.form-control {
        border: none;
        border-bottom: 1px solid #ddd;
        padding: 8px 0;
        border-radius: 0;
        background-color: transparent;
        font-size: 1.1rem;
        color: #333;
    }
    .profile-form-group input.form-control:focus {
        box-shadow: none;
        border-color: #c32929;
    }

    .save-button {
        background: linear-gradient(to right, #4CAF50, #81C784);
        border: none;
        color: white;
        padding: 15px 40px;
        border-radius: 30px; 
        font-size: 1.1rem;
        font-weight: 600;
        text-transform: uppercase;
        box-shadow: 0 8px 15px rgba(76, 175, 80, 0.3); 
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .save-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 20px rgba(76, 175, 80, 0.4); 
        opacity: 0.9;
    }
</style>
    <h1 class="profile-title">Change Password</h1>
    <div class="row profile-section">
        <div class="col-lg-8">
            {{-- Flash message --}}
            @if (session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger mt-3">{{ session('error') }}</div>
            @endif
            <form action="{{ route('user.update.password') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Current Password --}}
                <div class="profile-form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter your current password">
                    @error('current_password')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                {{-- New Password --}}
                <div class="profile-form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter your new password">
                    @error('new_password')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                {{-- Confirm Password --}}
                <div class="profile-form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Re-enter your new password">
                    @error('new_password_confirmation')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="mt-5 d-flex justify-content-end">
                    <button type="submit" class="save-button">Save</button>
                </div>

                
            </form>
        </div>
    </div> 
@endsection
