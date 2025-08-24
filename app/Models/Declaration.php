<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_declaration';
    protected $fillable = [
        'user_id',
        'id_controleur',
        'unite',
        'numero_impot',
        'date_soumission',
        'fichier',
        'statut',
        'user_id_controleur',
    ];
    // Si besoin d'un lien avec l'utilisateur (client)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function produits()
    {
        // On ajoute 'quantite' dans la table pivot si besoin
        return $this->belongsToMany(Produit::class, 'declaration_produit', 'id_declaration', 'id_produit')->withPivot('quantite');
    }
    public function rapports()
    {
        return $this->hasMany(RapportAnalyse::class, 'id_declaration', 'id_declaration');
    }
    public function dossiers()
    {
        return $this->belongsToMany(Dossier::class, 'dossier_declaration', 'id_declaration', 'id_dossier');
    }
    public function client()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function controleur()
    {
        return $this->belongsTo(User::class, 'user_id_controleur', 'id');
    }
// ...existing code...
}