<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = $_GET['limit'] ?? null;
        if($limit){
            $ingredients = Ingredient::with(['recipes'])->take($limit)->orderBy('id', 'DESC')->get();
        }else {
            $ingredients = Ingredient::with(['recipes'])->get();
        }
        return response()->json($ingredients);
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
            if($request->hasFile("photo")){
                $photoName = $request->get('name');
                $imageService->uploadImages($request->file("photo"), $photoName, "ingredients");
            }
            $ingredient = Ingredient::create(array_merge($request->all(), ['photo' => $photoName]));
            $ingredient->save();

            return response()->json([
                'status' => 'Ingrédient créé avec succès',
                'ingredient' => $ingredient
            ]);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Impossible de créer l\'ingrédient'], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {
        $ingredient->load(['recipes']);
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
            'description' => 'string',
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
    public function destroy(Ingredient $ingredient, ImageService $imageService)
    {
        $imageService->deleteImages($ingredient->photo, "ingredients");
        $ingredient->delete();
        return response()->json(['status' => 'Ingredient deleted successfully']);
    }
}
