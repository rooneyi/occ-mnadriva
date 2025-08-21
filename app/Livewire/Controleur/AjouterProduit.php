<?php
namespace App\Livewire\Controleur;

use Livewire\Component;
use App\Models\Produit;
use App\Models\Action;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use App\Models\ProduitPhoto;
use App\Services\OcrService;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class AjouterProduit extends Component
{
    use WithFileUploads;
    public $categorie_produit;
    public $nom_produit;
    public $description_produit;
    public $date_fabrication;
    public $date_expiration;
    public $produits;
    public $photo; // photo du produit (upload ou capture mobile)
    public $extractedText = '';
    public $detectedDate = '';

    protected $rules = [
        'categorie_produit' => 'required|string',
        'nom_produit' => 'required|string',
        'description_produit' => 'required|string',
        'date_fabrication' => 'required|date',
        'date_expiration' => 'required|date|after_or_equal:date_fabrication',
        'photo' => 'nullable|image|max:5120', // 5MB max
    ];

    public function mount()
    {
        $this->refreshProduits();
    }

    public function refreshProduits()
    {
        $this->produits = Produit::orderBy('id_produit', 'desc')->get();
    }

    #[On('photo-uploaded')]
    public function processPhoto($tempPath)
    {
        $ocrService = new OcrService();
        $this->extractedText = $ocrService->extractTextFromImage(storage_path('app/livewire-tmp/' . basename($tempPath)));
        
        if ($this->extractedText) {
            $this->detectedDate = $ocrService->extractDateFromText($this->extractedText);
            if ($this->detectedDate) {
                // Essayer de formater la date pour l'affichage
                try {
                    $date = \Carbon\Carbon::parse($this->detectedDate);
                    $this->date_fabrication = $date->format('Y-m-d');
                    $this->date_expiration = $date->addYear()->format('Y-m-d');
                } catch (\Exception $e) {
                    // La date n'a pas pu être parsée
                    \Log::error('Erreur de parsing de date: ' . $e->getMessage());
                }
            }
        }
    }

    public function updatedPhoto()
    {
        $this->validateOnly('photo');
        if ($this->photo) {
            $path = $this->photo->store('temp', 'local');
            $this->dispatch('photo-uploaded', tempPath: $path);
        }
    }

    public function ajouter()
    {
        $this->validate();
        $produit = Produit::create([
            'categorie_produit' => $this->categorie_produit,
            'nom_produit' => $this->nom_produit,
            'description' => $this->description_produit,
            'date_fabrication' => $this->date_fabrication,
            'date_expiration' => $this->date_expiration,
        ]);
        // Log action controleur
        Action::create([
            'user_id' => Auth::id(),
            'user_type' => 'controleur',
            'action' => 'ajout_produit',
            'description' => 'Ajout du produit "'.$produit->nom_produit.'" (ID '.$produit->id_produit.')',
        ]);
        // Sauvegarder la photo si fournie
        if ($this->photo) {
            $path = $this->photo->store('photos-produits', 'public');
            ProduitPhoto::create([
                'produit_id' => $produit->id_produit,
                'chemin_photo' => $path,
            ]);
            // Log action pour la photo
            Action::create([
                'user_id' => Auth::id(),
                'user_type' => 'controleur',
                'action' => 'ajout_photos',
                'description' => '1 photo ajoutée au produit ID '.$produit->id_produit,
            ]);
        }
        $this->reset(['categorie_produit', 'nom_produit', 'description_produit', 'date_fabrication', 'date_expiration', 'photo']);
        $this->refreshProduits();
        session()->flash('success', 'Produit ajouté avec succès.');
    }

    public function render()
    {
        return view('livewire.controleur.ajouter-produit');
    }
}
