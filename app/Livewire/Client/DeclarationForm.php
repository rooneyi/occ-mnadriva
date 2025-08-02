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

    public $designation_produit;
    public $quantiter;
    public $unite;
    public $numero_impot;
    public $date_soumission;
    public $fichier;


    public function submit()
    {
        $this->validate([
            'designation_produit' => 'required|string',
            'quantiter' => 'required|integer|min:1',
            'unite' => 'required|string',
            'numero_impot' => 'required|string',
            'fichier' => 'nullable|file',
        ]);
        $user = Auth::user();
        $fichierPath = null;
        if ($this->fichier) {
            $fichierPath = $this->fichier->store('declarations');
        }
        if (!$user || !$user->id) {
            session()->flash('error', 'Vous devez être connecté pour soumettre une déclaration.');
            return;
        }
        $declaration = Declaration::create([
            'user_id' => $user->id,
            'designation_produit' => $this->designation_produit,
            'quantiter' => $this->quantiter,
            'unite' => $this->unite,
            'numero_impot' => $this->numero_impot,
            'date_soumission' => now(),
            'fichier' => $fichierPath,
            'statut' => 'en_attente',
        ]);

        // Notifier le contrôleur
        $controleur = \App\Models\User::where('role', 'controleur')->first();
        if ($controleur) {
            $controleur->notify(new \App\Notifications\DeclarationSubmitted($declaration));
        }
        // Notifier l'utilisateur (client)
        if ($user) {
            $user->notify(new \App\Notifications\DeclarationSubmitted($declaration));
        }

        $dateReception = now()->locale('fr_FR')->isoFormat('dddd D MMMM YYYY');
        $dateConvocation = now()->addDay()->locale('fr_FR')->isoFormat('dddd D MMMM YYYY');
        $message = "Votre demande a bien été reçue, le $dateReception. Veuillez vous présenter pour le contrôle à cette date $dateConvocation.";
        session()->flash('success', $message);
        return redirect()->route('client.dashboard');
    }

    public $produits = [];

    public function mount()
    {
        $this->date_soumission = now()->toDateString();
        $this->produits = Produit::all();
    }

    public function render()
    {
        return view('livewire.client.declaration-form', [
            'produits' => $this->produits
        ]);
    }
}
