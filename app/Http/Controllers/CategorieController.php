<?php

namespace App\Http\Controllers;

use App\Models\categorie;
use Illuminate\Http\Request;
use Exception;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $categories = categorie::latest()->get();
        $baseUrl = url('/');

        return response()->json($categories, 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nomCat" => 'required|string|unique:categories,nomCat',
            "photo" => 'required|mimes:jpeg,png,jpg,gif|max:4096'
        ]);
        $photoName = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('images'), $photoName);

        $categorie = new categorie();
        $categorie->nomCat = $request["nomCat"];
        $categorie->image = 'images/' . $photoName;
        $categorie->save();

        return response()->json(['categorie' => $categorie], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Categorie::findOrFail($id);

        return response()->json([
            'id' => $category->id,
            'nomCat' => $category->nomCat,
            'image' => $category->image,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, categorie $categorie)
    {
        $request->validate([
            "nomCat" => 'string',
            "photo" => 'mimes:jpeg,png,jpg,gif|max:4096'
        ]);
        if ($request->nomCat) {
            $categorie->nomCat = $request->nomCat;
        }
        if ($request->photo) {

            unlink($categorie->image);
            $photoName = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('images'), $photoName);
            $categorie->image = 'images/' . $photoName;

        }

        $categorie->save();
        return response()->json([
            "message" => "categorie update",
            "categorie" => $categorie
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(categorie $categorie)
    {
        unlink($categorie->image);
        $value = $categorie->delete();
        return response()->json([
            'status_message' => "categorie supprime"
        ], 200);
    }
}
