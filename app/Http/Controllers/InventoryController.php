<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Ingredient;

class InventoryController extends Controller
{

    // Display the user's inventory
    public function index()
    {
        // Get the authenticated user's inventory
        $inventory = auth()->user()->inventory()->with('ingredient')->get();

        return view('inventory.index', compact('inventory'));
    }

    // Show the form to add a new inventory item
    public function create()
    {
        // Get all ingredients for the dropdown
        $ingredients = Ingredient::all();

        return view('inventory.create', compact('ingredients'));
    }

    // Store a new inventory item
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|in:liters,grams,pieces',
            'expiration_date' => 'required|date',
        ]);

        // Create the inventory item
        auth()->user()->inventory()->create([
            'ingredient_id' => $request->ingredient_id,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'expiration_date' => $request->expiration_date,
        ]);

        return redirect()->route('inventory.index')
                         ->with('success', 'Inventory item added successfully.');
    }

    // Show the form to edit an inventory item
    public function edit(Inventory $inventory)
    {
        // Ensure the user can only edit their own inventory
        if ($inventory->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get all ingredients for the dropdown
        $ingredients = Ingredient::all();

        return view('inventory.edit', compact('inventory', 'ingredients'));
    }

    // Update an inventory item
    public function update(Request $request, Inventory $inventory)
    {
        // Ensure the user can only update their own inventory
        if ($inventory->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|in:liters,grams,pieces',
            'expiration_date' => 'required|date',
        ]);

        // Update the inventory item
        $inventory->update([
            'ingredient_id' => $request->ingredient_id,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'expiration_date' => $request->expiration_date,
        ]);

        return redirect()->route('inventory.index')
                         ->with('success', 'Inventory item updated successfully.');
    }

    // Delete an inventory item
    public function destroy(Inventory $inventory)
    {
        // Ensure the user can only delete their own inventory
        if ($inventory->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the inventory item
        $inventory->delete();

        return redirect()->route('inventory.index')
                         ->with('success', 'Inventory item deleted successfully.');
    }
}