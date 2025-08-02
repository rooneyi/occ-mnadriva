<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laborantin;
use App\Models\RapportAnalyse;
use App\Models\Declaration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaborantinController extends Controller
{
    // Affiche le formulaire de saisie des résultats d'analyse
    public function showAnalyseForm()
    {
        $laborantin = Auth::user();
        $declarations = \App\Models\Declaration::with('produits', 'client')->get();
        $produits = \App\Models\Produit::all();
        return view('laborantin.analyse-form', compact('declarations', 'produits'));
    }

    // Enregistre et génère le rapport d'analyse, puis le soumet au contrôleur
    public function storeRapport(Request $request)
    {
        $request->validate([
            'id_declaration' => 'required|integer',
            'designation_produit' => 'required|string',
            'quantite' => 'required|numeric',
            'methode_essai' => 'required|string',
            'aspect_exterieur' => 'required|string',
            'resultat_analyse' => 'required|string',
            'date_fabrication' => 'required|date',
            'date_expiration' => 'required|date',
            'conclusion' => 'required|string',
        ]);
        $laborantin = Auth::user();
        $rapport = \App\Models\RapportAnalyse::create([
            'id_laborantin' => $laborantin->id_laborantin,
            'id_declaration' => $request->id_declaration,
            'designation_produit' => $request->designation_produit,
            'quantite' => $request->quantite,
            'methode_essai' => $request->methode_essai,
            'aspect_exterieur' => $request->aspect_exterieur,
            'resultat_analyse' => $request->resultat_analyse,
            'date_fabrication' => $request->date_fabrication,
            'date_expiration' => $request->date_expiration,
            'conclusion' => $request->conclusion,
        ]);
        // Génération automatique du rapport PDF (optionnel)
        // Notifier le contrôleur
        $controleur = \App\Models\User::where('role', 'controleur')->first();
        if ($controleur) {
            $controleur->notify(new \App\Notifications\AnalyseSubmitted($rapport));
        }
        return redirect()->route('laborantin.historique')->with('success', 'Rapport généré et soumis au contrôleur.');
    }

    // Historique des analyses
    public function historique()
    {
        $laborantin = Auth::user();
        $analyses = \App\Models\RapportAnalyse::where('id_laborantin', $laborantin->id_laborantin)->latest()->get();
        return view('laborantin.historique', compact('analyses'));
    }

    // Tableau de bord laborantin
    public function dashboard()
    {
        $laborantin = Auth::user();
        // Exemple : récupérer les déclarations à analyser (à adapter selon ta logique métier)
        $analyses = Declaration::where('statut', 'en_attente')
            ->where('id_laborantin', $laborantin->id_laborantin)
            ->with('produit')
            ->get();
        return view('laborantin.dashboard', compact('analyses'));
    }
}
