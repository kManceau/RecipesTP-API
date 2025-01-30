<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = $_GET['limit'] ?? null;
        if($limit){
            $recipes = Recipe::with('ingredients')->take($limit)->orderBy('id', "DESC")->get();
        } else{
            $recipes = Recipe::with('ingredients')->get();
        }
        return response()->json($recipes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageService $imageService)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'type' => 'required|string|max:50',
                'description' => 'required|string',
            ]);
            $photoName = "";
            if($request->hasFile('photo')){
                $photoName = $request->get('name');
                $imageService->uploadImages($request->file('photo'), $photoName, 'recipes');
            }
            $recipe = Recipe::create(array_merge($request->all(), ['photo' => $photoName]));
            $recipe->save();

            return response()->json([
                'status' => 'Recette créée avec succès',
                'recipe' => $recipe
            ]);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Impossible de créer la recette'], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        $recipe->load('ingredients');
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
            'description' => 'string',
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
    public function destroy(Recipe $recipe, ImageService $imageService)
    {
        $imageService->deleteImages($recipe->photo, 'recipes');
        $recipe->delete();
        return response()->json(['status' => 'Recipe deleted successfully']);
    }
}
