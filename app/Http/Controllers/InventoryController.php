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
        $request->validate([
            'ingredient_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'expiration_date' => 'nullable|date',
        ]);

        // Check if this user already has this ingredient in their inventory
        $existingInventory = auth()->user()->inventory()
            ->whereHas('ingredient', function($query) use ($request) {
                $query->where('name', $request->ingredient_name);
            })->first();

        if ($existingInventory) {
            return response()->json([
                'error' => 'You already have this ingredient in your inventory. Please update the existing item.'
            ], 422);
        }

        // Find or create the ingredient
        $ingredient = Ingredient::firstOrCreate(
            ['name' => $request->ingredient_name],
            ['category' => $request->category]
        );

        // Create the inventory item
        $inventoryItem = Inventory::create([
            'ingredient_id' => $ingredient->id,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'expiration_date' => $request->expiration_date,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'id' => $inventoryItem->id,
            'ingredient' => $ingredient,
            'quantity' => $inventoryItem->quantity,
            'unit' => $inventoryItem->unit,
            'expiration_date' => $inventoryItem->expiration_date,
        ]);
    }
    // Show the form to edit an inventory item
    public function edit(Inventory $inventory)
    {
        // Ensure the user can only edit their own inventory
        if ($inventory->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Return the inventory item data as JSON
        return response()->json([
            'id' => $inventory->id,
            'ingredient' => $inventory->ingredient,
            'quantity' => $inventory->quantity,
            'unit' => $inventory->unit,
            'expiration_date' => $inventory->expiration_date,
        ]);
    }

    // Update an inventory item
    public function update(Request $request, Inventory $inventory)
    {
        // Authorization check
        if ($inventory->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    
        $request->validate([
            'ingredient_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'expiration_date' => 'required|date',
        ]);
    
        // Update the ingredient
        $inventory->ingredient->update([
            'name' => $request->ingredient_name,
            'category' => $request->category,
        ]);
    
        // Update the inventory item
        $inventory->update([
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'expiration_date' => $request->expiration_date,
        ]);
    
        return response()->json([
            'id' => $inventory->id,
            'ingredient' => $inventory->ingredient,
            'quantity' => $inventory->quantity,
            'unit' => $inventory->unit,
            'expiration_date' => $inventory->expiration_date,
        ]);
    }

    // Delete an inventory item
    public function destroy(Inventory $inventory)
    {
        // Authorization check
        if ($inventory->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only delete the inventory item, not the ingredient
        $inventory->delete();

        return response()->json(['success' => true]);
    }
}