<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Controleur;
use App\Models\Declaration;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;

class ControleurController extends Controller
{
    // Liste des demandes assignées au contrôleur
    public function demandesAssignees()
    {
        $controleur = Auth::user();
        $demandes = Declaration::where('id_controleur', $controleur->id_controleur)->with('produits')->get();
        return view('controleur.demandes', compact('demandes'));
    }

    // Prendre une photo d'un produit (upload)
    public function prendrePhoto(Request $request, $produitId)
    {
        $request->validate([
            'photo' => 'required|image',
        ]);
        $produit = Produit::findOrFail($produitId);
        $path = $request->file('photo')->store('produit_photos');
        $produit->photo = $path;
        $produit->save();
        return back()->with('success', 'Photo ajoutée.');
    }

    // Ajouter un commentaire sur un produit
    public function ajouterCommentaire(Request $request, $produitId)
    {
        $request->validate([
            'commentaire' => 'required|string',
        ]);
        $produit = Produit::findOrFail($produitId);
        $produit->commentaire = $request->commentaire;
        $produit->save();
        return back()->with('success', 'Commentaire ajouté.');
    }

    // Valider un produit
    public function validerProduit($produitId)
    {
        $produit = Produit::findOrFail($produitId);
        $produit->statut = 'valide';
        $produit->save();
        return back()->with('success', 'Produit validé.');
    }

    // Rejeter un produit
    public function rejeterProduit($produitId)
    {
        $produit = Produit::findOrFail($produitId);
        $produit->statut = 'rejete';
        $produit->save();
        return back()->with('success', 'Produit rejeté.');
    }
}

