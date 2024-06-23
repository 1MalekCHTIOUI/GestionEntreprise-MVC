<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;
    protected $fillable = [
        'table',
        'id_record',
        'action',
        'data_before',
        'data_after',
        'changed_at',
        'changed_by',
    ];

    protected $casts = [
        'data_before' => 'array',
        'data_after' => 'array',
    ];
}
