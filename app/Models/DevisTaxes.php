<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevisTaxes extends Model
{
    use HasFactory;

    protected $table = 'devis_taxes';

    protected $fillable = ['idDevis', 'idTaxe'];
}
