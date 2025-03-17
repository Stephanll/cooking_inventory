<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Add this if user_id is required
            'ingredient_id' => Ingredient::factory(),
            'quantity' => $this->faker->numberBetween(1, 1000),
            'unit' => $this->faker->randomElement(['grams', 'liters', 'pieces']),
        ];
    }
}
