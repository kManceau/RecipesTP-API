<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Recipe::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'description' => 'required|text',
        ]);
        $recipe = new Recipe();
        $recipe->fill($formFields);
        $recipe->save();
        return response()->json([
            'status' => 'Recipe created successfully',
            $recipe
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        return response()->json($recipe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        $formFields = $request->validate([
            'name' => 'string|max:255',
            'type' => 'string|max:50',
            'description' => 'text',
        ]);
        $recipe->fill($formFields);
        $recipe->save();
        return response()->json([
            'status' => 'Recipe updated successfully',
            $recipe
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return response()->json(['status' => 'Recipe deleted successfully']);
    }
}
