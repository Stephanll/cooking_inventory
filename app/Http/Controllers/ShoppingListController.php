<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ShoppingList;
use App\Models\Recipe;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    // Display shoppinglist
    public function index()
    {
        // Get the authenticated user's shopping list
        $shoppingLists = auth()->user()->shoppingLists()->with('ingredient')->get();

        // Get all ingredients for the dropdown
        $ingredients = \App\Models\Ingredient::all();

        // Pass the data to the view
        return view('shopping-list.index', compact('shoppingLists', 'ingredients'));
    }

    // Store ingredient to shoppinglist
    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|in:grams,liters,pieces',
        ]);

        auth()->user()->shoppingLists()->create($request->only('ingredient_id', 'quantity', 'unit'));

        return redirect()->route('shopping-list.index')->with('success', 'Ingredient added to shopping list.');
    }

    // Remove ingredient from shoppinglist
    public function destroy(ShoppingList $shoppingList)
    {
        $shoppingList->delete();
        return redirect()->route('shopping-list.index')->with('success', 'Ingredient removed from shopping list.');
    }

    public function finishShopping()
    {
        // Get the authenticated user's shopping list
        $shoppingLists = auth()->user()->shoppingLists;

        // Check if the shopping list is empty
        if ($shoppingLists->isEmpty()) {
            return redirect()->route('shopping-list.index')->with('error', 'Your shopping list is empty.');
        }

        // Move items to the inventory
        foreach ($shoppingLists as $shoppingList) {
            // Check if the ingredient already exists in the inventory
            $inventoryItem = auth()->user()->inventory()->where('ingredient_id', $shoppingList->ingredient_id)->first();

            if ($inventoryItem) {
                // If it exists, update the quantity
                $inventoryItem->quantity += $shoppingList->quantity;
                $inventoryItem->save();
            } else {
                // If it doesn't exist, create a new inventory item
                auth()->user()->inventory()->create([
                    'ingredient_id' => $shoppingList->ingredient_id,
                    'quantity' => $shoppingList->quantity,
                    'unit' => $shoppingList->unit,
                ]);
            }

            // Remove the item from the shopping list
            $shoppingList->delete();
        }

        return redirect()->route('shopping-list.index')->with('success', 'Shopping list items moved to inventory.');
    }

    public function updateFromRecipe(Request $request)
    {
        // Fetch the recipe
        $recipe = Recipe::findOrFail($request->recipe_id);
    
        // Get the missing ingredients
        $missingIngredients = $recipe->canMake(auth()->user()->inventory)['missingIngredients'];
    
        // Add missing ingredients to the shopping list
        foreach ($missingIngredients as $missing) {
            // Calculate the quantity to add
            $quantityToAdd = $missing['required'] - $missing['available'];
            // Check if the ingredient already exists in the shopping list
            $existingItem = ShoppingList::where('user_id', auth()->id())
                ->where('ingredient_id', $missing['ingredient_id'])
                ->first();
    
            if ($existingItem) {
                // Update the quantity if the ingredient already exists
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $missing['required'],
                ]);
            } else {
                // Create a new entry if the ingredient doesn't exist
                ShoppingList::create([
                    'user_id' => auth()->id(),
                    'ingredient_id' => $missing['ingredient_id'],
                    'quantity' => $quantityToAdd,
                    'unit' => $missing['unit'],
                ]);
            }
        }
    
        // Redirect back with a success message
        return redirect()->route('recipes.feasible')->with('success', 'Shopping cart updated successfully!');
    }
}
