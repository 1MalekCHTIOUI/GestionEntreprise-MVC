<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessoires extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'titre',
        'description',
        'prixAchat',
        'prixVente',
        'qte',
        'image',
        'active'
    ];


    public function produits()
    {
        return $this->belongsToMany(Produits::class, 'produits_accessoires')->withPivot('qte')->withTimestamps();
    }
}
