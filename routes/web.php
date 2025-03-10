<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ShoppingListController;

// Recipe routes

Route::get('/recipes/feasible', [RecipeController::class, 'feasible'])->name('recipes.feasible')->middleware('auth');

Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index')->middleware('auth');



Route::resource('recipes', RecipeController::class)->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::resource('inventory', InventoryController::class);
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
});


require __DIR__.'/auth.php';

