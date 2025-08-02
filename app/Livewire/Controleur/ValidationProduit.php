<?php
namespace App\Livewire\Controleur;

use Livewire\Component;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;

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
            session()->flash('success', 'Produit validé avec succès.');
        }
    }

    public function rejeter()
    {
        $produit = Produit::where('id_produit', $this->produitId)->first();
        if ($produit) {
            $produit->statut = 'rejeté';
            $produit->save();
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
            $this->statutAuto = $this->moisRestants > 3 ? 'passable' : 'non passable';
        } else {
            $this->moisRestants = null;
            $this->statutAuto = null;
        }
    }

    public function render()
    {
        $produit = null;
        if ($this->produitId) {
            $produit = Produit::where('id_produit', $this->produitId)->first();
            if ($produit) {
                $this->calculerStatut($produit);
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
        ]);
    }
}
