<div wire:poll.2s>
    <h2>Notifications</h2>
    <div class="mt-4">
        @forelse($notifications as $notification)
            <div class="alert alert-info mb-2 flex justify-between items-center">
                <div>
                    <strong>Type :</strong> {{ $notification->type ?? 'N/A' }}<br>
                    <strong>Message :</strong> {{ $notification->data['message'] ?? ($notification->data['designation_produit'] ?? 'Non spécifié') }}<br>
@if(isset($notification->data['controle']) && $notification->data['controle'] === true)
    <span class="text-warning font-semibold">Merci de vous présenter pour le contrôle dans 2 jours.</span><br>
@endif
                    <a href="#" wire:click.prevent="showDetails('{{ $notification->id }}')" class="text-primary">
                        Voir les détails
                    </a><br>
                    <small>Créée {{ $notification->created_at->diffForHumans() }}</small>
                </div>
            </div>
            @if($selectedNotification === $notification->id && $selectedNotificationDetails)
                <div class="p-3 bg-gray-100 border mb-2">
                    <strong>Détails :</strong>
                    <pre>{{ json_encode($selectedNotificationDetails->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            @endif
        @empty
            <div class="alert alert-secondary">Aucune notification.</div>
        @endforelse
    </div>
</div>
