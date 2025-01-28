<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredient::with(['recipe'])->get();
        return response()->json($ingredients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'description' => 'required|text',
        ]);
        $ingredient = new Ingredient();
        $ingredient->fill($formFields);
        $ingredient->save();
        return response()->json([
            'status' => 'Ingredient created successfully',
            $ingredient
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {
        $ingredient->load(['recipe']);
        return response()->json($ingredient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $formFields = $request->validate([
            'name' => 'string|max:100',
            'type' => 'string|max:50',
            'description' => 'text',
        ]);
        $ingredient->fill($formFields);
        $ingredient->save();
        return response()->json([
            'status' => 'Ingredient updated successfully',
            $ingredient
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return response()->json(['status' => 'Ingredient deleted successfully']);
    }
}
