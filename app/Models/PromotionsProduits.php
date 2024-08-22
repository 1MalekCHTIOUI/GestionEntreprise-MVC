<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionsProduits extends Model
{
    use HasFactory;
    protected $table = 'promotions_produits';
    protected $fillable = [
        'idPromotion',
        'idProduit'
    ];
}
