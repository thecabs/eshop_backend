<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TontineController extends Controller
{
    public function store(Request $request)
    {
        $tontine = $request->validate([
            'montant' => 'required|numeric',
            'commentaire' => 'string',
            'validite' => 'required|boolean',

        ]);

    }
}
