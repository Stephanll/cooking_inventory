<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueToIngredientsTable extends Migration
{
    public function up()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            // Add unique constraint to the `name` column
            $table->string('name')->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            // Remove the unique constraint
            $table->string('name')->change();
        });
    }
}