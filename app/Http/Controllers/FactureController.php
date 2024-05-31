<?php

namespace App\Http\Controllers;

use App\Models\facture;
use App\Models\ligneFacture;
use App\Models\produit;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $facture = $request->validate([
            "montant" => 'required|numeric',
            "remise" => 'numeric',
            "tel" => 'required|string',
            "typeFac" => 'required|numeric',
            "tva" => 'required|numeric',
            "capital" => 'required|numeric',
            "ligneFacture" => 'required|array|min:1'
        ]);

        $user = auth()->user();
        $ligneFacture = $facture['ligneFacture'];
        unset($facture['ligneFacture']);
        $facture_creer = facture::create($facture);

        foreach ($ligneFacture as $ligne) {
            $produit = produit::find($ligne['codePro']);
            if (isset($produit) && $produit->qte >= $ligne["qte"]) {
                $produit->qte -= $ligne["qte"];
                $produit->save();

                unset($ligne['codePro']);
                $ligne_creer = new ligneFacture($ligne);
                $ligne_creer->prix = $produit->prix;
                $ligne_creer->save();

                $produit->ligneFactures()->save($ligne_creer);
                $facture_creer->ligneFactures()->save($ligne_creer);
            }
        }

        $user->factures()->save($facture_creer);
        return response()->json([
            "message" => "Facture enregistrer",
            "facture" => facture::latest()->first()
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(facture $facture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, facture $facture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(facture $facture)
    {
        //
    }
}
