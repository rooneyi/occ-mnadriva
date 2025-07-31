<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laborantin;
use App\Models\RapportAnalyse;
use App\Models\Declaration;
use Illuminate\Support\Facades\Auth;

class LaborantinController extends Controller
{
    // Affiche le formulaire de saisie des résultats d'analyse
    public function showAnalyseForm($declarationId)
    {
        $declaration = Declaration::findOrFail($declarationId);
        return view('laborantin.analyse-form', compact('declaration'));
    }

    // Remplit et génère un rapport d'analyse
    public function submitAnalyse(Request $request, $declarationId)
    {
        $request->validate([
            'code_lab' => 'required',
            'designation_produit' => 'required',
            'quantite' => 'required|numeric',
            'methode_essai' => 'required',
            'aspect_exterieur' => 'required',
            'resultat_analyse' => 'required',
            'date_fabrication' => 'required|date',
            'date_expiration' => 'required|date',
            'conclusion' => 'required',
        ]);
        $laborantin = Auth::user();
        $rapport = RapportAnalyse::create([
            'id_laborantin' => $laborantin->id_laborantin,
            'id_declaration' => $declarationId,
            'code_lab' => $request->code_lab,
            'designation_produit' => $request->designation_produit,
            'quantite' => $request->quantite,
            'methode_essai' => $request->methode_essai,
            'aspect_exterieur' => $request->aspect_exterieur,
            'resultat_analyse' => $request->resultat_analyse,
            'date_fabrication' => $request->date_fabrication,
            'date_expiration' => $request->date_expiration,
            'conclusion' => $request->conclusion,
        ]);
        return redirect()->route('laborantin.historique')->with('success', 'Rapport généré et soumis.');
    }

    // Historique des analyses
    public function historique()
    {
        $laborantin = Auth::user();
        $rapports = RapportAnalyse::where('id_laborantin', $laborantin->id_laborantin)->with('declaration')->get();
        return view('laborantin.historique', compact('rapports'));
    }
}

