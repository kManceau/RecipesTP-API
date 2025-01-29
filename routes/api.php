<?php

use App\Http\Controllers\API\IngredientController;
use App\Http\Controllers\API\RecipeController;
use App\Http\Controllers\API\RecipeIngredientController;
use Illuminate\Support\Facades\Route;

Route::get('/ingredients', [IngredientController::class, 'index'])->name('ingredients.list');
Route::get('/ingredients/{ingredient}', [IngredientController::class, 'show'])->name('ingredients.show');
Route::post('/ingredients', [IngredientController::class, 'store'])->name('ingredients.store');
Route::put('/ingredients/{ingredient}', [IngredientController::class, 'update'])->name('ingredients.update');
Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');

Route::get('/recipes/', [RecipeController::class, 'index'])->name('recipes.list');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

Route::post('/add-ingredient', [RecipeIngredientController::class, 'addIngredientToRecipe'])->name('addIngredient');
