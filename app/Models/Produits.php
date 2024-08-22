<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produits extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'ref',
        'prixCharge',
        'prixVente',
        'qte',
        'qteMinGros',
        'prixGros',
        'promo',
        'longueur',
        'largeur',
        'hauteur',
        'profondeur',
        'tempsProduction',
        'matiers',
        'description',
        'descriptionTechnique',
        'ficheTechnique',
        'publicationSocial',
        'fraisTransport',
        'idCategorie',
        'imagePrincipale',
        'active'
    ];

    public function accessoires()
    {
        return $this->belongsToMany(Accessoires::class, 'produits_accessoires', 'idProduit', 'idAccessoire')->withPivot('qte')->withTimestamps();;
    }

    public function images()
    {
        return $this->hasMany(Images::class, 'idProduit');
    }

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'idCategorie');
    }

    public function devis()
    {
        return $this->belongsToMany(Devis::class, 'devis_produits', 'idProduit', 'idDevis')
            ->withPivot('qte')
            ->withTimestamps();
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotions_produits', 'idProduit', 'idPromotion');
    }
}
