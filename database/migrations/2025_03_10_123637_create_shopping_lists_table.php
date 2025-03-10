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
    Schema::create('shopping_lists', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to the user
        $table->foreignId('ingredient_id')->constrained()->onDelete('cascade'); // Link to the ingredient
        $table->decimal('quantity', 8, 2); // Quantity of the ingredient
        $table->string('unit'); // Unit of the ingredient (e.g., grams, liters)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_lists');
    }
};
