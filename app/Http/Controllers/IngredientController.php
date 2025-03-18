<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        // Create the ingredient
        Ingredient::create([
            'name' => $request->name,
            'category' => $request->category,
        ]);

        // Redirect back with a success message
        return redirect()->route('inventory.index')->with('success', 'Ingredient added successfully!');
    }
}