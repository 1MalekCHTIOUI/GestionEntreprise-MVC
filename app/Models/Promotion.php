<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'image_footer', 'promo'];

    public function produits()
    {
        return $this->belongsToMany(Produits::class, 'promotions_produits', 'idPromotion', 'idProduit');
    }
}
