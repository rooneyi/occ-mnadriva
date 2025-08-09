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
        $produits = collect();
        if ($declarations->count() > 0) {
            // On récupère tous les produits liés aux déclarations sélectionnées (évite doublons)
            $produits = $declarations->flatMap->produits->unique('id_produit');
        }
        return view('laborantin.analyse-form', compact('declarations', 'produits'));
    }

    // Enregistre et génère le rapport d'analyse, puis le soumet au contrôleur
    public function storeRapport(Request $request)
    {
        $request->validate([
            'id_declaration' => 'required|integer|exists:declarations,id_declaration',
            'designation_produit' => 'required|string',
            'code_lab' => 'nullable|string|max:255',
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
            'id_declaration' => $request->id_declaration,
            'id_laborantin' => $laborantin->id_laborantin ?? $laborantin->id,
            'designation_produit' => $request->designation_produit,
            'quantite' => $request->quantite,
            'code_lab' => $request->code_lab ?? 'LAB-'.date('YmdHis'),
            'methode_essai' => $request->methode_essai,
            'aspect_exterieur' => $request->aspect_exterieur,
            'resultat_analyse' => $request->resultat_analyse,
            'date_fabrication' => $request->date_fabrication,
            'date_expiration' => $request->date_expiration,
            'conclusion' => $request->conclusion,
        ]);
        // Génération automatique du rapport PDF (optionnel)
        // Notifier le contrôleur pour validation
        $controleur = \App\Models\User::where('role', 'controleur')->first();
        if ($controleur) {
            $controleur->notify(new \App\Notifications\AnalyseSubmitted($rapport));
        }
        return redirect()->route('laborantin.historique')->with('success', 'Rapport généré pour le produit ' . ($produit->nom_produit ?? '') . ' et soumis au contrôleur.');
    }

    // Historique des analyses
    public function historique()
    {
        $laborantin = Auth::user();
        $analyses = \App\Models\RapportAnalyse::where('id_laborantin', $laborantin->id)->latest()->get();
        return view('laborantin.historique', compact('analyses'));
    }

    // Tableau de bord laborantin
    public function dashboard()
    {
        $laborantin = Auth::user();
        $analyses = \App\Models\RapportAnalyse::where('id_laborantin', $laborantin->id)->latest()->get();
        // Récupérer uniquement les produits liés à des déclarations (plus de filtre sur id_laborantin)
        $produits = \App\Models\Produit::whereIn('id_produit', function($query) {
            $query->select('id_produit')
                ->from('declaration_produit');
        })->get();
        return view('laborantin.dashboard', compact('analyses', 'produits'));
    }

    // Génère automatiquement un rapport d'analyse à partir de la dernière déclaration du laborantin
    public function genererRapportAuto(Request $request)
    {
        $laborantin = Auth::user();
        $produitId = $request->id_produit;
        $produit = \App\Models\Produit::find($produitId);
        if (!$produit) {
            return redirect()->back()->with('error', 'Aucun produit trouvé pour générer le rapport.');
        }
        // Ici, on ne cherche plus la déclaration, on génère le rapport uniquement avec le produit sélectionné
        $rapport = \App\Models\RapportAnalyse::create([
            'id_laborantin' => $laborantin->id,
            'designation_produit' => $produit->nom_produit ?? $produit->designation ?? $produit->designation_produit ?? 'Produit',
            'code_lab' => 'AUTO-'.date('YmdHis'),
            'quantite' => 1,
            'methode_essai' => 'Automatique',
            'aspect_exterieur' => 'Automatique',
            'resultat_analyse' => 'Automatique',
            'date_fabrication' => $produit->date_fabrication ?? now(),
            'date_expiration' => $produit->date_expiration ?? now()->addMonths(6),
            'conclusion' => 'Généré automatiquement',
        ]);
        $submitted = [
            'id_produit' => $produit->id_produit,
            'nom_produit' => $produit->nom_produit ?? $produit->designation ?? $produit->designation_produit ?? null,
            'date_fabrication' => $produit->date_fabrication ?? '',
            'date_expiration' => $produit->date_expiration ?? '',
        ];
        return redirect()->back()->with(['success' => 'Rapport généré automatiquement avec succès.', 'submitted' => $submitted]);
    }
}
