<?php
namespace App\Livewire;

use App\Models\Produit;
use Livewire\Component;

class ProduitCrud extends Component
{
    public $categorie_produit;
    public $nom_produit;
    public $produits;

    protected $rules = [
        'categorie_produit' => 'required|string',
        'nom_produit' => 'required|string',
    ];

    public function mount()
    {
        $this->refreshProduits();
    }

    public function refreshProduits()
    {
        $this->produits = Produit::orderBy('id_produit', 'desc')->get();
    }

    public function ajouter()
    {
        $this->validate();
        Produit::create([
            'categorie_produit' => $this->categorie_produit,
            'nom_produit' => $this->nom_produit,
        ]);
        $this->reset(['categorie_produit', 'nom_produit']);
        $this->refreshProduits();
        session()->flash('success', 'Produit ajouté avec succès.');
    }

    public function supprimer($id)
    {
        Produit::where('id_produit', $id)->delete();
        $this->refreshProduits();
        session()->flash('success', 'Produit supprimé.');
    }

    public function render()
    {
        return view('livewire.produit-crud');
    }
}
