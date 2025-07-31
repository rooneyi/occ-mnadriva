<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Controleur extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_controleur';
    protected $fillable = [
        'id_utilisateur',
        'grade',
    ];
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur', 'id_utilisateur');
    }
    // Fonctionnalités Contrôleur
    public function demandesAssignees()
    {
        // À adapter selon la logique d'assignation (exemple : via une table de liaison ou un champ sur Declaration)
        // Ici, on suppose qu'il existe un champ id_controleur sur Declaration
        return Declaration::where('id_controleur', $this->id_controleur)->get();
    }

    public function prendrePhoto($produitId, $photoPath)
    {
        // À implémenter : sauvegarder le chemin de la photo pour le produit contrôlé
        // Exemple : ajouter une table produit_photos ou un champ photo sur Produit
    }

    public function ajouterCommentaire($produitId, $commentaire)
    {
        $produit = Produit::find($produitId);
        if ($produit) {
            $produit->commentaire = $commentaire;
            $produit->save();
            return $produit;
        }
        return null;
    }

    public function validerProduit($produitId)
    {
        $produit = Produit::find($produitId);
        if ($produit) {
            $produit->statut = 'valide';
            $produit->save();
            return $produit;
        }
        return null;
    }

    public function rejeterProduit($produitId)
    {
        $produit = Produit::find($produitId);
        if ($produit) {
            $produit->statut = 'rejete';
            $produit->save();
            return $produit;
        }
        return null;
    }
}
