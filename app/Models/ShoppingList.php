<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    protected $fillable = ['user_id', 'ingredient_id', 'quantity', 'unit'];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with the Ingredient model
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}