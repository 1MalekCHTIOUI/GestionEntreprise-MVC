<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    public $timestamps = false; // Désactiver les timestamps automatiques
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['email', 'token', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
