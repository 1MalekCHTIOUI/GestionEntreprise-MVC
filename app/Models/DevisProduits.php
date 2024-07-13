<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevisProduits extends Model
{
    use HasFactory;

    protected $table = 'devis_produits';

    protected $fillable = [
        'idDevis', 'idProduit', 'qte'
    ];

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produits::class);
    }
}
