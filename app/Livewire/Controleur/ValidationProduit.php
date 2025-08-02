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

    public function render()
    {
        $produit = Produit::find($this->produitId);
        return view('livewire.controleur.validation-produit', compact('produit'));
    }
}

