<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produits;

class Cut extends Model
{
    use HasFactory;


    protected $fillable = [
        'idProduit',
        'largeur',
        'longueur',
        'epaisseur',
        'perimetre'
    ];

    /**
     * Get the product that owns the cut.
     */
    public function produit()
    {
        return $this->belongsTo(Produits::class, 'idProduit');
    }
}
