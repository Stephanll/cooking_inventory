<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('recipe_ingredient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 8, 2); // 8 total digits, 2 decimal places
            $table->string('unit'); // Unit of the ingredient (e.g., grams, liters)
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredient');
    }
};
