<?php
namespace App\Livewire\Client;

use App\Models\Declaration;
use App\Models\Produit;
use App\Models\Action;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class DeclarationForm extends Component
{
    use WithFileUploads;

    public $id_declaration;
    public $unite;
    public $numero_impot;
    public $date_soumission;
    public $fichier;
    public $id_controleur;
    public $controleurs = [];
    public array $selectedProduits = [];
    public array $quantites = [];


    public function submit()
    {
        // Validation de base
        $this->validate([
            'selectedProduits' => 'required|array|min:1',
            'unite' => 'required|string',
            'numero_impot' => 'required|string',
            'fichier' => 'nullable|file',
            'id_controleur' => 'required|integer|exists:users,id',
            'quantites' => 'required|array',
            'quantites.*' => 'nullable|numeric|min:1',
        ]);

        $user = Auth::user();
        if (!$user || !$user->id) {
            session()->flash('error', 'Vous devez être connecté pour soumettre une déclaration.');
            return;
        }

        // Normaliser les IDs sélectionnés et construire les données pivot AVANT toute création
        

        $pivotData = [];
        $missing = [];
        // Nettoyer la liste (retirer valeurs vides/null) et normaliser en chaînes numériques
        $produitsSelectionnes = array_values(array_filter((array) $this->selectedProduits, function ($id) {
            return $id !== null && $id !== '';
        }));

        foreach ($produitsSelectionnes as $produitId) {
            // Accéder indifféremment via clé string ou int
            $quantite = $this->quantites[$produitId] ?? ($this->quantites[(int) $produitId] ?? null);
            \Log::info('DEBUG produit', ['produitId' => $produitId, 'quantite' => $quantite]);
            if (is_numeric($quantite) && (int) $quantite > 0) {
                $pivotData[$produitId] = ['quantite' => (int) $quantite];
            } else {
                $missing[] = $produitId;
            }
        }

        if (!empty($missing)) {
            // Ajouter une erreur ciblée sur les quantités
            $this->addError('quantites', 'Veuillez saisir une quantité pour chaque produit sélectionné.');
            return;
        }

        // Upload du fichier uniquement après validation réussie
        $fichierPath = null;
        if ($this->fichier) {
            $fichierPath = $this->fichier->store('declarations');
        }

        // Créer la déclaration seulement maintenant
        $declaration = Declaration::create([
            'user_id' => $user->id,
            'unite' => $this->unite,
            'numero_impot' => $this->numero_impot,
            'date_soumission' => now(),
            'statut' => 'en_attente',
            // Conserver id_controleur si utilisé ailleurs, mais surtout remplir user_id_controleur
            'id_controleur' => $this->id_controleur,
            'user_id_controleur' => $this->id_controleur,
            'fichier' => $fichierPath,
        ]);

        // Associer les produits avec leurs quantités
        $declaration->produits()->sync($pivotData);

        // Journaliser l'action pour visibilité Chef de service
        Action::create([
            'user_id' => $user->id,
            'user_type' => 'client',
            'action' => 'soumission_declaration',
            'description' => 'Déclaration #'.$declaration->id_declaration.' soumise avec '.count($pivotData).' produit(s).',
        ]);

        session()->flash('success', 'Déclaration soumise avec succès.');
        $this->reset(['selectedProduits', 'quantites', 'unite', 'numero_impot', 'fichier', 'id_controleur']);
        return redirect()->route('client.dashboard');
    }

    public $produits = [];

    public function mount()
    {
        $this->date_soumission = now()->toDateString();
        $this->produits = Produit::all();
        // Récupère tous les utilisateurs ayant le rôle 'controleur'
        $this->controleurs = \App\Models\User::where('role', 'controleur')->get();
    }

    public function render()
    {
        return view('livewire.client.declaration-form', [
            'produits' => $this->produits,
            'controleurs' => $this->controleurs
        ]);
    }
}
