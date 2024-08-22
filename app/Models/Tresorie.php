<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tresorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'montant',
        'type_paiement',
        'date',
        'numFacture',
        'date_cheque',
        'paye',
        'notes'
    ];

    public function facture()
    {
        return $this->belongsTo(Factures::class, 'numFacture', 'ref');
    }
}
