<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your email address</title>
    <!-- Favicon -->{{-- Assuming $logo is available for the favicon, otherwise remove or hardcode --}}
    @isset($logo)
    <link rel="shortcut icon" href="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}" type="image/x-icon">
    @endisset
    
    <!-- Bootstrap (only essential if using its grid or some components, otherwise simplified) --><link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"> 
    {{-- Add any other essential CSS files if your main.css is critical for basic styling --}}
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}"> 

    <!-- Font Awesome for social icons, or link to Phosphor icons if you prefer 'ph ph-twitter' etc. -->{{-- I'll use Font Awesome for widespread compatibility with social icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .email-container {
            max-width: 600px;
            width: 100%;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            box-sizing: border-box; /* Include padding in width calculation */
        }
        .logo {
            margin-bottom: 30px;
        }
        .logo img {
            max-width: 120px;
            height: auto;
        }
        h1 {
            font-size: 24px;
            color: #333333;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555555;
            margin-bottom: 25px;
        }
        .verify-button {
            display: inline-block;
            background-color: #5cb85c; /* Green color for the button */
            color: #ffffff !important; /* !important to override Bootstrap if needed */
            padding: 15px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 30px;
            transition: background-color 0.3s ease;
            border: none; /* Remove button border */
            cursor: pointer;
        }
        .verify-button:hover {
            background-color: #4cae4c; /* Darker green on hover */
            color: #ffffff !important;
        }
        .link-text {
            font-size: 14px;
            color: #777777;
            margin-bottom: 20px;
        }
        .verification-link {
            font-size: 14px;
            color: #007bff; /* Blue for the link */
            word-break: break-all; /* Allow long links to break */
            margin-bottom: 30px;
            display: block; /* Make it a block element for margin */
        }
        .social-icons {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
        }
        .social-icons a {
            display: inline-block;
            margin: 0 10px;
            color: #aaaaaa;
            font-size: 24px;
            text-decoration: none;
        }
        .social-icons a:hover {
            color: #555555;
        }
        .footer-text {
            font-size: 12px;
            color: #888888;
            margin-top: 30px;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <div class="email-container">
        <div class="logo">
            @if (!empty($logo) && !empty($logo->sub_logo))
                <img
                    id="logo_mobile_1"
                    alt="Site Logo"
                    src="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}"
                    data-light="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}"
                    data-dark="{{ asset('uploads/logo/sub/' . $logo->sub_logo) }}">
            @else
                <img
                    id="logo_mobile_1"
                    alt="Default Logo"
                    src="{{ asset('assets/images/logo2.png') }}"
                    data-light="{{ asset('assets/images/logo2.png') }}"
                    data-dark="{{ asset('assets/images/logo2.png') }}">
            @endif
        </div>

        <h1>Verify your email address</h1>
        <p>
            Please verify your email address by clicking the button below. This helps us ensure the security of your account and provides you with full access to our services.
        </p>

        @if (session('resent'))
            <div class="alert alert-success mt-4" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif

        <!-- Main Verification Button (this will trigger a resend if already sent, or log in and verify if not) --><form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="verify-button">
                Verify my email
            </button>
        </form>
        <p class="verification-link">
            {{ __('If you did not receive the email, click the button above to request another.') }}
        </p>
        
        <!-- Social Icons --><div class="social-icons">
            @if(!empty($socialLinks?->twitter))
            <a href="{{ $socialLinks->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
            @endif
            @if(!empty($socialLinks?->facebook))
            <a href="{{ $socialLinks->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
            @endif
            @if(!empty($socialLinks?->instagram))
            <a href="{{ $socialLinks->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
            @endif
        </div>

        <!-- Footer --><p class="footer-text">
            © {{ date('Y') }} Your App Name. All rights reserved.<br>
            Your Company Address, City, Country - Postcode<br>
            @if (!empty($logo) && !empty($logo->main_logo))
                <img
                    id="logo_mobile_1"
                    alt="Site Logo"
                    src="{{ asset('uploads/logo/main/' . $logo->main_logo) }}"
                    data-light="{{ asset('uploads/logo/main/' . $logo->main_logo) }}"
                    data-dark="{{ asset('uploads/logo/main/' . $logo->main_logo) }}">
            @else
                <img
                    id="logo_mobile_1"
                    alt="Default Logo"
                    src="{{ asset('assets/images/logo2.png') }}"
                    data-light="{{ asset('assets/images/logo2.png') }}"
                    data-dark="{{ asset('assets/images/logo2.png') }}">
            @endif
        </p>
    </div>
<!-- Polling Script for Auto-Redirect -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check verification status every 5 seconds
            const pollingInterval = 5000; 
            const verificationStatusRoute = "{{ route('verification.status') }}";
            const homeRoute = "{{ url('/') }}";

            async function checkVerificationStatus() {
                try {
                    const response = await fetch(verificationStatusRoute, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            // Since this route is protected by 'auth' middleware, 
                            // Laravel should handle CSRF token checks via session cookie.
                        }
                    });

                    if (!response.ok) {
                        console.error('Network response was not ok, status:', response.status);
                        // Do not proceed with redirect on error
                        return;
                    }

                    const data = await response.json();

                    if (data.verified === true) {
                        // Verification successful, redirect the user
                        window.location.href = homeRoute;
                    }

                } catch (error) {
                    // console.error('Error fetching verification status:', error);
                    // You can optionally stop polling or log a silent error here
                }
            }

            // Start the polling interval
            const intervalId = setInterval(checkVerificationStatus, pollingInterval);

            // Optional: Clear the interval if the user navigates away or logs out, 
            // though the automatic refresh will handle most cases.
            window.onbeforeunload = function() {
                clearInterval(intervalId);
            };
        });
    </script>
    
</body>
</html>