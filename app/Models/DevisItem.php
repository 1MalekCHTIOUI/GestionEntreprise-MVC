<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevisItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'idDevis',
        'description',
        'quantity',
        'unit_price',
        'total_price',
    ];

    // Define the relationship with Devis
    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    // Calculate the total price (optional)
    public function calculateTotalPrice()
    {
        return $this->qte * $this->unit_price;
    }
}
