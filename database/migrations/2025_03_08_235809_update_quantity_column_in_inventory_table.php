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
        Schema::table('inventory', function (Blueprint $table) {
            $table->decimal('quantity', 8, 2)->change(); // 8 total digits, 2 decimal places
        });
    }
    
    public function down()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->integer('quantity')->change(); // Revert to integer if needed
        });
    }
};
