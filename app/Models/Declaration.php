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
        'designation_produit',
        'quantiter',
        'unite',
        'numero_impot',
        'date_soumission',
        'fichier',
        'statut',
        'id_controleur', // Ajouté pour l'assignation au contrôleur
    ];
    // Si besoin d'un lien avec l'utilisateur (client)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'declaration_produit', 'id_declaration', 'id_produit');
    }
    public function rapports()
    {
        return $this->hasMany(RapportAnalyse::class, 'id_declaration', 'id_declaration');
    }
    public function dossiers()
    {
        return $this->belongsToMany(Dossier::class, 'dossier_declaration', 'id_declaration', 'id_dossier');
    }

    // Actions métier demandées
    public function modifier($data)
    {
        $this->update($data);
    }

    public function joindreFichier($filePath)
    {
        $this->fichier = $filePath;
        $this->save();
    }

    public function envoyer()
    {
        $this->statut = 'envoyée';
        $this->save();
    }

    // Fonctionnalités Contrôleur (à implémenter côté Controleur.php)
    // public function photos() {...}
    // public function commentaires() {...}
    // public function validerProduit() {...}
    // public function rejeterProduit() {...}

    // Fonctionnalités Laborantin (à implémenter côté RapportAnalyse.php)
    // public function remplirResultatAnalyse() {...}
    // public function genererRapport() {...}
    // public function soumettreRapport() {...}
    // public function consulterHistorique() {...}

    // Fonctionnalités Chef de service (à implémenter côté Dossier.php)
    // public function voirTableauDeBord() {...}
}
