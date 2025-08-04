<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ClientDeclarationSubmitted extends Notification
{
    use Queueable;

    private $declarationData;

    public function __construct($declarationData)
    {
        $this->declarationData = $declarationData;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toArray($notifiable)
    {
        return $this->declarationData;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Déclaration soumise avec succès')
            ->line('Votre déclaration a été soumise avec succès.')
            ->line('Veuillez revenir dans 2 jours pour le contrôle.')
            ->action('Voir votre tableau de bord', url('/client/dashboard'))
            ->line('Merci d’utiliser notre plateforme !');
    }
}
