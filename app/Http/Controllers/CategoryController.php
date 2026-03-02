<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Category;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function store (Request $request, Group $group) {
        if($group->admin->id === Auth::user()->id){
            $validated = $request->validate([
                "name" => "required"
            ]);
    
            $group->categories()->create($validated);
        }

        return redirect("/groups/" . $group->id);
    }

    public function destroy (Group $group, Category $category) {
        if(Auth::user()->id === $group->admin->id) $category->delete();
        return redirect()->back();
    }
}
