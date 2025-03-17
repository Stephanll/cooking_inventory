<?php

namespace Database\Seeders;

use App\Models\Inventory;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class InventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inventory::create([
            'user_id' => '21',
            'ingredient_id' => '3',
            'quantity' => '1',
            'unit' => 'liters',
            'expiration_date' => '2025-03-11',
        ]);
    }
}
