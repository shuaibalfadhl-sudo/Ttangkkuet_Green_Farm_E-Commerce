<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verified!</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}" type="image/x-icon">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- select 2 -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <!-- Slick -->
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
    <!-- Wow -->
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
    <!-- Main css -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/css/icons.css">
    <style>
        /* General page background to match the clean design */
        body {
            background-color: #f4f6f9; /* Light grey background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: sans-serif; /* Use a clean font */
        }
        
        /* Custom styles for the circular icon */
        .verification-icon {
            width: 100px;
            height: 100px;
            background-color: #28a745; /* Bootstrap Success Green */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem; 
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4); /* Green shadow */
        }

        /* Style for the checkmark icon (Assuming bi-check from Bootstrap Icons) */
        .verification-icon i {
            color: white;
            font-size: 3rem; 
        }

        /* Style for the card to match the clean white look */
        .verification-card {
            padding: 40px 30px;
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 90%; /* Responsive width */
            background-color: white;
            text-align: center;
        }
        
        /* Button style matching the green theme */
        .btn-success {
            background-color: #28aa46;
            border-color: #28a745;
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="card verification-card">
        
        <div class="verification-icon">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="40" cy="40" r="40" fill="#299E60" />
            <path
              d="M52.9743 35.7612C52.9743 35.3426 52.8069 34.9241 52.5056 34.6228L50.2288 32.346C49.9275 32.0446 49.5089 31.8772 49.0904 31.8772C48.6719 31.8772 48.2533 32.0446 47.952 32.346L36.9699 43.3449L32.048 38.4062C31.7467 38.1049 31.3281 37.9375 30.9096 37.9375C30.4911 37.9375 30.0725 38.1049 29.7712 38.4062L27.4944 40.683C27.1931 40.9844 27.0257 41.4029 27.0257 41.8214C27.0257 42.24 27.1931 42.6585 27.4944 42.9598L33.5547 49.0201L35.8315 51.2969C36.1328 51.5982 36.5513 51.7656 36.9699 51.7656C37.3884 51.7656 37.8069 51.5982 38.1083 51.2969L40.385 49.0201L52.5056 36.8996C52.8069 36.5982 52.9743 36.1797 52.9743 35.7612Z"
              fill="white" />
          </svg>
        </div>

        <h3 class="fw-bold mb-2 text-success">
            Verified!
        </h3>
        
        <p class="text-secondary mb-4">
            You have successfully verified your account.
        </p>

        <a href="{{ route('home.index') }}" class="btn btn-success fw-bold px-5 rounded-pill">
            Go to Home
        </a>
    </div>

</body>
</html>