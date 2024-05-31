<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ligneCommande extends Model
{
    use HasFactory;

    public function commande(): BelongsTo
    {
        return $this->belongsTo(commande::class);
    }
    public function produit(): BelongsTo
    {
        return $this->belongsTo(produit::class);
    }

    protected $fillable = [
        "commande_id",
        "produit_codePro",
        'qte',
        "taille",
        "couleur",
    ];

}
