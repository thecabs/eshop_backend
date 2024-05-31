<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class produit extends Model
{
    use HasFactory;

    protected $primaryKey = 'codePro';
    protected $keyType = 'unsignedInteger';

    protected $casts = [
        'codePro' => 'int',
    ];


    protected $with = [
        'photos',
        'sizes',
        'colors'
    ];

    protected $fillable = [
        "codePro",
        "nomPro",
        "prix",
        "qte",
        "description",
        "codeArrivage",
        "actif",
        "prixAchat",
        "pourcentage",
        "promo"
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(categorie::class);
    }


    public function photos(): HasMany
    {
        return $this->hasMany(photo::class);
    }
    public function sizes(): HasMany
    {
        return $this->hasMany(size::class);
    }
    public function colors(): HasMany
    {
        return $this->hasMany(color::class);
    }
    public function ligneCommandes(): HasMany
    {
        return $this->hasMany(ligneCommande::class);
    }
    public function ligneFactures(): HasMany
    {
        return $this->hasMany(ligneFacture::class);
    }


}
