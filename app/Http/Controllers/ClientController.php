<?php

namespace App\Http\Controllers;

use App\Models\Produit;
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

        try {
            // Créer la déclaration
            $controleur = \App\Models\User::where('role', 'controleur')->first();
            $declaration = Declaration::create([
                'id_client'       => $client->id_client,
                'id_declaration' => $request->id_declaration,
                'unite'           => $request->unite,
                'numero_import'   => $request->numero_import,
                'date_soumission' => now(),
                'statut'          => 'en_attente',
                'user_id'         => Auth::id(),
                'user_id_controleur' => $controleur ? $controleur->id : null,
            ]);
            if (!$declaration) {
                return back()->withInput()->with('error', "Erreur lors de la création de la déclaration.");
            }

            // Lier les produits à la déclaration
            if (!empty($request->produits)) {
                $produitIds = array_filter($request->produits, function ($id) {
                    return is_numeric($id);
                });
                if (empty($produitIds)) {
                    return back()->withInput()->with('error', "Aucun produit valide sélectionné.");
                }
                $declaration->produits()->sync($produitIds);
            } else {
                return back()->withInput()->with('error', "Aucun produit sélectionné.");
            }

            // Créer un dossier et lier la déclaration
            $dossier = \App\Models\Dossier::create([
                'nom_dossier' => 'Dossier de ' . ($client->name ?? $client->nom ?? 'Client') . ' - ' . now()->format('d/m/Y H:i'),
                'statut' => 'en_attente',
            ]);
            if (!$dossier) {
                return back()->withInput()->with('error', "Erreur lors de la création du dossier.");
            }
            $dossier->declarations()->attach($declaration->id_declaration);

            // Joindre le document si fourni
            if ($request->hasFile('document')) {
                $path = $request->file('document')->store('documents');
                $declaration->joindreDocument($path);
            }

            // Charger les produits associés à la déclaration
            $declaration->load('produits');

            // Préparer la liste des produits pour la notification
            $produitsNotif = $declaration->produits->map(function($produit) {
                return [
                    'nom_produit' => $produit->nom_produit,
                    'quantite' => $produit->quantite,
                ];
            })->toArray();

            // Notifier le client avec tous les produits soumis
            $client->notify(new \App\Notifications\ClientDeclarationSubmitted([
                'id_declaration' => $declaration->id_declaration,
                'statut' => $declaration->statut,
                'produits' => $produitsNotif,
            ]));

            // Notifier le contrôleur avec tous les produits
            if ($controleur) {
                $controleur->notify(new \App\Notifications\ControleurDeclarationNotification([
                    'id' => $declaration->id_declaration,
                    'statut' => $declaration->statut,
                    'produits' => $produitsNotif,
                ]));
            }

            // Charger les déclarations associées au client
            $declarations = Declaration::where('user_id', $client->id)
                ->with('produits')
                ->latest()
                ->get();

            // Retourner directement à la vue du tableau de bord avec les déclarations
            return redirect()->route('client.dashboard')->with('success', 'Déclaration soumise avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la soumission de la déclaration : ' . $e->getMessage());
            return back()->withInput()->with('error', "Une erreur s'est produite lors de la soumission de la déclaration.");
        }
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
