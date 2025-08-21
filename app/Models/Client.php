<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;use Illuminate\Foundation\Auth\User as Authenticatable; // <-- IMPORTANT


class Client extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'adresse',
        'email',
        'password',
        'remember_token',
    ];
    /**
     * Pour que l'attribut password soit bien hashé et utilisable par l'auth Laravel
     */
    protected $hidden = [
        'password',
    ];

}
