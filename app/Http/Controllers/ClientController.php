<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Declaration;
use App\Models\RapportAnalyse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Affiche le tableau de bord du client connecté
     */

    public function dashboard()
    {
        // Toujours utiliser le guard 'client' si tu as configuré un guard spécifique
        $client = Auth::user();

        // Protection : rediriger si pas connecté
        if (!$client) {
            return redirect()->route('client.login')
                ->with('error', 'Veuillez vous connecter pour accéder à votre tableau de bord.');
        }
        // Correction ici : utiliser 'user_id' au lieu de 'id_client'
        $declarations = Declaration::where('user_id', $client->id)
            ->with('rapports')
            ->get();

        return view('client.dashboard', compact('client', 'declarations'));
    }

    /**
     * Affiche le formulaire de déclaration
     */
    public function showDeclarationForm()
    {
        $produits = Produit::all();
        return view('client.declaration-form', compact('produits'));
    }

    /**
     * Soumet une nouvelle déclaration
     */
    public function submitDeclaration(Request $request)
    {
        $request->validate([
            'produits'       => 'required|array',
            'unite'          => 'required|string',
            'numero_import'  => 'required|string',
            'document'       => 'nullable|file',
        ]);

        // Utiliser le guard par défaut (web) pour l'authentification client
        $client = Auth::user();

        // Protection : rediriger si pas connecté
        if (!$client) {
            return redirect()->route('client.login')
                ->with('error', 'Veuillez vous connecter pour accéder à votre tableau de bord.');
        }

        // Créer la déclaration
        $controleur = \App\Models\User::where('role', 'controleur')->first();
        $declaration = Declaration::create([
            'id_client'       => $client->id_client,
            'unite'           => $request->unite,
            'numero_import'   => $request->numero_import,
            'date_soumission' => now(),
            'statut'          => 'en_attente',
            'user_id'         => Auth::id(),
            'user_id_controleur' => $controleur ? $controleur->id : null,
        ]);

        // Lier les produits
        $declaration->produits()->sync($request->produits);

        // Joindre le document si fourni
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents');
            // Vérifie que tu as bien une méthode joindreDocument() dans ton modèle Declaration
            $declaration->joindreDocument($path);
        }

        return redirect()->route('client.dashboard')
            ->with('success', 'Déclaration soumise avec succès.');
    }

    /**
     * Téléchargement du rapport d'analyse
     */
    public function downloadRapport($rapportId)
    {
        $rapport = RapportAnalyse::findOrFail($rapportId);

        // Exemple si tu stockes le fichier sur le disque
        if ($rapport->fichier && Storage::exists($rapport->fichier)) {
            return Storage::download($rapport->fichier);
        }

        // Sinon, retourne juste les infos
        return response()->json($rapport);
    }
}
