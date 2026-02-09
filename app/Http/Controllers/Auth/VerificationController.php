<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    use VerifiesEmails;

    public function __construct()
    {
        $this->middleware('auth')->except(['verify']);
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Handle the email verification attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request) // Override the default verify method
    {
        $user = User::find($request->route('id'));

        if (! $user || $user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
             throw new \Illuminate\Auth\Access\AuthorizationException;
        }
        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
        }
        return redirect()->route('verification.success')->with('verified', true);
    }
    public function checkStatus(): JsonResponse
    {   
        $user = Auth::user();
        // Check if the currently authenticated user has verified their email
        $verified = $user && ($user->email_verified_at !== null);
        if ($verified) {
            return response()->json(['verified' => true]);
        }

        return response()->json(['verified' => false]);
    }
}