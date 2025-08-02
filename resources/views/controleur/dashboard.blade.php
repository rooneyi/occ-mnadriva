@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-blue-900 mb-4 sm:mb-0">Tableau de bord du Contrôleur</h2>
        <a href="{{ route('controleur.notifications') }}" class="relative px-4 py-2 rounded bg-blue-900 text-white font-semibold hover:bg-yellow-500 hover:text-blue-900 transition">
            Notifications
            @php $unread = auth()->user()->unreadNotifications->count(); @endphp
            @if($unread > 0)
                <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $unread }}</span>
            @endif
        </a>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-blue-900 mb-4">Demandes assignées</h3>
        @if($demandes->isEmpty())
            <div class="bg-yellow-50 text-blue-900 p-4 rounded mb-4">Aucune demande assignée.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-blue-200">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">ID</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Client</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Date</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Statut</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-blue-100">
                        @foreach($demandes as $demande)
                        <tr>
                            <td class="px-4 py-2">{{ $demande->id }}</td>
                            <td class="px-4 py-2">{{ $demande->client->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $demande->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">{{ $demande->statut }}</td>
                            <td class="px-4 py-2">
                                <!-- Ajoute ici les actions spécifiques (ex: voir, valider, rejeter) -->
                                <a href="{{ route('controleur.demande.show', $demande->id) }}" class="px-3 py-1 rounded bg-yellow-500 text-blue-900 font-bold hover:bg-blue-900 hover:text-white transition">Voir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
