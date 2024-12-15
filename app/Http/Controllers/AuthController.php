<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function index(){
        return view('pages.auth.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Redirect to intended page or default dashboard
            return redirect()->intended('/');
        }

        // If authentication fails, redirect back with errors
        return back()->withErrors([
            'email' => 'Email / password salah!',
        ]);
    }

    public function logout(Request $request){
        // Log the user out
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Redirect to login or home page
        return redirect('/auth');
    }
}
