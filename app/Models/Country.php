<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'short_name', 'flag_img', 'country_code'];

    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'pays_id');
    }
}
