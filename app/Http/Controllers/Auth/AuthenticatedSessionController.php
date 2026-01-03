<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


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

        // Important: regenerate session so login persists
        $request->session()->regenerate();

        // ✅ Redirect to intended page (dashboard by default)
        $user = $request->user();

        if ($user?->role === 'trainer') {
            return redirect()->intended(route('trainer.home'));
        }

        if ($user?->role === 'user') {
            return redirect()->intended(route('user.home'));
        }

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
