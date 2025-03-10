<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category'
    ];

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
    
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredient')
                    ->withPivot('quantity', 'unit')
                    ->withTimestamps();
    }

    public function shoppingLists()
    {
        return $this->hasMany(shoppingLists::class);
    }
}
