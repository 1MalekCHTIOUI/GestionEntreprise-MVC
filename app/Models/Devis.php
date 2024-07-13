<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'idClient', 'idProduit', 'qte', 'date', 'valid_until'
        'client_id', 'date', 'valid_until', 'status'
    ];

    protected $dates = ['date', 'valid_until'];

    public function produits()
    {
        return $this->belongsToMany(Produits::class, 'devis_produits', 'idDevis', 'idProduit')
            ->withPivot('qte')
            ->withTimestamps();
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'devis_taxes', 'idDevis', 'idTaxe');
    }
}
