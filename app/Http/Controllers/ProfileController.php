<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $activeGroups = $user->active_groups()->withCount(['activeMembers'])->get();
        $totalExpensesPaid = $user->expenses()->sum('amount');
        $groupsJoined = $user->groups()->count();

        return view('profile', compact('user', 'activeGroups', 'totalExpensesPaid', 'groupsJoined'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre compte.',
        ]);

        $user->update($validated);

        return redirect('/profile')->with('success', 'Profil mis à jour avec succès !');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/profile')->with('success', 'Mot de passe modifié avec succès !');
    }

    public function showPublic(User $user)
    {
        $currentUser = Auth::user();

        // If viewing own profile, redirect to /profile
        if ($currentUser->id === $user->id) {
            return redirect('/profile');
        }

        $activeGroupsCount = $user->active_groups()->count();
        $totalGroupsJoined = $user->groups()->count();
        $memberSince = $user->created_at;

        return view('profile-public', compact('user', 'currentUser', 'activeGroupsCount', 'totalGroupsJoined', 'memberSince'));
    }
}
