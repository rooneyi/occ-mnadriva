<?php
namespace App\Livewire\Client;

use App\Models\Declaration;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class DeclarationForm extends Component
{
    use WithFileUploads;

    public $produits = [];
    public $selectedProduits = [];
    public $unite;
    public $numero_import;
    public $document;

    public function submit()
    {
        $this->validate([
            'selectedProduits' => 'required|array',
            'unite' => 'required|string',
            'numero_import' => 'required|string',
            'document' => 'nullable|file',
        ]);
        $client = Auth::user();
        $declaration = Declaration::create([
            'id_client' => $client->id_client,
            'unite' => $this->unite,
            'numero_import' => $this->numero_import,
            'date_soumission' => now(),
            'statut' => 'en_attente',
        ]);
        $declaration->produits()->sync($this->selectedProduits);
        if ($this->document) {
            $path = $this->document->store('documents');
            $declaration->joindreDocument($path);
        }
        session()->flash('success', 'Déclaration soumise avec succès.');
        return redirect()->route('client.dashboard');
    }

    public function mount()
    {
        $this->produits = Produit::all();
    }

    public function render()
    {
        return view('livewire.client.declaration-form');
    }
}

