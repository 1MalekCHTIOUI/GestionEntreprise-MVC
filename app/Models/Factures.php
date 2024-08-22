<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factures extends Model
{
    use HasFactory;

    protected $fillable = [
        'idDevis',
        'ref',
        'date',
        'status',
        'totalHT',
        'totalTTC',
        'montant_restant'
    ];

    public function devis()
    {
        return $this->belongsTo(Devis::class, 'idDevis');
    }

    // public function client()
    // {
    //     return $this->devis()->belongsTo(Client::class, 'client_id');
    // }

    public function tresories()
    {
        return $this->hasMany(Tresorie::class, 'numFacture', 'ref');
    }

    public function remainingBalance()
    {
        $totalPayments = $this->tresories()->sum('montant');
        return $this->totalTTC - $totalPayments;
    }

    public function generateInvoiceNumber()
    {
        $year = date('Y');
        return sprintf('FAC-%03d-%d', $this->id, $year);
    }
}
