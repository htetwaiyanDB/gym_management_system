<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Mail\EmailVerificationCodeMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Breeze default: this will attempt login using email/password
        $request->authenticate();

        $user = $request->user();

        if ($user && ! $user->email_verified_at) {
            $verificationCode = (string) random_int(100000, 999999);

            $user->forceFill([
                'email_verification_code' => $verificationCode,
                'email_verification_expires_at' => now()->addMinutes(10),
            ])->save();

            Mail::to($user->email)->send(
                new EmailVerificationCodeMail($verificationCode)
            );

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('verify.form')
                ->with('email', $user->email)
                ->with('success', 'Verification code sent to your email.');
        }

        // Important: regenerate session so login persists
        $request->session()->regenerate();

        // ✅ Redirect to intended page (dashboard by default)
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        auth()->guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
