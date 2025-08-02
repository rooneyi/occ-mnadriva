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
        $isControleur = $notifiable->role === 'controleur';
        if ($isControleur) {
            return [
                'title' => 'Nouvelle déclaration à traiter',
                'message' => 'Un client a soumis une nouvelle déclaration pour le produit ' . $this->declaration->designation_produit . ' (quantité : ' . $this->declaration->quantiter . ')',
                'declaration_id' => $this->declaration->id,
            ];
        } else {
            return [
                'title' => 'Déclaration soumise',
                'message' => 'Votre déclaration pour le produit ' . $this->declaration->designation_produit . ' (quantité : ' . $this->declaration->quantiter . ') a bien été transmise au contrôleur.',
                'declaration_id' => $this->declaration->id,
            ];
        }
    }
}
