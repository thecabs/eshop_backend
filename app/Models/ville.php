<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ville extends Model
{
    use HasFactory;

    public function commandes(): HasMany
    {
        return $this->hasMany(commande::class)->latest();
    }
    public function clientCartes(): HasMany
    {
        return $this->hasMany(clientCarte::class);
    }
    protected $fillable = [
        "libelle"
    ];

}


