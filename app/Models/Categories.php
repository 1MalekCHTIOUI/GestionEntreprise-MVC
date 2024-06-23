<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'titreCateg', 'descriptionCateg', 'idParentCateg',
    ];

    public function parent()
    {
        return $this->belongsTo(Categories::class, 'idParentCateg');
    }

    public function sousCategories()
    {
        return $this->hasMany(Categories::class, 'idParentCateg');
    }


}
