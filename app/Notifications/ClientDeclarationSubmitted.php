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
        // Désactiver l'envoi d'email, conserver uniquement la notification en base
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $produits = $this->declarationData['produits'] ?? [];
        $produitsList = [];
        foreach ($produits as $produit) {
            $produitsList[] = '- ' . ($produit['nom_produit'] ?? 'Produit') . ' | Quantité : ' . ($produit['quantite'] ?? '');
        }
        // Calculer la date de passage (2 jours après la soumission)
        $datePassage = isset($this->declarationData['date_soumission'])
            ? \Carbon\Carbon::parse($this->declarationData['date_soumission'])->addDays(2)->format('d/m/Y')
            : \Carbon\Carbon::now()->addDays(2)->format('d/m/Y');
        return array_merge($this->declarationData, [
            'controle' => true,
            'sujet' => 'Déclaration soumise avec succès',
            'message' => 'Votre déclaration a été soumise avec succès. Veuillez revenir le ' . $datePassage . ' pour le contrôle.',
            'date_passage' => $datePassage,
            'produits_list' => $produitsList,
            'dashboard_url' => url('/client/dashboard'),
        ]);
    }

    public function toMail($notifiable)
    {
        // --- Le contenu du mail est aussi stocké dans la base via toDatabase() ---
    
        $produits = $this->declarationData['produits'] ?? [];
        $mail = (new MailMessage)
            ->subject('Déclaration soumise avec succès')
            ->line('Votre déclaration a été soumise avec succès.')
            ->line('Veuillez revenir dans 2 jours pour le contrôle.');
        if (!empty($produits)) {
            $mail->line('Produits déclarés :');
            foreach ($produits as $produit) {
                $mail->line('- ' . ($produit['nom_produit'] ?? 'Produit') . ' | Quantité : ' . ($produit['quantite'] ?? ''));
            }
        }
        $mail->action('Voir votre tableau de bord', url('/client/dashboard'));
        $mail->line('Merci d’utiliser notre plateforme !');
        return $mail;
    }

    public function toDatabase($notifiable)
    {
        $produits = $this->declarationData['produits'] ?? [];
        return [
            'sujet' => 'Déclaration soumise avec succès',
            'message' => 'Votre déclaration a été soumise avec succès. Veuillez revenir dans 2 jours pour le contrôle.',
            'produits' => $produits,
            'dashboard_url' => url('/client/dashboard'),
        ];
    }

    // public function toDatabase($notifiable)
    // {
    //     $produits = $this->declarationData['produits'] ?? [];
    //     return [
    //         'sujet' => 'Déclaration soumise avec succès',
    //         'message' => 'Votre déclaration a été soumise avec succès. Veuillez revenir dans 2 jours pour le contrôle.',
    //         'produits' => $produits,
    //         'dashboard_url' => url('/client/dashboard'),
    //     ];
    // }
}
