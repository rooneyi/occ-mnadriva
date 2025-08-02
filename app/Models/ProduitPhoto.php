<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitPhoto extends Model
{
    use HasFactory;
    protected $table = 'produit_photos';
    protected $fillable = [
        'produit_id',
        'chemin_photo',
    ];
}

