<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ControleurDeclarationNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        // Ajoutez des données si nécessaire
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle déclaration soumise')
            ->line('Une nouvelle déclaration a été soumise par un client.')
            ->action('Voir les déclarations', url('/controleur/dashboard'))
            ->line('Merci de vérifier les détails dès que possible.');
    }
}
