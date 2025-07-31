<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChefService extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_chef_service';
    protected $fillable = [
        'id_utilisateur',
    ];
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur', 'id_utilisateur');
    }

    // Fonctionnalité Chef de service : Vue d'ensemble des dossiers
    public function voirTableauDeBord()
    {
        // Retourne tous les dossiers avec leurs déclarations et rapports associés
        return Dossier::with(['declarations', 'rapports'])->get();
    }
}
