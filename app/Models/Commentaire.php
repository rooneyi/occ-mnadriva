<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    protected $fillable = [
        'contenu',
        'produit_id',
        'user_id',
    ];
}

