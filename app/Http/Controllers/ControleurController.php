<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Controleur;
use App\Models\Declaration;
use App\Models\Produit;
use App\Models\Action;
use Illuminate\Support\Facades\Auth;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Str;

class ControleurController extends Controller
{
    // Dashboard Contrôleur
    public function dashboard()
    {
        $controleur = Auth::user();
        // Afficher uniquement les demandes assignées au contrôleur connecté
        $demandes = Declaration::where('user_id_controleur', $controleur->id)
            ->with(['produits', 'client'])
            ->latest()
            ->get();
        return view('controleur.dashboard', compact('demandes'));
    }

    // Nouvelle méthode pour afficher le tableau de bord avec les déclarations assignées
    public function showDashboard()
    {
        $controleur = Auth::user();

        // Charger les déclarations assignées au contrôleur
        $declarations = Declaration::where('user_id_controleur', $controleur->id)
            ->with('produits')
            ->latest()
            ->get();

        // Retourner directement à la vue du tableau de bord avec les déclarations
        return view('controleur.dashboard', [
            'controleur' => $controleur,
            'declarations' => $declarations,
        ]);
    }

    // Liste des demandes assignées au contrôleur
    public function demandesAssignees()
    {
        $controleur = Auth::user();
        $demandes = Declaration::where('user_id_controleur', $controleur->id)->with('produits')->get();
        return view('controleur.demandes', compact('demandes'));
    }


    // Ajouter un produit à une demande
    public function addProduit($demandeId)
    {
        $demande = Declaration::findOrFail($demandeId);
        return view('controleur.produit_add', compact('demande'));
    }

    // Enregistrer le produit ajouté
    public function storeProduit(Request $request, $demandeId)
    {
        $request->validate([
            'designation' => 'required',
            'quantite' => 'required|numeric',
        ]);
        $produit = new Produit();
        $produit->designation = $request->designation;
        $produit->quantite = $request->quantite;
        $produit->id_declaration = $demandeId;
        $produit->save();
        // Log action
        Action::create([
            'user_id' => Auth::id(),
            'user_type' => 'controleur',
            'action' => 'ajout_produit',
            'description' => 'Ajout du produit "' . $produit->designation . '" à la demande ' . $demandeId,
        ]);
        return redirect()->route('controleur.demande.show', $demandeId)->with('success', 'Produit ajouté avec succès.');
    }

    // Page de détail d'une demande (actions sur produits)
    public function showDemande($demandeId)
    {
        $demande = Declaration::with('produits')->findOrFail($demandeId);
        $produit = $demande->produits->first();
        $validite = null;
        // Si aucun produit n'est lié à la demande, on évite l'erreur
        if (!$produit) {
            return view('controleur.demande_show', ['produit' => null, 'validite' => null]);
        }
        if ($produit->date_expiration && $produit->date_fabrication) {
            $diff = now()->diffInMonths(\Carbon\Carbon::parse($produit->date_expiration), false);
            $validite = [
                'passable' => $produit->statut === 'passable',
                'message' => ($produit->statut === 'passable' ? 'Produit passable' : 'Produit non passable') . ' (expiration dans ' . $diff . ' mois)'
            ];
        }
        return view('controleur.demande_show', compact('produit', 'validite'));
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
        // Log action
        Action::create([
            'user_id' => Auth::id(),
            'user_type' => 'controleur',
            'action' => 'ajout_photo',
            'description' => 'Ajout d\'une photo au produit ID ' . $produitId,
        ]);
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
        // Log action
        Action::create([
            'user_id' => Auth::id(),
            'user_type' => 'controleur',
            'action' => 'ajout_commentaire',
            'description' => 'Ajout d\'un commentaire au produit ID ' . $produitId,
        ]);
        return back()->with('success', 'Commentaire ajouté.');
    }

    // Valider un produit
    public function validerProduit($produitId)
    {
        $produit = Produit::findOrFail($produitId);
        $produit->statut = 'valide';
        $produit->save();
        // Log action
        Action::create([
            'user_id' => Auth::id(),
            'user_type' => 'controleur',
            'action' => 'valider_produit',
            'description' => 'Validation du produit ID ' . $produitId,
        ]);
        // Log action pour le chef de service
        \App\Models\Action::create([
            'user_id' => Auth::id(),
            'user_type' => 'controleur',
            'action' => 'validation_produit',
            'description' => 'Produit validé par le contrôleur ID ' . Auth::id() . ' (Produit ID ' . $produitId . ')',
        ]);
        return back()->with('success', 'Produit validé.');
    }

    // Rejeter un produit
    public function rejeterProduit($produitId)
    {
        $produit = Produit::findOrFail($produitId);
        $produit->statut = 'rejete';
        $produit->save();
        // Log action
        Action::create([
            'user_id' => Auth::id(),
            'user_type' => 'controleur',
            'action' => 'rejeter_produit',
            'description' => 'Rejet du produit ID ' . $produitId,
        ]);
        // Log action pour le chef de service
        \App\Models\Action::create([
            'user_id' => Auth::id(),
            'user_type' => 'controleur',
            'action' => 'rejet_produit',
            'description' => 'Produit rejeté par le contrôleur ID ' . Auth::id() . ' (Produit ID ' . $produitId . ')',
        ]);
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
            // Log action: mise à jour des dates et statut par le contrôleur
            Action::create([
                'user_id' => Auth::id(),
                'user_type' => 'controleur',
                'action' => 'maj_dates_produit',
                'description' => 'Mise à jour des dates et du statut pour le produit ID ' . $produit->id_produit,
            ]);
        } elseif ($produit->date_expiration && $produit->date_fabrication) {
            $diff = now()->diffInMonths(\Carbon\Carbon::parse($produit->date_expiration), false);
            $validite = [
                'passable' => $produit->statut === 'passable',
                'message' => ($produit->statut === 'passable' ? 'Produit passable' : 'Produit non passable') . ' (expiration dans ' . $diff . ' mois)'
            ];
        }
        return view('controleur.produit_show', compact('produit', 'validite'));
    }

    // Assigner automatiquement les demandes depuis les notifications
    public function assignerDemandesDepuisNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->where('type', 'App\Notifications\DeclarationSubmitted')->get();
        foreach ($notifications as $notif) {
            $data = $notif->data;
            if (isset($data['declaration_id'])) {
                Declaration::where('id_declaration', $data['declaration_id'])
                    ->update(['user_id_controleur' => $user->id]);
            }
        }
        return redirect()->back()->with('success', 'Toutes les demandes des notifications ont été assignées à ce contrôleur.');
    }

    // Afficher les détails d'une déclaration
    public function showDeclaration($id)
    {
        // Récupérer les détails de la déclaration
        $declaration = \App\Models\Declaration::with('produit')->findOrFail($id);

        // Passer les données à la vue
        return view('controleur.declaration-detail', compact('declaration'));
    }


    // Extraction OCR des dates depuis une photo
    public function extractDates(Request $request)
    {
        if (!$request->hasFile('photo') || !$request->has('produit_id')) {
            return response()->json(['error' => 'Aucune image ou produit spécifié.'], 400);
        }
        $photo = $request->file('photo');
        $produitId = $request->input('produit_id');
        $produit = Produit::find($produitId);
        if (!$produit) {
            return response()->json(['error' => 'Produit introuvable.'], 404);
        }
        $path = $photo->storeAs('ocr_temp', uniqid().'.'.$photo->getClientOriginalExtension(), 'public');
        $fullPath = storage_path('app/public/'.$path);

        // Lancer l'OCR
        $ocr = new TesseractOCR($fullPath);
        $text = $ocr->run();

        // Extraction des dates (formats courants)
        $dateFabrication = null;
        $dateExpiration = null;
        if (preg_match_all('/(\d{2}[\/\-]\d{2}[\/\-]\d{4})/', $text, $matches)) {
            $dateFabrication = $matches[1][0] ?? null;
            $dateExpiration = $matches[1][1] ?? null;
        }
        // Nettoyage du fichier temporaire
        @unlink($fullPath);

        // Mettre à jour le modèle Produit
        $produit->date_fabrication = $dateFabrication;
        $produit->date_expiration = $dateExpiration;
        $produit->save();

        return response()->json([
            'date_fabrication' => $dateFabrication,
            'date_expiration' => $dateExpiration,
        ]);
    }
    
    /**
     * Afficher les notifications du contrôleur
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('controleur.notifications', [
            'notifications' => $notifications
        ]);
    }
}
