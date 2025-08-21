<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DeclarationValideeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $declarationId = $this->data['declaration_id'];
        $estValide = $this->data['est_valide'];
        $commentaire = $this->data['commentaire'] ?? 'Aucun commentaire';
        $dateValidation = $this->data['date_validation'];

        $mail = (new MailMessage)
            ->subject('Décision concernant votre déclaration #' . $declarationId)
            ->greeting('Bonjour ' . $notifiable->name . ',');

        if ($estValide) {
            $mail->line('Nous avons le plaisir de vous informer que votre déclaration #' . $declarationId . ' a été validée avec succès.')
                ->line('Date de validation : ' . $dateValidation);
        } else {
            $mail->line('Nous vous informons que votre déclaration #' . $declarationId . ' a été rejetée.')
                ->line('Raison : ' . $commentaire)
                ->line('Date du rejet : ' . $dateValidation)
                ->line('Veuillez contacter notre service client pour plus d\'informations.');
        }

        $mail->action('Voir ma déclaration', url('/declarations/' . $declarationId))
            ->line('Merci de votre confiance !');

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'declaration_id' => $this->data['declaration_id'],
            'est_valide' => $this->data['est_valide'],
            'message' => $this->data['est_valide'] 
                ? 'Votre déclaration #' . $this->data['declaration_id'] . ' a été validée.'
                : 'Votre déclaration #' . $this->data['declaration_id'] . ' a été rejetée.',
            'commentaire' => $this->data['commentaire'] ?? '',
            'date_validation' => $this->data['date_validation'],
            'url' => url('/declarations/' . $this->data['declaration_id'])
        ];
    }
}
