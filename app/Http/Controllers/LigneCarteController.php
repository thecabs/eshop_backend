<?php

namespace App\Http\Controllers;

use App\Models\clientCarte;
use App\Models\facture;
use App\Models\ligneCarte;
use Illuminate\Http\Request;

class LigneCarteController extends Controller
{
    public function store(Request $request)
    {
        $point = 10000;
        $ligne = $request->validate([
            'facture_id' => 'required|numeric',
            'clientCarte_matr' => 'required|numeric',
        ]);
        $facture = facture::find($ligne['facture_id']);
        $client = clientCarte::find($ligne['clientCarte_matr']);

        $ligne_creer = new ligneCarte();
        $ligne_creer->montantFac = $facture->montant;
        $ligne_creer->point = $facture->montant / $point;

        $facture->ligneCartes()->save($ligne_creer);

        return response()->json(["message" => "ligne ajouter", "ligne" => ligneCarte::latest()->first()], 200);
    }
}
