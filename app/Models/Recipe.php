<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'description'];

    // Relationship with ingredients (through the pivot table)
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredient')
                    ->withPivot('quantity', 'unit')
                    ->withTimestamps();
    }

    /**
     * Check if the recipe can be made with the given inventory.
     *
     * @param \Illuminate\Database\Eloquent\Collection $inventory
     * @return array
     */
    public function canMake($inventory)
    {
        $missingIngredients = [];
        $canMake = true;
    
        foreach ($this->ingredients as $ingredient) {
            // Find the corresponding inventory item
            $inventoryItem = $inventory->where('ingredient_id', $ingredient->id)->first();
    
            // Check if the ingredient is missing or insufficient
            if (!$inventoryItem || $inventoryItem->quantity < $ingredient->pivot->quantity) {
                $missingIngredients[] = [
                    'name' => $ingredient->name,
                    'required' => $ingredient->pivot->quantity,
                    'available' => $inventoryItem ? $inventoryItem->quantity : 0,
                    'unit' => $ingredient->pivot->unit,
                    'ingredient_id' => $ingredient->id, 
                ];
                $canMake = false;
    
            }
        }
    
        return [
            'canMake' => $canMake,
            'missingIngredients' => $missingIngredients,
        ];
    }

}