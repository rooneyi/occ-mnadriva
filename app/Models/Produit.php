<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_produit';
    protected $fillable = [
        'nom',
        'description',
        // Ajoutez d'autres champs si besoin
    ];
}

