<?php
namespace App\Livewire\Controleur;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Produit;
use App\Models\ProduitPhoto;

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
            $path = $photo->store('photos-produits', 'public');
            ProduitPhoto::create([
                'produit_id' => $this->produitId,
                'chemin_photo' => $path,
            ]);
        }
        session()->flash('success', 'Photos enregistrées avec succès.');
    }

    public function render()
    {
        $photos = [];
        if ($this->produitId) {
            $photos = \App\Models\ProduitPhoto::where('produit_id', $this->produitId)->get();
        }
        return view('livewire.controleur.photos-produit', [
            'produits' => $this->produits,
            'produitId' => $this->produitId,
            'photos' => $photos,
        ]);
    }
}
