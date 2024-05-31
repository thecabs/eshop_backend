<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'montant',
        'nomClient',
        'mobile',
        "addresse",
        "commentaire",
        "avance",
        "remise",
        "ville_id"
    ];

    protected $with = [
        'ligneCommandes'
    ];

    public function ligneCommandes(): HasMany
    {
        return $this->hasMany(ligneCommande::class);
    }
    public function ville(): BelongsTo
    {
        return $this->belongsTo(ville::class);
    }
}
