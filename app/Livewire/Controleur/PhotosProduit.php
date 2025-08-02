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
    public $produits;

    public function mount()
    {
        $this->produits = Produit::all();
        if (!$this->produitId && $this->produits->count()) {
            $this->produitId = $this->produits->first()->id_produit;
        }
    }

    public function save()
    {
        foreach ($this->photos as $photo) {
            $photo->store('photos-produits', 'public');
        }
        session()->flash('success', 'Photos enregistrées avec succès.');
    }

    public function render()
    {
        return view('livewire.controleur.photos-produit', [
            'produits' => $this->produits,
            'produitId' => $this->produitId,
        ]);
    }
}
