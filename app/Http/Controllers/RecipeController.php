<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    // Display all recipes
    public function index()
    {
        $recipes = Recipe::with('ingredients')->get();
        return view('recipes.index', compact('recipes'));
    }

    // Show the form to create a new recipe
    public function create()
    {
        $ingredients = Ingredient::all();
        return view('recipes.create', compact('ingredients'));
    }

    // Store a new recipe
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:recipes,name',
            'category' => 'required|in:breakfast,lunch,snack,dinner',
            'description' => 'nullable|string',
            'ingredients' => 'required|array',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:0',
            'units' => 'required|array',
        ]);

        // Create the recipe
        $recipe = Recipe::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        // Attach ingredients to the recipe
        foreach ($request->ingredients as $index => $ingredientId) {
            $recipe->ingredients()->attach($ingredientId, [
                'quantity' => $request->quantities[$index],
                'unit' => $request->units[$index],
            ]);
        }

        return redirect()->route('recipes.index')->with('success', 'Recipe created successfully.');
    }

    // Show the form to edit a recipe
    public function edit(Recipe $recipe)
    {
        $ingredients = Ingredient::all();
        return view('recipes.edit', compact('recipe', 'ingredients'));
    }

    // Update a recipe
    public function update(Request $request, Recipe $recipe)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:recipes,name,' . $recipe->id,
            'category' => 'required|in:breakfast,lunch,snack,dinner',
            'description' => 'nullable|string',
            'ingredients' => 'required|array',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:0',
            'units' => 'required|array',
        ]);

        // Update the recipe
        $recipe->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        // Sync ingredients with quantities and units
        $ingredientsData = [];
        foreach ($request->ingredients as $index => $ingredientId) {
            $ingredientsData[$ingredientId] = [
                'quantity' => $request->quantities[$index],
                'unit' => $request->units[$index],
            ];
        }
        $recipe->ingredients()->sync($ingredientsData);

        return redirect()->route('recipes.index')->with('success', 'Recipe updated successfully.');
    }

    // Delete a recipe
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('recipes.index')->with('success', 'Recipe deleted successfully.');
    }

    /**
     * Show recipes the user can make based on their inventory.
     *
     * @return \Illuminate\View\View
     */
    public function feasible()
    {
        // Debugging: Check if the user is authenticated
        if (!auth()->check()) {
            dd('User is not authenticated');
        }
    
        // Get the authenticated user's inventory
        $inventory = auth()->user()->inventory;

        // Get all recipes
        $recipes = Recipe::with('ingredients')->get();
    
        // Check which recipes can be made
        $feasibleRecipes = [];
        foreach ($recipes as $recipe) {
            $result = $recipe->canMake($inventory);
            $feasibleRecipes[] = [
                'recipe' => $recipe,
                'canMake' => $result['canMake'],
                'missingIngredients' => $result['missingIngredients'],
            ];
        }
    
        return view('recipes.feasible', compact('feasibleRecipes'));
    }

    public function cook(Recipe $recipe)
    {
        // Get the authenticated user's inventory
        $inventory = auth()->user()->inventory;

        // Check if the recipe can be made
        $result = $recipe->canMake($inventory);

        if (!$result['canMake']) {
            return redirect()->route('recipes.feasible')->with('error', 'You do not have enough ingredients to cook this recipe.');
        }

        // Deduct the ingredients from the inventory
        foreach ($recipe->ingredients as $ingredient) {
            $inventoryItem = $inventory->where('ingredient_id', $ingredient->id)->first();

            if ($inventoryItem) {
                $inventoryItem->quantity -= $ingredient->pivot->quantity;

                // If the quantity reaches zero, delete the inventory item
                if ($inventoryItem->quantity <= 0) {
                    $inventoryItem->delete();
                } else {
                    $inventoryItem->save();
                }
            }
        }

        return redirect()->route('recipes.feasible')->with('success', 'Recipe cooked successfully! Inventory updated.');
    }

    public function undoCook(Recipe $recipe)
    {
        // Get the authenticated user's inventory
        $inventory = auth()->user()->inventory;
    
        // Restore the ingredients to the inventory
        foreach ($recipe->ingredients as $ingredient) {
            $inventoryItem = $inventory->where('ingredient_id', $ingredient->id)->first();
    
            if ($inventoryItem) {
                // If the inventory item exists, add the quantity back
                $inventoryItem->quantity += $ingredient->pivot->quantity;
                $inventoryItem->save();
            } else {
                // If the inventory item doesn't exist, create it
                auth()->user()->inventory()->create([
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $ingredient->pivot->quantity,
                    'unit' => $ingredient->pivot->unit,
                ]);
            }
        }
    
        return redirect()->route('recipes.feasible')->with('success', 'Cooking undone successfully! Inventory restored.');
    }
}