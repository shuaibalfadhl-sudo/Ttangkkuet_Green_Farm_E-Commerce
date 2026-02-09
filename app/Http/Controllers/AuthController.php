<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class AuthController extends Controller
{
	/**
	 * Display the login view.
	 */
	public function showLogin()
	{
		return view('auth.login');
	}

	/**
	 * Handle an authentication attempt.
	 */
	public function login(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
		]);

		if (Auth::attempt($credentials, $request->filled('remember'))) {
			$user = Auth::user();

			if ($user->email_verified_at === null) {
				Auth::logout();
				return back()->withErrors([
					'email' => 'You need to confirm your account. We have sent you an activation link, please check your email.',
				])->withInput($request->only('email'));
			}

			// Restore cart and wishlist from the database
			Cart::instance('cart')->restore($user->id);
			Cart::instance('wishlist')->restore($user->id);
			
			$request->session()->regenerate();

			return redirect()->intended('/');
		}

		return back()->withErrors([
			'email' => 'The provided credentials do not match our records.',
		])->withInput($request->only('email'));
	}

	/**
	 * Log the user out of the application.
	 */
	public function logout(Request $request)
	{
		if (Auth::check()) {
			// Store the cart and wishlist to the database before logging out
			Cart::instance('cart')->store(Auth::id());
			Cart::instance('wishlist')->store(Auth::id());
		}

		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect('/');
	}

	// show register page
	public function showRegister()
	{
		if (Auth::check()) {
			return redirect()->intended('/');
		}
		return view('auth.register');
	}

	// handle registration form submission
	public function register(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:8|confirmed',
		]);

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
		]);

		event(new Registered($user));

		// Auto-login the user after registration
		Auth::login($user);

		// redirect to profile or intended page
		return redirect()->intended('/');
	}

	// show forgot-password page
	public function showForgotPassword()
	{
		if (Auth::check()) {
			return redirect()->intended('/');
		}
		return view('auth.forgot-password');
	}

	// send reset link (supports AJAX and normal form submit)
	public function sendResetLink(Request $request)
	{
		$request->validate(['email' => 'required|email']);

		$status = Password::sendResetLink(
			$request->only('email')
		);

		// JSON / AJAX request
		if ($request->wantsJson() || $request->ajax()) {
			if ($status === Password::RESET_LINK_SENT) {
				return response()->json(['message' => 'Password reset link sent to your email.'], 200);
			}
			return response()->json(['message' => __($status)], 422);
		}

		// Normal form submit: redirect with flash messages
		if ($status === Password::RESET_LINK_SENT) {
			return redirect()->route('login')->with('status', trans($status));
		}

		return back()->withErrors(['email' => trans($status)])->withInput();
	}

	// handle password reset form submission
	public function resetPassword(Request $request)
	{
		$request->validate([
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|confirmed|min:8',
		]);

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) {
				$user->password = Hash::make($password);
				$user->setRememberToken(Str::random(60));
				$user->save();
			}
		);

		if ($status === Password::PASSWORD_RESET || $status === Password::PASSWORD_RESET) {
			// regenerate session id and CSRF token so subsequent login form has a fresh token
			$request->session()->regenerate();
			$request->session()->regenerateToken();

			// redirect to login with success message
			return redirect()->route('login')->with('status', trans($status));
		}

		return back()->withErrors(['email' => trans($status)]);
	}
}