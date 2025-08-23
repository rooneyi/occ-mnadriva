<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Declaration;

class RapportAnalyse extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_rapport';
    protected $fillable = [
        'id_laborantin',
        'code_lab',
        'designation_produit',
        'quantite',
        'methode_essai',
        'aspect_exterieur',
        'resultat_analyse',
        'date_fabrication',
        'date_expiration',
        'conclusion',
    'fichier', // chemin du fichier rapport (PDF, etc.)
    'statut', // statut du rapport (en_attente, valide, rejete)
    ];
    // Suppression du cast id_declaration, le champ n'est plus utilisé
    public function laborantin()
    {
        return $this->belongsTo(Laborantin::class, 'id_laborantin', 'id_laborantin');
    }
    public function declaration()
    {
        return $this->belongsTo(Declaration::class, 'id_declaration', 'id_declaration');
    }
    public function dossiers()
    {
        return $this->belongsToMany(Dossier::class, 'dossier_rapport_analyse', 'id_rapport', 'id_dossier');
    }
}
