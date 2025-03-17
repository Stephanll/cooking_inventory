<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition()
    {
        
        return [
            'name' => $this->faker->word,
            'category' => $this->faker->randomElement(['Breakfast', 'Lunch', 'Snack', 'Dinner']),
            'description' => $this->faker->sentence,
        ];
    }
}
