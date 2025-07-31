<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;use Illuminate\Foundation\Auth\User as Authenticatable; // <-- IMPORTANT


class Client extends Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'id_client';
    protected $fillable = [
        'adresse',
        'email',
        'password',
    ];
    /**
     * Pour que l'attribut password soit bien hashé et utilisable par l'auth Laravel
     */
    protected $hidden = [
        'password',
    ];

}
