@extends('user.account-nav')
@section('contents')

<style>
    /* Custom styles to match the provided image */
    .profile-section {
        background-color: #fff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .profile-title {
        font-size: 3rem; /* Larger font size for 'Profile' */
        font-weight: 700;
        margin-bottom: 2rem;
        color: #000;
    }

    .profile-form-group {
        margin-bottom: 25px; /* Spacing between form groups */
    }

    .profile-form-group label {
        font-size: 0.8rem;
        color: #888;
        text-transform: uppercase;
        margin-bottom: 5px;
        display: block;
        font-weight: 600;
    }

    .profile-form-group .form-control-plaintext {
        border: none;
        border-bottom: 1px solid #ddd;
        padding: 8px 0;
        border-radius: 0;
        background-color: transparent;
        font-size: 1.1rem;
        color: #333;
    }
    .profile-form-group .form-control-plaintext:focus {
        box-shadow: none;
        border-color: #c32929; /* Highlight on focus, or a subtle color */
    }
    .profile-form-group input.form-control { /* For editable inputs if you choose to make them editable */
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

    .profile-avatar-wrapper {
        position: relative;
        width: 150px; /* Adjust size as needed */
        height: 150px;
        margin: 0 auto 30px auto; /* Centered for smaller screens, adjusts with flex on larger */
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eee; /* Subtle border around image */
    }

    .profile-avatar-upload-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-in-out;
    }
    .profile-avatar-upload-btn:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .profile-avatar-upload-btn i {
        font-size: 1.2rem;
        color: #555;
    }
    .profile-avatar-input {
        display: none; /* Hide the default file input */
    }

    .save-button {
    background: linear-gradient(to right, #4CAF50, #81C784); /* Green Gradient */
    border: none;
    color: white;
    padding: 15px 40px;
    border-radius: 30px; 
    font-size: 1.1rem;
    font-weight: 600;
    text-transform: uppercase;
    /* CORRESPONDING CHANGE: Using an RGBA based on the darker green (#4CAF50) */
    box-shadow: 0 8px 15px rgba(76, 175, 80, 0.3); 
    transition: all 0.3s ease;
    cursor: pointer;
    }
    .save-button:hover {
        transform: translateY(-3px);
        /* CORRESPONDING CHANGE: Matching the hover shadow to the new green */
        box-shadow: 0 12px 20px rgba(76, 175, 80, 0.4); 
        opacity: 0.9;
    }
    .toggle-btn {
        border: none;
        padding: 14px 36px;
        border-radius: 30px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    /* Notification ON Style */
    .toggle-btn.on {
        background: linear-gradient(to right, #4CAF50, #81C784);
        color: #fff;
        box-shadow: 0 8px 15px rgba(76, 175, 80, 0.3);
    }
    .toggle-btn.on:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 20px rgba(76, 175, 80, 0.4);
    }

    /* Notification OFF Style */
    .toggle-btn.off {
        background: linear-gradient(to right, #bdbdbd, #e0e0e0);
        color: #555;
        box-shadow: 0 8px 15px rgba(158, 158, 158, 0.2);
    }
    .toggle-btn.off:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 20px rgba(189, 189, 189, 0.3);
    }

    .toggle-btn i {
        font-size: 1.3rem;
    }
</style> 
    <h1 class="profile-title">Profile</h1>
    
    <form action="{{ route('user.toggleUpdates') }}" method="POST">
        @csrf
        <button type="submit" 
            class="toggle-btn {{ optional(Auth::user()->notification)->receive_updates ? 'on' : 'off' }}">
            <i class="bi {{ optional(Auth::user()->notification)->receive_updates ? 'bi-bell-fill' : 'bi-bell-slash' }}"></i>
            {{ optional(Auth::user()->notification)->receive_updates ? 'Notifications: ON' : 'Notifications: OFF' }}
        </button>
    </form> 
    <div class="row profile-section">
        {{-- Left Column: User Details --}}
        <div class="col-lg-8">
            <form action="{{ route('user.update.profile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Use PUT method for updating --}}

                {{-- User Name --}}
                <div class="profile-form-group">
                    <label for="name">User Name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', Auth::user()->name ?? '') }}" 
                           placeholder="Enter your name">
                    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                {{-- Email --}}
                <div class="profile-form-group">
                    <label for="email">E-Mail</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email', Auth::user()->email ?? '') }}" 
                           placeholder="Enter your email" readonly> {{-- Email often readonly --}}
                    @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                {{-- Phone (New field) --}}
                <div class="profile-form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" 
                           value="{{ old('mobile', Auth::user()->mobile ?? '') }}" >
                    @error('mobile')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                
                {{-- Hidden input for file upload (if submitted with main form) --}}
                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="profile-avatar-input">

                <div class="mt-5 d-flex justify-content-end">
                    <button type="submit" class="save-button">Save</button>
                </div>
            </form>
        </div>
        
        {{-- Right Column: Profile Image --}}
        <div class="col-lg-4 d-flex justify-content-center align-items-start mt-4 mt-lg-0">
            <div class="profile-avatar-wrapper">
                <img 
                    id="currentProfileImage"
                    src="{{ Auth::user()->profile_image 
                        ? asset('uploads/profile_images/' . Auth::user()->profile_image) . '?' . time() 
                        : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'User') . '&background=6c757d&color=fff' }}"
                    alt="Profile Avatar"
                    class="profile-avatar"
                />

                <div class="profile-avatar-upload-btn" onclick="document.getElementById('profile_image').click()">
                    <i class="bi bi-camera"></i>
                </div>
            </div>
        </div>
    </div> 

{{-- Add Bootstrap Icons if not already included in your layout --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<script>
    // Script to instantly preview the selected image
    document.getElementById('profile_image').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            document.getElementById('currentProfileImage').src = URL.createObjectURL(file);
        }
    });
</script>

@endsection