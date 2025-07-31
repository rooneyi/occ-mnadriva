<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_declaration';
    protected $fillable = [
        'id_client',
        'produit',
        'unite',
        'numero_import',
        'date_soumission',
        'document',
        'statut',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
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

    // Fonctionnalités Client
    public function joindreDocument($documentPath)
    {
        $this->document = $documentPath;
        $this->save();
    }

    public function recevoirNotification($message)
    {
        // Ici, on pourrait utiliser le système de notifications Laravel
        // Notification::send($this->client, new StatutDossierNotification($message));
    }

    public function telechargerRapport()
    {
        // Retourne le(s) rapport(s) lié(s) à la déclaration
        return $this->rapports;
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
