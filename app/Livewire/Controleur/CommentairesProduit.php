<?php
namespace App\Livewire\Controleur;

use Livewire\Component;
use App\Models\Produit;
use App\Models\Commentaire;
use Illuminate\Support\Facades\Auth;

class CommentairesProduit extends Component
{
    public $produitId;
    public $commentaire;

    public function ajouterCommentaire()
    {
        Commentaire::create([
            'produit_id' => $this->produitId,
            'user_id' => Auth::id(),
            'contenu' => $this->commentaire,
        ]);
        $this->commentaire = '';
        session()->flash('success', 'Commentaire ajouté avec succès.');
    }

    public function render()
    {
        $commentaires = Commentaire::where('produit_id', $this->produitId)->latest()->get();
        return view('livewire.controleur.commentaires-produit', compact('commentaires'));
    }
}

