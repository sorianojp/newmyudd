<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = [
            'USER_ID' => $request->input('USER_ID'),
            'PASSWORD' => $request->input('PASSWORD'),
        ];

        $user = User::where('USER_ID', $credentials['USER_ID'])
            ->where('PASSWORD', $credentials['PASSWORD']) // Replace with hashed password logic later
            ->where('IS_VALID', 1)
            ->where('IS_DEL', 0)
            ->first();

        if ($user) {
            Auth::login($user);
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'USER_ID' => 'Invalid credentials.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
