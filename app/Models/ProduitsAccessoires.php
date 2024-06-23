<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitsAccessoires extends Model
{
    use HasFactory;
    protected $table = 'produits_accessoires';
    protected $fillable = [
        'idProduit',
        'idAccessoire',
        'qte'
    ];

}
