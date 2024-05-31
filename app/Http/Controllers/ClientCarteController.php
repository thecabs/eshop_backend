<?php

namespace App\Http\Controllers;

use App\Models\clientCarte;
use App\Models\ville;
use Illuminate\Http\Request;

class ClientCarteController extends Controller
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
        $client = $request->validate([
            'matr' => 'required|numeric',
            'nom' => 'required|string',
            'sexe' => 'required|boolean',
            'dateNaiss' => 'required|string',
            'ville_id' => 'required|numeric',
            'mobile' => 'required|string',
            'whatsapp' => 'boolean',
            'montantTontine' => 'decimal'
        ]);

        $ville_id = $client["ville_id"];
        unset($client["ville_id"]);

        $ville = ville::find($ville_id);
        $client_creer = new clientCarte($client);
        $ville->clientCartes()->save($client_creer);

        return response()->json(["message" => "client creer", "client" => clientCarte::latest()->first()], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(clientCarte $clientCarte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, clientCarte $clientCarte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(clientCarte $clientCarte)
    {
        //
    }
}
