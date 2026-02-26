<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function authenticate (Request $request) {
        $credentials = $request->validate([
            "email" => "required|email|",
            "password" => "required"
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            return redirect()->intended("/dashboard");
        }

        return back()->withErrors([
            "email" => "wrong credentials"
        ])->onlyInput("email");
    }

    public function store (Request $request){
        $validated = $request->validate([
            "first_name" => "required|string",
            "last_name" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed|min:8"
        ]);

        if(!User::exists()){
            $validated["is_admin"] = true;
        }

        $user = User::create($validated);

        return redirect("/auth/login");
    }

    public function logout () {
        Auth::logout();
        return redirect("/dashboard");
    }
    
    public function login () {
        return view("login");
    }

    public function signup () {
        return view ("signup");
    }
}
