<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'nom_societe',
        'tel1',
        'tel2',
        'whatsapp',
        'facebook_page',
        'instagram_account',
        'linkedin_page',
        'site_web',
        'email',
        'pays_id',
        'gouvernerat_id',
        'adresse',
        'matricul_fiscal',
        'secteur',
        'notes',
        'label_id',
        'logo'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'pays_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'gouvernerat_id');
    }

    public function label()
    {
        return $this->belongsTo(Label::class);
    }
}
