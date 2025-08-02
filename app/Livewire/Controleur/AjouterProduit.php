<?php
namespace App\Livewire\Controleur;

use Livewire\Component;
use App\Models\Produit;

class AjouterProduit extends Component
{
    public $categorie_produit;
    public $nom_produit;
    public $description_produit;
    public $date_fabrication;
    public $date_expiration;
    public $produits;

    protected $rules = [
        'categorie_produit' => 'required|string',
        'nom_produit' => 'required|string',
        'description_produit' => 'required|string',
        'date_fabrication' => 'required|date',
        'date_expiration' => 'required|date|after_or_equal:date_fabrication',
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
            'description' => $this->description_produit,
            'date_fabrication' => $this->date_fabrication,
            'date_expiration' => $this->date_expiration,
        ]);
        $this->reset(['categorie_produit', 'nom_produit', 'description_produit', 'date_fabrication', 'date_expiration']);
        $this->refreshProduits();
        session()->flash('success', 'Produit ajouté avec succès.');
    }

    public function render()
    {
        return view('livewire.controleur.ajouter-produit');
    }
}
