<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'timbre_fiscale',
        'tva',
        'fodec',
        'cachet',
        'logo',
        'titre',
        'tel',
        'email',
        'adresse',
        'numero_fiscal'
    ];
}
