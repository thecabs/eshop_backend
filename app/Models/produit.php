<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
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
        return $this->belongsTo(Categorie::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(Size::class);
    }

    public function colors(): HasMany
    {
        return $this->hasMany(Color::class);
    }

    public function ligneCommandes(): HasMany
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function ligneFactures(): HasMany
    {
        return $this->hasMany(LigneFacture::class);
    }

    public function gestionStocks(): HasMany
    {
        return $this->hasMany(GestionStock::class, 'produit_codePro', 'codePro');
    }
}
