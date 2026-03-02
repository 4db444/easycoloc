<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function authenticate (Request $request) {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ], [
            "email.required" => "L'adresse email est obligatoire.",
            "email.email" => "Veuillez entrer une adresse email valide.",
            "password.required" => "Le mot de passe est obligatoire.",
        ]);

        if(Auth::attempt($credentials)){
            // Check if the user is banned
            if (Auth::user()->is_banned) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Votre compte a été banni. Contactez l\'administrateur pour plus d\'informations.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended("/dashboard");
        }

        return back()->withErrors([
            "email" => "Identifiants incorrects. Vérifiez votre email et mot de passe."
        ])->onlyInput("email");
    }

    public function store (Request $request){
        $validated = $request->validate([
            "first_name" => "required|string",
            "last_name" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed|min:8"
        ], [
            "first_name.required" => "Le prénom est obligatoire.",
            "last_name.required" => "Le nom est obligatoire.",
            "email.required" => "L'adresse email est obligatoire.",
            "email.email" => "Veuillez entrer une adresse email valide.",
            "email.unique" => "Cette adresse email est déjà utilisée.",
            "password.required" => "Le mot de passe est obligatoire.",
            "password.confirmed" => "Les mots de passe ne correspondent pas.",
            "password.min" => "Le mot de passe doit contenir au moins 8 caractères.",
        ]);

        if(!User::exists()){
            $validated["is_admin"] = true;
        }

        $user = User::create([
            "first_name" => $validated["first_name"],
            "last_name" => $validated["last_name"],
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"])
        ]);

        return redirect("/auth/login")->with("success", "Compte créé avec succès ! Connectez-vous.");
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
