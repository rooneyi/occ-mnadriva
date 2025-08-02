<?php
namespace App\Notifications;

use App\Models\RapportAnalyse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnalyseSubmitted extends Notification implements ShouldQueue
{
    use Queueable;
    public $rapport;
    public function __construct(RapportAnalyse $rapport)
    {
        $this->rapport = $rapport;
    }
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouveau rapport d\'analyse soumis')
            ->greeting('Bonjour Contrôleur !')
            ->line('Un nouveau rapport d\'analyse a été soumis par le laborantin.')
            ->line('Produit : ' . $this->rapport->designation_produit)
            ->line('Conclusion : ' . $this->rapport->conclusion)
            ->action('Voir les rapports', url('/controleur/rapports'));
    }
    public function toArray($notifiable)
    {
        return [
            'title' => 'Nouveau rapport d\'analyse à valider',
            'message' => 'Le laborantin a soumis un rapport pour le produit ' . $this->rapport->designation_produit . ' (conclusion : ' . $this->rapport->conclusion . ')',
            'rapport_id' => $this->rapport->id_rapport,
        ];
    }
}
