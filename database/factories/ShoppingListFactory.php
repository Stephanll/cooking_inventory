<?php

namespace Database\Factories;

use App\Models\ShoppingList;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppingListFactory extends Factory
{
    protected $model = ShoppingList::class;

    public function definition()
    {
        
        return [
            'user_id' => User::factory(),
            'ingredient_id' => Ingredient::factory(),
            'quantity' => $this->faker->numberBetween(1, 1000),
            'unit' =>  $this->faker->randomElement(['grams', 'liters', 'pieces']),
        ];
    }
}
