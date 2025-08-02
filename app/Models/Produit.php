<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_produit';
    protected $fillable = [
        'categorie_produit',
        'nom_produit',
        'description',
        'commentaire',
        'date_fabrication',
        'date_expiration',
    ];
}

