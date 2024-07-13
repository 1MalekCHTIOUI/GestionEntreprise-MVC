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
        'totalHT',
        'totalTTC',
    ];

    public function devis()
    {
        return $this->belongsTo(Devis::class, 'idDevis');
    }

    // public function client()
    // {
    //     return $this->devis()->belongsTo(Client::class, 'client_id');
    // }
}
