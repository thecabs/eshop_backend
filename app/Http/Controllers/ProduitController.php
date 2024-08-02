<?php

namespace App\Http\Controllers;

use App\Models\categorie;
use App\Models\photo;
use App\Models\produit;
use App\Models\LigneFacture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    public function index(Request $request)
{
    $query = produit::query();
    $perPage = 10;
    $page = $request->input('page', 1);

    # Filtre avec le mot
    $search = $request->input('search');
    if ($search) {
        $query->where('nomPro', 'LIKE', '%' . $search . '%')
            ->orWhere('description', 'LIKE', '%' . $search . '%')
            ->orWhere('codePro', 'LIKE', '%' . $search . '%');
    }

    # Filtre par prix
    $prix1 = $request->input('prix1');
    $prix2 = $request->input('prix2');
    if ($prix1 && $prix2) {
        $query->whereBetween('prix', [$prix1, $prix2]);
    }

    # Filtre par date de création
    $dateStart = $request->input('dateStart');
    $dateEnd = $request->input('dateEnd');
    if ($dateStart && $dateEnd) {
        $query->whereBetween('created_at', [$dateStart, $dateEnd]);
    }

    

    $total = $query->count();
    $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->latest()->get();

    return response()->json([
        'current_page' => $page,
        'last_page' => ceil($total / $perPage),
        'total' => $total,
        'items' => $result
    ], 200);
}


    public function produitsByCategorie(categorie $categorie, Request $request)
    {
        $query = produit::query();
        $perPage = 10;
        $page = $request->input('page', 1);
        $query->where('categorie_id', $categorie->id);
        #Filtre avec le mot
        $search = $request->input('search');
        if ($search) {
            $query->where('nomPro', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%');
        }
        #Filtre par prix
        $prix1 = $request->input('prix1');
        $prix2 = $request->input('prix2');
        if ($prix1 && $prix2) {
            $query->whereBetween('prix', [$prix1, $prix2]);
        }
        $total = $query->count();
        $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->latest()->get();
        return response()->json([
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'total' => $total,
            'items' => $result
        ], 200);
    }

    public function show(produit $produit)
    {
        return response()->json(["produit" => $produit], 200);
    }

    public function store(Request $request)
    {
        $produit = $request->validate([
            "codePro" => "required|numeric|unique:produits,codePro",
            "nomPro" => 'required|string',
            "prix" => 'required|numeric',
            "qte" => 'required|numeric',
            "description" => 'required|string',
            "codeArrivage" => 'required|string',
            "actif" => "boolean",
            "categorie_id" => "required|numeric",
            "prixAchat" => "required|numeric",
            "pourcentage" => 'required|numeric',
            "promo" => 'boolean'
        ]);
        $categorie_id = $request->categorie_id;

        unset($produit["categorie_id"]);
        $produit_creer = new produit($produit);
        $categorie = categorie::find($categorie_id);
        $categorie->produits()->save($produit_creer);

        return response()->json([
            "message" => "produit cree",
            "produit" => produit::latest()->first()
        ], 201);
    }

    public function update(produit $produit, Request $request)
    {
        $request->validate([
            "nomPro" => 'string',
            "prix" => 'numeric',
            "description" => 'string',
            "codeArrivage" => 'string',
            "actif" => "boolean",
            "categorie_id" => "numeric",
            "prixAchat" => "numeric",
            "pourcentage" => 'numeric',
            "promo" => 'boolean'
        ]);

        $request->nomPro && ($produit->nomPro = $request->nomPro);
        $request->prix && ($produit->prix = $request->prix);
        $request->description && ($produit->description = $request->description);
        $request->codeArrivage && ($produit->codeArrivage = $request->codeArrivage);
        $request->actif && ($produit->actif = $request->actif);
        $request->prixAchat && ($produit->prixAchat = $request->prixAchat);
        $request->pourcentage && ($produit->pourcentage = $request->pourcentage);
        $request->promo && ($produit->promo = $request->promo);

        $produit->save();

        return response()->json(["message" => "Modification reussie"], 200);
    }

    public function destroy(produit $produit)
    {
        $produit->delete();
        return response()->json([
            "message" => "Produit supprime"
        ], 200);
    }




    public function getQuantity($codePro)
{
    // Récupérer le produit basé sur le codePro fourni
    $product = Produit::where('codePro', $codePro)->first();

    // Si le produit n'existe pas, retourner une réponse d'erreur 404
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    // Obtenir la quantité courante du produit
    $qteCourant = $product->qte;

    // Récupérer toutes les ventes (lignes de factures) pour le produit
    $ligneFactures = LigneFacture::where('produit_codePro', $codePro)->get(['qte', 'created_at']);
    $qteVendu = $ligneFactures->sum('qte');
    $detailsVendu = $ligneFactures->map(function ($item) {
        return ['quantity' => $item->qte, 'date' => $item->created_at];
    });

    // Récupérer tous les enregistrements de gestion de stock pour le produit
    $gestionStocks = $product->gestionStocks()->get(['qte', 'operation', 'created_at']);

    // Calculer les quantités ajoutées et leurs dates
    $qteStock = $gestionStocks->where('operation', 1)->sum('qte');
    $detailsStock = $gestionStocks->where('operation', 1)->map(function ($item) {
        return ['quantity' => $item->qte, 'date' => $item->created_at];
    });

    // Calculer les quantités retirées et leurs dates
    $qteRetire = $gestionStocks->where('operation', 0)->sum('qte');
    $detailsRetire = $gestionStocks->where('operation', 0)->map(function ($item) {
        return ['quantity' => $item->qte, 'date' => $item->created_at];
    });

    // Retourner les données dans une réponse JSON
    return response()->json([
        'qte_courante' => $qteCourant,
        'qte_vendue' => $detailsVendu,
        'qte_ajoutee' => $detailsStock,
        'qte_retiree' => $detailsRetire,
    ]);
}

}
