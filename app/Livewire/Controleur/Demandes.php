<?php
namespace App\Livewire\Controleur;

use Livewire\Component;
use App\Models\Declaration;
use Illuminate\Support\Facades\Auth;

class Demandes extends Component
{
    public $demandes;

    public function mount()
    {
        $user = Auth::user();
        // Récupère les demandes assignées au contrôleur connecté
        $this->demandes = Declaration::where('user_id_controleur', $user->id)->get();
    }

    public function render()
    {
        return view('livewire.controleur.demandes');
    }
}

