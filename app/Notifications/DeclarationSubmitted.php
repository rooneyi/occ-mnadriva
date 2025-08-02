<?php
namespace App\Notifications;

use App\Models\Declaration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeclarationSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public $declaration;

    public function __construct(Declaration $declaration)
    {
        $this->declaration = $declaration;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $isControleur = $notifiable->role === 'controleur';
        $message = (new MailMessage)
            ->subject('Nouvelle déclaration soumise');
        if ($isControleur) {
            $message->greeting('Bonjour Contrôleur !')
                ->line('Une nouvelle déclaration a été soumise par un client.')
                ->line('Produit : ' . $this->declaration->designation_produit)
                ->line('Quantité : ' . $this->declaration->quantiter)
                ->action('Voir les demandes', url('/controleur/demandes'));
        } else {
            $message->greeting('Bonjour !')
                ->line('Votre déclaration a été soumise avec succès.')
                ->line('Produit : ' . $this->declaration->designation_produit)
                ->line('Quantité : ' . $this->declaration->quantiter)
                ->action('Voir mes déclarations', url('/client/mes-declarations'));
        }
        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            'declaration_id' => $this->declaration->id_declaration,
            'designation_produit' => $this->declaration->designation_produit,
            'quantiter' => $this->declaration->quantiter,
            'statut' => $this->declaration->statut,
        ];
    }
}
