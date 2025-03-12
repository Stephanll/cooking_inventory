<?php

namespace Database\Seeders;

use App\Models\Ingredient;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientsTableSeeder extends Seeder
{
    public function run()
    {
        Ingredient::create([
            'name' => 'Flour',
            'category' => 'Baking',
        ]);

        Ingredient::create([
            'name' => 'Milk',
            'category' => 'Dairy',
        ]);

        Ingredient::create([
            'name' => 'Eggs',
            'category' => 'Protein',
        ]);
    }
}