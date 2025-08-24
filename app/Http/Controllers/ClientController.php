<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Declaration;
use App\Models\RapportAnalyse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Services\NotificationService;

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

        $client = Auth::user();
        if (!$client) {
            return redirect()->route('client.login')
                ->with('error', 'Veuillez vous connecter pour accéder à votre tableau de bord.');
        }

        $produitIds = array_filter($request->produits, function ($id) {
            return is_numeric($id);
        });
        if (empty($produitIds)) {
            return back()->withInput()->with('error', "Aucun produit valide sélectionné.");
        }

        // Vérifier si le client a déjà déclaré ces produits
        $dejaDeclare = Declaration::where('user_id', $client->id)
            ->whereHas('produits', function($q) use ($produitIds) {
                $q->whereIn('produits.id_produit', $produitIds);
            })->exists();
        if ($dejaDeclare) {
            return back()->withInput()->with('error', "Vous avez déjà déclaré ce(s) produit(s). Veuillez choisir un autre produit.");
        }

        $controleur = \App\Models\User::where('role', 'controleur')->first();
        if (!$controleur) {
            return back()->withInput()->with('error', "Aucun contrôleur n'est disponible pour l'assignation.");
        }

        $declaration = Declaration::create([
            'id_client'       => $client->id_client ?? $client->id,
            'id_declaration'  => $request->id_declaration,
            'unite'           => $request->unite,
            'numero_import'   => $request->numero_import,
            'date_soumission' => now(),
            'statut'          => 'en_attente',
            'user_id'         => Auth::id(),
            'user_id_controleur' => $controleur->id,
        ]);
        if (!$declaration) {
            return back()->withInput()->with('error', "Erreur lors de la création de la déclaration.");
        }

        $declaration->produits()->sync($produitIds);
        $produits = $declaration->produits;
        NotificationService::notifyDeclarationSubmitted($declaration, $produits);

        $dossier = \App\Models\Dossier::create([
            'nom_dossier' => 'Dossier de ' . ($client->name ?? $client->nom ?? 'Client') . ' - ' . now()->format('d/m/Y H:i'),
            'statut' => 'en_attente',
        ]);
        if (!$dossier) {
            return back()->withInput()->with('error', "Erreur lors de la création du dossier.");
        }
        $dossier->declarations()->attach($declaration->id_declaration);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents');
            $declaration->joindreDocument($path);
        }

        $declaration->load('produits');
        $produitsNotif = $declaration->produits->map(function($produit) {
            return [
                'nom_produit' => $produit->nom_produit,
                'quantite' => $produit->quantite,
            ];
        })->toArray();

        Notification::sendNow($client, new \App\Notifications\ClientDeclarationSubmitted([
            'id_declaration' => $declaration->id_declaration,
            'statut' => $declaration->statut,
            'produits' => $produitsNotif,
            'date_soumission' => $declaration->date_soumission,
        ]));

        return redirect()->route('client.dashboard')->with('success', 'Déclaration soumise avec succès.');
    }
}
