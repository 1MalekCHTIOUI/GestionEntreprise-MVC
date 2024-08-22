<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'numFacture', 'montant', 'date', 'status'];

    public function customer()
    {
        return $this->belongsTo(Client::class);
    }

    public function facture()
    {
        return $this->belongsTo(Factures::class, 'numFacture', 'ref');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
