<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'id_utilisateur';
    protected $fillable = [
        'email',
        'mot_de_passe',
    ];
}

