<?php
namespace App\Notifications;

use App\Models\Declaration;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeclarationSubmitted extends Notification
{
    

    public $declaration;

    public function __construct(Declaration $declaration)
    {
        $this->declaration = $declaration;
    }

    public function via($notifiable)
    {
        // Désactiver l'envoi d'email, conserver uniquement la notification en base
        return ['database'];
    }

    public function toMail($notifiable)
    {
        $isControleur = $notifiable->role === 'controleur';
        $message = (new MailMessage)
            ->subject('Nouvelle déclaration soumise');

        // Charger les produits liés avec la quantité depuis le pivot
        $produits = $this->declaration->produits()->withPivot('quantite')->get();

        if ($isControleur) {
            $message->greeting('Bonjour Contrôleur !')
                ->line('Une nouvelle déclaration a été soumise par un client.');
            if ($produits->isNotEmpty()) {
                $message->line('Produits déclarés :');
                foreach ($produits as $produit) {
                    $message->line('- ' . ($produit->nom_produit ?? 'Produit') . ' | Quantité : ' . ($produit->pivot->quantite ?? ''));
                }
            }
            $message->action('Voir les demandes', url('/controleur/demandes'));
        } else {
            $message->greeting('Bonjour !')
                ->line('Votre déclaration a été soumise avec succès.');
            if ($produits->isNotEmpty()) {
                $message->line('Produits déclarés :');
                foreach ($produits as $produit) {
                    $message->line('- ' . ($produit->nom_produit ?? 'Produit') . ' | Quantité : ' . ($produit->pivot->quantite ?? ''));
                }
            }
            $message->action('Voir mes déclarations', url('/client/mes-declarations'));
        }
        return $message;
    }

    public function toArray($notifiable)
    {
        // Inclure la liste des produits avec quantités dans la notification base de données
        $produits = $this->declaration->produits()->withPivot('quantite')->get()->map(function ($p) {
            return [
                'nom_produit' => $p->nom_produit ?? 'Produit',
                'quantite' => $p->pivot->quantite ?? null,
            ];
        })->toArray();

        return [
            'declaration_id' => $this->declaration->id_declaration,
            'statut' => $this->declaration->statut,
            'produits' => $produits,
            'url' => url('/controleur/demandes'),
        ];
    }
}
