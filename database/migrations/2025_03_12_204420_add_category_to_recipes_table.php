<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryToRecipesTable extends Migration
{
    public function up()
    {
        Schema::table('recipes', function (Blueprint $table) {
            // Add the `category` column
            $table->string('category')->after('name'); // Place it after the `name` column
        });
    }

    public function down()
    {
        Schema::table('recipes', function (Blueprint $table) {
            // Remove the `category` column
            $table->dropColumn('category');
        });
    }
}