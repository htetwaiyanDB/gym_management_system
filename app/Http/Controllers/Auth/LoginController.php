<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Handle login request (WITH IMAGE CAPTCHA)
     */
    public function login(Request $request)
    {
        // ✅ Validate input + CAPTCHA
        $request->validate([
            'email'   => ['required', 'email'],
            'password'=> ['required'],
            'captcha' => ['required', 'captcha'],
        ]);

        // ✅ Attempt authentication
        if (! Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            throw ValidationException::withMessages([
                'email' => 'Invalid email or password.',
            ]);
        }

        // ✅ Regenerate session (security)
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
