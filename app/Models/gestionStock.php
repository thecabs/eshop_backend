<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GestionStock extends Model
{
    protected $table = 'gestion_stocks';

    protected $fillable = [
        'product_id', 
        'quantity', 
        'type', 
        'created_at'
    ];

    public function product()
    {
        return $this->belongsTo(Produit::class, 'produit_codePro', 'codePro');
    }
}
