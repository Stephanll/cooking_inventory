<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class RecipeIngredient extends Model
{
    use HasFactory;

    protected $table = 'recipe_ingredient';
    protected $fillable = ['recipe_id', 'ingredient_id', 'quantity', 'unit'];
}
