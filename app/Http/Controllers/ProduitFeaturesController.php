<?php

namespace App\Http\Controllers;

use App\Models\color;
use App\Models\photo;
use App\Models\produit;
use App\Models\size;
use Illuminate\Http\Request;

class ProduitFeaturesController extends Controller
{
    public function destroyPhotos(photo $photo)
    {

        unlink($photo->lienPhoto);
        $photo->delete();
        return response()->json([
            'status_message' => "Photo supprime"
        ], 200);
    }

    public function storePhotos(produit $produit, Request $request)
    {
        $request->validate([
            "photo" => 'required|mimes:jpeg,png,jpg,gif|max:4096'
        ]);
        $photoName = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('images'), $photoName);

        $photo = new photo();
        $photo->lienPhoto = 'images/' . $photoName;
        $produit->photos()->save($photo);

        return response()->json([
            'status_message' => 'Photo ajoute',
        ], 201);
    }

    public function destroyColor(color $color)
    {
        $color->delete();
        return response()->json([
            'status_message' => "Couleur supprime"
        ], 200);
    }

    public function destroySize(size $size)
    {
        $size->delete();
        return response()->json([
            'status_message' => "Couleur supprime"
        ], 200);
    }
    public function storeColor(produit $produit, Request $request)
    {
        $request->validate([
            "name" => 'required|string'
        ]);
        $color = new color();
        $color->colorName = $request->name;
        $produit->colors()->save($color);
        return response()->json([
            "message" => "modification reussie"
        ], 201);

    }
    public function storeSize(produit $produit, Request $request)
    {
        $request->validate([
            "name" => 'required|string'
        ]);
        $size = new size();
        $size->sizeName = $request->name;
        $produit->sizes()->save($size);
        return response()->json([
            "message" => "modification reussie"
        ], 201);

    }

}
