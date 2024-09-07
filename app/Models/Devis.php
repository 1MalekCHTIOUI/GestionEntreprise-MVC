<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    const STATUS_STILL = 'still';
    const STATUS_DONE = 'done';
    const STATUS_REFUSED = 'refused';

    protected $fillable = [
        // 'idClient', 'idProduit', 'qte', 'date', 'valid_until'
        'client_id', 'ref', 'date', 'valid_until', 'status', 'totalHT', 'totalServices', 'totalRemises', 'totalFraisLivraison', 'totalTTC'
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

    public function generateDevisNumber()
    {
        $year = date('Y');
        return sprintf('DEV-%03d-%d', $this->id, $year);
    }

    public function items()
    {
        return $this->hasMany(DevisItem::class, 'idDevis');
    }
}
