<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ControleurDeclarationNotification extends Notification
{
    use Queueable;

    private $declarationDetails;

    public function __construct($declarationDetails)
    {
        $this->declarationDetails = $declarationDetails;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toArray($notifiable)
    {
        return [
            'declaration_id' => $this->declarationDetails['id'],
            'statut' => $this->declarationDetails['statut'],
            'produits' => $this->declarationDetails['produits'] ?? [],
            'url' => route('controleur.declaration.detail', ['id' => $this->declarationDetails['id']]),
        ];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Nouvelle déclaration assignée')
            ->line('Une nouvelle déclaration vous a été assignée.');
        $produits = $this->declarationDetails['produits'] ?? [];
        if (!empty($produits)) {
            $mail->line('Produits déclarés :');
            foreach ($produits as $produit) {
                $mail->line('- ' . ($produit['nom_produit'] ?? 'Produit') . ' | Quantité : ' . ($produit['quantite'] ?? ''));
            }
        }
        $mail->action('Voir les détails', route('controleur.declaration.detail', ['id' => $this->declarationDetails['id']]))
            ->line('Merci de vérifier les détails dès que possible.');
        return $mail;
    }
}
