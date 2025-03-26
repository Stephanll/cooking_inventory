<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\IngredientController;

// Recipe routes

Route::get('/recipes/feasible', [RecipeController::class, 'feasible'])->name('recipes.feasible')->middleware('auth');

Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index')->middleware('auth');

Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy']);

Route::post('/ingredients', [IngredientController::class, 'store'])->name('ingredients.store');

Route::resource('recipes', RecipeController::class)->middleware('auth');

Route::get('/ingredients/search', [IngredientController::class, 'search'])->name('ingredients.search');

Route::middleware('auth')->group(function () {
    Route::resource('inventory', InventoryController::class);
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::delete('/inventory/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    // Edit inventory item route
    Route::get('/inventory/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    // Update inventory item route
    Route::put('/inventory/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
});


Route::resource('recipes', RecipeController::class)->middleware('auth');

Route::post('/recipes/{recipe}/cook', [RecipeController::class, 'cook'])->name('recipes.cook')->middleware('auth');

Route::post('/recipes/{recipe}/undo-cook', [RecipeController::class, 'undoCook'])->name('recipes.undoCook')->middleware('auth');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/shopping-list', [ShoppingListController::class, 'index'])->name('shopping-list.index');
    Route::post('/shopping-list', [ShoppingListController::class, 'store'])->name('shopping-list.store');
    Route::post('/shopping-list/finish', [ShoppingListController::class, 'finishShopping'])->name('shopping-list.finish')->middleware('auth');
    Route::delete('/shopping-list/{shoppingList}', [ShoppingListController::class, 'destroy'])->name('shopping-list.destroy');
    Route::post('/shopping-list/update-from-recipe', [ShoppingListController::class, 'updateFromRecipe'])->name('shopping-list.update-from-recipe');
});



require __DIR__.'/auth.php';

