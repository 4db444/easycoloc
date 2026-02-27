<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index () {
        $user = Auth::user();
        $groups = $user->groups;
        return view("groups", compact("user", "groups"));
    }

    public function store (Request $request) {
        $validated = $request->validate([
            "name" => "required"
        ]);

        $user = Auth::user();

        $user->groups()->create([
            "admin_id" => $user->id,
            "name" => $validated["name"],
        ]);

        return redirect("/groups");
    }

    public function show (int $id) {
        $user = Auth::user();
        return view("group", compact("user"));
    }
}
