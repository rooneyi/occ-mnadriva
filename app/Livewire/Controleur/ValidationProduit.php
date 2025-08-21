<?php
namespace App\Livewire\Controleur;

use Livewire\Component;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;
use App\Models\Action;

class ValidationProduit extends Component
{
    public $produitId;
    public $commentaire;
    public $moisRestants;
    public $statutAuto;

    public function mount()
    {
        $this->produitId = null;
        $this->moisRestants = null;
        $this->statutAuto = null;
    }

    public function valider()
    {
        $produit = Produit::where('id_produit', $this->produitId)->first();
        if ($produit) {
            $produit->statut = 'valide';
            $produit->save();
            // Log action controleur
            Action::create([
                'user_id' => Auth::id(),
                'user_type' => 'controleur',
                'action' => 'valider_produit',
                'description' => 'Validation du produit ID '.$produit->id_produit,
            ]);
            session()->flash('success', 'Produit validé avec succès.');
        }
    }

    public function rejeter()
    {
        $produit = Produit::where('id_produit', $this->produitId)->first();
        if ($produit) {
            $produit->statut = 'rejete';
            $produit->save();
            // Log action controleur
            Action::create([
                'user_id' => Auth::id(),
                'user_type' => 'controleur',
                'action' => 'rejeter_produit',
                'description' => 'Rejet du produit ID '.$produit->id_produit,
            ]);
            session()->flash('error', 'Produit rejeté.');
        }
    }

    public function updatedProduitId()
    {
        $produit = Produit::where('id_produit', $this->produitId)->first();
        if ($produit) {
            $this->calculerStatut($produit);
        } else {
            $this->moisRestants = null;
            $this->statutAuto = null;
        }
    }

    public function calculerStatut($produit)
    {
        if ($produit->date_expiration && $produit->date_fabrication) {
            $dateFab = \Carbon\Carbon::parse($produit->date_fabrication);
            $dateExp = \Carbon\Carbon::parse($produit->date_expiration);
            $this->moisRestants = $dateFab->diffInMonths($dateExp, false);
            $this->statutAuto = $this->moisRestants > 2 ? 'passable' : 'non passable';
        } else {
            $this->moisRestants = null;
            $this->statutAuto = null;
        }
    }

    public function render()
    {
        $produit = null;
        $rapportSoumis = false;
        if ($this->produitId) {
            $produit = Produit::where('id_produit', $this->produitId)->first();
            if ($produit) {
                $this->calculerStatut($produit);
                // Vérifier si un rapport d'analyse existe et est soumis pour ce produit
                $rapportSoumis = \App\Models\RapportAnalyse::where('designation_produit', $produit->nom_produit)
                    ->where('statut', 'soumis')
                    ->exists();
            } else {
                $this->moisRestants = null;
                $this->statutAuto = null;
            }
        } else {
            $this->moisRestants = null;
            $this->statutAuto = null;
        }
        return view('livewire.controleur.validation-produit', [
            'produit' => $produit,
            'moisRestants' => $this->moisRestants,
            'statutAuto' => $this->statutAuto,
            'rapportSoumis' => $rapportSoumis,
        ]);
    }
}
