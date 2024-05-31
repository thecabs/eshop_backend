<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class clientCarte extends Model
{
    use HasFactory;

    protected $primaryKey = 'matr';
    protected $keyType = 'unsignedInteger';
    protected $casts = [
        'matr' => 'int',
    ];

    public function ligneCartes(): HasMany
    {
        return $this->hasMany(ligneCarte::class);
    }
    public function ville(): BelongsTo
    {
        return $this->belongsTo(ville::class);
    }
    protected $fillable = [
        "matr",
        "nom",
        "sexe",
        "dateNaiss",
        "mobile",
        "whatsapp",
        "point",
        "montantTontine"
    ];
    protected $with = [
        'ligneCartes'
    ];
}
