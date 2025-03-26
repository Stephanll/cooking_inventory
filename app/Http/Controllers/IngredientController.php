<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ingredients,name',
            'category' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first('name')
            ], 422);
        }

        $ingredient = Ingredient::create([
            'name' => $request->name,
            'category' => $request->category,
        ]);

        return response()->json([
            'success' => 'Ingredient added successfully!',
            'redirect' => route('inventory.index')
        ]);
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $ingredients = Ingredient::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'category']);
        
        return response()->json($ingredients);
    }
}