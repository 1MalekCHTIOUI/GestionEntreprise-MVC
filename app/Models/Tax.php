<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'rate'];

    public function devis()
    {
        return $this->belongsToMany(Devis::class, 'devis_tax', 'idTaxe', 'idDevis');
    }
}
