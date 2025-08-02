<?php
namespace App\Livewire\Controleur;

use Livewire\Component;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;

class ValidationProduit extends Component
{
    public $produitId;
    public $statut;
    public $commentaire;
    public $moisRestants;
    public $statutAuto;

    public function valider()
    {
        $produit = Produit::find($this->produitId);
        if ($produit) {
            $produit->statut = 'valide';
            $produit->save();
            session()->flash('success', 'Produit validé avec succès.');
        }
    }

    public function rejeter()
    {
        $produit = Produit::find($this->produitId);
        if ($produit) {
            $produit->statut = 'rejeté';
            $produit->save();
            session()->flash('error', 'Produit rejeté.');
        }
    }

    public function updatedProduitId()
    {
        $produit = Produit::find($this->produitId);
        if ($produit) {
            $this->calculerStatut($produit);
        } else {
            $this->moisRestants = null;
            $this->statutAuto = null;
        }
    }

    public function calculerStatut($produit)
    {
        if ($produit->date_expiration) {
            $dateExp = \Carbon\Carbon::parse($produit->date_expiration);
            $dateNow = \Carbon\Carbon::now();
            $this->moisRestants = $dateNow->diffInMonths($dateExp, false);
            $this->statutAuto = $this->moisRestants > 3 ? 'passable' : 'non passable';
        } else {
            $this->moisRestants = null;
            $this->statutAuto = null;
        }
    }

    public function render()
    {
        $produit = $this->produitId ? Produit::find($this->produitId) : null;
        return view('livewire.controleur.validation-produit', [
            'produit' => $produit,
            'moisRestants' => $this->moisRestants,
            'statutAuto' => $this->statutAuto,
        ]);
    }
}
