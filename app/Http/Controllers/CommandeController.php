<?php

namespace App\Http\Controllers;

use App\Models\commande;
use App\Models\ligneCommande;
use App\Models\produit;
use App\Models\ville;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = commande::query();
        $perPage = 10;
        $page = $request->input('page', 1);

        $type = $request->input('type');
        if (isset($type)) {
            $query->where('type', 'LIKE', $type);
        }
        $avance = $request->input('avance');
        if (isset($avance)) {
            $query->where('avance', '>', 0);
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $commande = $request->validate([
            "montant" => 'required|numeric',
            "nomClient" => ["required", "string", "min:2", "max:30"],
            "mobile" => ["required", "string", "max:20"],
            "addresse" => ["string"],
            "commentaire" => "string",
            "avance" => ["numeric"],
            "remise" => 'required|numeric',
            "ville_id" => 'required|numeric',
            "productList" => 'required|array|min:1'
        ]);

        $commande['avance'] = 0;
        $productList = $request->productList;
        $ville_id = $request->ville_id;
        unset($commande["ville_id"]);
        unset($commande["productList"]);

        $commande_creer = commande::create($commande);
        foreach ($productList as $ligneCommande) {
            $product = produit::find($ligneCommande["codePro"]);
            unset($ligneCommande["codePro"]);
            if (isset($product) && $product->qte >= $ligneCommande["qte"]) {
                $ligne_creer = new ligneCommande($ligneCommande);
                $product->ligneCommandes()->save($ligne_creer);
                $commande_creer->ligneCommandes()->save($ligne_creer);
            }

        }
        $commande_creer->save();

        $ville = ville::find($ville_id);
        $ville->commandes()->save($commande_creer);

        $commande_creer = commande::latest()->first();
        return response()->json([
            'status_message' => 'ok',
            "Commande" => $commande_creer
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function createVille(Request $request)
    {
        $ville = $request->validate([
            "libelle" => 'required|string'
        ]);
        $ville_creer = ville::create($ville);
        return response()->json([
            "Status_message" => "ville creer",
            "ville" => $ville_creer
        ], 201);
    }

    public function listVille()
    {
        return response()->json(["ville" => ville::latest()->get()], 200);
    }
    public function show(commande $commande)
    {
        return response()->json(["commande" => $commande], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(commande $commande, Request $request)
    {

        if ($commande->avance > 0) {
            return response()->json([
                "message" => "modification pas autorise"
            ], 401);
        }
        $request->validate([
            "montant" => ["numeric"],
            "nomClient" => ["string", "min:2", "max:30"],
            "mobile" => ["string", "max:20"],
            "addresse" => ["string"],
            "commentaire" => 'string',
            "livrer" => 'boolean',
            "avance" => ["numeric"],
            "remise" => ["numeric"],
            "type" => "boolean",
            "ville_id" => 'numeric',
            "productList" => 'array|min:1'
        ]);

        $request->montant && ($commande->montant = $request->montant);
        $request->nomCLient && ($commande->nomClient = $request->nomCLient);
        $request->mobile && ($commande->mobile = $request->mobile);
        $request->addresse && ($commande->addresse = $request->addresse);
        $request->commentaire && ($commande->commentaire = $request->commentaire);
        $request->livrer && ($commande->livrer = $request->livrer);
        $request->remise && ($commande->remise = $request->remise);
        $request->type && ($commande->type = $request->type);
        $commande->save();

        if ($request->ville_id) {
            $ville = ville::find($request->ville_id);
            $ville->commandes()->save($commande);
        }

        if ($request->productList) {
            $commande_id = $commande->id;
            ligneCommande::whereHas('commande', function ($query) use ($commande_id) {
                $query->where('commande_id', $commande_id);
            })->delete();

            $productList = $request->productList;

            foreach ($productList as $ligneCommande) {
                $product = produit::find($ligneCommande["codePro"]);
                unset($ligneCommande["codePro"]);
                if ($product->qte >= $ligneCommande["qte"]) {
                    $ligne_creer = new ligneCommande($ligneCommande);
                    $product->ligneCommandes()->save($ligne_creer);
                    $commande->ligneCommandes()->save($ligne_creer);
                }
            }
        }

        return response()->json([
            "message" => "modification reussie"
        ], 200);
    }


    public function faireAvance(commande $commande, Request $request)
    {
        $request->validate([
            'avance' => 'required|numeric'
        ]);
        if ($request->avance == 0) {
            return response()->json(["message" => "echec"]);
        }
        $commande_id = $commande->id;
        if ($commande->avance == 0) {
            $lignesCommande = ligneCommande::whereHas('commande', function ($query) use ($commande_id) {
                $query->where('commande_id', $commande_id);
            })->get();
            foreach ($lignesCommande as $ligne) {
                $produit = produit::find($ligne["produit_codePro"]);
                if ($produit->qte >= $ligne["qte"]) {
                    $produit->qte -= $ligne["qte"];
                    $produit->save();
                } else {
                    return response()->json([
                        "message" => $produit->nomPro . ' pas disponible'
                    ]);
                }
            }
        }
        $commande->avance = $request->avance;
        ($commande->avance >= (1 - $commande->remise / 100) * $commande->montant) && ($commande->type = true);
        $commande->save();


        return response()->json([
            "message" => "avance enregistrer"
        ], 201);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(commande $commande)
    {
        if ($commande->avance > 0) {
            $commande_id = $commande->id;
            $lignesCommande = ligneCommande::whereHas('commande', function ($query) use ($commande_id) {
                $query->where('commande_id', $commande_id);
            })->get();
            foreach ($lignesCommande as $ligne) {
                $produit = produit::find($ligne["produit_codePro"]);

                $produit->qte += $ligne["qte"];
                $produit->save();

            }
        }

        $commande->delete();
        return response()->json(["message" => "suppression reussie"], 200);
    }
}
