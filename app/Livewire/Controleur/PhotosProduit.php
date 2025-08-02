<?php
namespace App\Livewire\Controleur;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Produit;

class PhotosProduit extends Component
{
    use WithFileUploads;

    public $produitId;
    public $photos = [];

    public function save()
    {
        foreach ($this->photos as $photo) {
            $photo->store('photos-produits', 'public');
        }
        session()->flash('success', 'Photos enregistrées avec succès.');
    }

    public function render()
    {
        return view('livewire.controleur.photos-produit');
    }
}

