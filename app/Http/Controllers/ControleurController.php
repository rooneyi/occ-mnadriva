<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Controleur;
use App\Models\Declaration;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;

class ControleurController extends Controller
{
    // Dashboard Contrôleur
    public function dashboard()
    {
        $controleur = Auth::user();
        $demandes = \App\Models\Declaration::where('id_controleur', $controleur->id_controleur)
            ->with('produits')
            ->get();
        return view('controleur.dashboard', compact('demandes'));
    }

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

    // Scan/saisie des dates et calcul validité produit
    public function scanProduit(Request $request, $id_produit)
    {
        $produit = Produit::findOrFail($id_produit);
        $validite = null;
        if ($request->isMethod('post')) {
            $request->validate([
                'date_fabrication' => 'required|date',
                'date_expiration' => 'required|date|after:date_fabrication',
            ]);
            $produit->date_fabrication = $request->date_fabrication;
            $produit->date_expiration = $request->date_expiration;
            // Calcul validité
            $diff = now()->diffInMonths(
                \Carbon\Carbon::parse($request->date_expiration),
                false // négatif si déjà expiré
            );
            if ($diff > 3) {
                $produit->statut = 'passable';
                $validite = [
                    'passable' => true,
                    'message' => 'Produit passable (expiration dans ' . $diff . ' mois)'
                ];
            } else {
                $produit->statut = 'non passable';
                $validite = [
                    'passable' => false,
                    'message' => 'Produit non passable (expiration dans ' . $diff . ' mois)'
                ];
            }
            $produit->save();
        } elseif ($produit->date_expiration && $produit->date_fabrication) {
            $diff = now()->diffInMonths(\Carbon\Carbon::parse($produit->date_expiration), false);
            $validite = [
                'passable' => $produit->statut === 'passable',
                'message' => ($produit->statut === 'passable' ? 'Produit passable' : 'Produit non passable') . ' (expiration dans ' . $diff . ' mois)'
            ];
        }
        return view('controleur.produit_show', compact('produit', 'validite'));
    }
}
