<div wire:poll.2s class="bg-white rounded-lg shadow p-6 max-w-3xl mx-auto mt-8">
    <h2 class="text-2xl font-bold text-blue-900 mb-6 flex items-center gap-2">
        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        Notifications
    </h2>
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="flex flex-col md:flex-row md:items-center justify-between bg-blue-50 border border-blue-200 rounded-lg p-4 shadow-sm transition hover:shadow-md">
                <div class="flex-1 text-blue-900">
                    @if(isset($notification->data['sujet']))
                        <div class="font-semibold text-lg mb-1">{{ $notification->data['sujet'] }}</div>
                        <div class="mb-2">{{ $notification->data['message'] }}</div>
                        @if(!empty($notification->data['produits_list']))
                            <ul class="list-disc list-inside mb-2 text-sm text-blue-800">
                                @foreach($notification->data['produits_list'] as $produit)
                                    <li>{{ $produit }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="{{ $notification->data['dashboard_url'] }}" class="text-blue-700 underline font-medium hover:text-blue-900">Voir votre tableau de bord</a>
                    @else
                        <div class="mb-1"><span class="font-semibold">Déclaration ID :</span> {{ $notification->data['declaration_id'] ?? 'Non spécifié' }}</div>
                        <div class="mb-1"><span class="font-semibold">Produit :</span> {{ $notification->data['produit'] ?? ($notification->data['designation_produit'] ?? 'Non spécifié') }}</div>
                        <div class="mb-1"><span class="font-semibold">Quantité :</span> {{ $notification->data['quantite'] ?? ($notification->data['quantiter'] ?? 'Non spécifiée') }}</div>
                        <div class="mb-1"><span class="font-semibold">Statut :</span> {{ $notification->data['statut'] ?? 'Non spécifié' }}</div>
                        @if(isset($notification->data['url']))
                            <a href="{{ $notification->data['url'] }}" class="text-blue-700 underline font-medium hover:text-blue-900">Voir les détails</a>
                        @endif
                    @endif
                    <div class="text-xs text-gray-500 mt-2">Créée {{ $notification->created_at->diffForHumans() }}</div>
                </div>
                @if(is_null($notification->read_at))
                    <button wire:click="markAsRead('{{ $notification->id }}')"
                        class="mt-3 md:mt-0 md:ml-6 px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-800 transition font-semibold">
                        Marquer comme lue
                    </button>
                @endif
            </div>
        @empty
            <div class="bg-gray-100 border border-gray-300 text-gray-500 p-4 rounded text-center">Aucune notification.</div>
        @endforelse
    </div>
</div>
