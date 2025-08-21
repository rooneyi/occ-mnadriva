<?php
namespace App\Livewire\Controleur;

use Livewire\Component;
use App\Models\Produit;
use App\Models\Commentaire;
use Illuminate\Support\Facades\Auth;
use App\Models\Action;

class CommentairesProduit extends Component
{
    public $produitId;
    public $commentaire;
    public $produits;

    public function mount()
    {
        $this->produits = Produit::all();
        if (!$this->produitId && $this->produits->count()) {
            $this->produitId = $this->produits->first()->id_produit;
        }
    }

    public function ajouterCommentaire()
    {
        Commentaire::create([
            'produit_id' => $this->produitId,
            'user_id' => Auth::id(),
            'contenu' => $this->commentaire,
        ]);
        // Log action controleur
        Action::create([
            'user_id' => Auth::id(),
            'user_type' => 'controleur',
            'action' => 'ajout_commentaire',
            'description' => 'Commentaire ajouté sur produit ID '.$this->produitId,
        ]);
        $this->commentaire = '';
        session()->flash('success', 'Commentaire ajouté avec succès.');
    }

    public function render()
    {
        $commentaires = Commentaire::where('produit_id', $this->produitId)->latest()->get();
        return view('livewire.controleur.commentaires-produit', [
            'commentaires' => $commentaires,
            'produits' => $this->produits,
            'produitId' => $this->produitId,
        ]);
    }
}
