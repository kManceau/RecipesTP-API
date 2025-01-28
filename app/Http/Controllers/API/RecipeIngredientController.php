<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeIngredientController extends Controller
{
    public function addIngredientToRecipe(Request $request)
    {
        $recipe = Recipe::find($request->get('recipe_id'));
        $ingredient = Ingredient::find($request->get('ingredient_id'));
        $recipe->ingredients()->attach($ingredient, ['quantity' => $request->get('quantity')]);
        return response()->json(['status' => 'Ingredient added to recipe successfully']);
    }
}
