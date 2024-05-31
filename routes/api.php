<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ClientCarteController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\GestionnaireController;
use App\Http\Controllers\LigneCarteController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProduitFeaturesController;
use App\Models\facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(ProduitController::class)->group(function () {
    Route::get('/produitsList', 'index');
    Route::get('/showProduit/{produit}', 'show');
    Route::get('/produitByCategories/{categorie}', 'produitsByCategorie');

});


Route::controller(CommandeController::class)->group(function () {
    Route::post('/createCommande', 'store');
    Route::post('/updateCommande/{commande}', 'update');
    Route::post('/faireAvance/{commande}', 'faireAvance');
    Route::get('/voirCommande/{commande}', 'show');
    Route::get('/listVille', 'listVille');
});

Route::get('/listCategories', [CategorieController::class, 'index']);

Route::controller(GestionnaireController::class)->group(function () {
    Route::post('/createGest', 'store');
    Route::post('/loginGest', 'login');
});



Route::group(["middleware" => ['auth:sanctum']], function () {
    Route::get('/logoutGest', [GestionnaireController::class, 'logout']);
    Route::post('/createFac', [FactureController::class, 'store']);

    Route::controller(ProduitController::class)->group(function () {
        Route::post('/updateProduit/{produit}', 'update');
        Route::post('/createProduit', 'store');
    });

    Route::controller(ProduitFeaturesController::class)->group(function () {
        Route::get('/dropPhoto/{photo}', 'destroyPhotos');
        Route::post('/addPhoto/{produit}', 'storePhotos');
        Route::get('/dropSize/{size}', 'destroySize');
        Route::post('/addSize/{produit}', 'storeSize');
        Route::get('/dropColor/{color}', 'destroyColor');
        Route::post('/addColor/{produit}', 'storeColor');

    });


    Route::post('/createVille', [CommandeController::class, 'createVille']);
    Route::get('/listCommande', [CommandeController::class, 'index']);
    Route::get('/dropCommande/{commande}', [CommandeController::class, 'destroy']);

    Route::post('/createCategorie', [CategorieController::class, 'store']);
    Route::post('/updateCategorie/{categorie}', [CategorieController::class, 'update']);
    Route::get('/dropCategorie/{categorie}', [CategorieController::class, 'destroy']);

    Route::post('/createClientCarte', [ClientCarteController::class, 'store']);
    Route::post('/createLigneCarte', [LigneCarteController::class, 'store']);
});
