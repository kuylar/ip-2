<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    function login(Request $request)
    {
        return view('auth.login');
    }

    function loginPost(Request $request)
    {
        $validated = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        if (Auth::attemptWhen($validated, function (User $user) use ($request) {
            UserLoginHistory::create([
                "user_id" => $user->id,
                "login_type" => "login",
                "params" => "user_agent=" . urlencode($request->header('User-Agent'))
            ]);
            return true;
        })) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'password' => 'Geçersiz kullanıcı adı veya şifre',
        ])->onlyInput("email");
    }

    function register(Request $request)
    {
        return view('auth.register');
    }

    function registerPost(Request $request)
    {
        $validated = $request->validate([
            "email" => "required|email",
            "password" => "required|min:8",
            "firstName" => "required",
            "lastName" => "required"
        ]);
        User::create([
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"]),
            "first_name" => $validated["firstName"],
            "last_name" => $validated["lastName"],
        ]);
        if (Auth::attempt($request->only("email", "password"))) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'password' => 'Geçersiz kullanıcı adı veya şifre',
        ])->onlyInput("email");
    }

    function logout() {
        UserLoginHistory::create([
            "user_id" => Auth::id(),
            "login_type" => "logout",
            "params" => ""
        ]);
        Auth::logout();
        return redirect()->intended('/auth/login');
    }
}
