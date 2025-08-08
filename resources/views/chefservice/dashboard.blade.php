@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-10">
    <h2 class="text-3xl font-bold text-blue-900 mb-6">Tableau de bord - Chef de Service</h2>
    <form method="get" class="flex flex-col md:flex-row gap-4 mb-6">
        <input type="text" name="client" value="{{ request('client') }}" class="px-4 py-2 rounded border border-blue-200 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 flex-1" placeholder="Nom du client">
        <select name="statut" class="px-4 py-2 rounded border border-blue-200 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 flex-1">
            <option value="">Tous statuts</option>
            @foreach($statuts as $statut)
                <option value="{{ $statut }}" @if(request('statut')==$statut) selected @endif>{{ $statut }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 items-center">
            <button class="px-4 py-2 rounded bg-blue-900 text-white font-semibold hover:bg-yellow-500 hover:text-blue-900 transition" type="submit">Filtrer</button>
            <a href="{{ route('chefservice.dashboard') }}" class="px-4 py-2 rounded bg-yellow-100 text-blue-900 font-semibold hover:bg-yellow-200 transition">Réinitialiser</a>
        </div>
        <a href="{{ route('chefservice.export', request()->all()) }}" class="px-4 py-2 rounded bg-green-600 text-white font-semibold hover:bg-green-800 transition">Exporter Excel</a>
    </form>
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h3 class="text-xl font-bold text-blue-900 mb-4">Vue d'ensemble des dossiers</h3>
        @if($dossiers->isEmpty())
            <div class="bg-yellow-50 text-blue-900 p-4 rounded mb-4">Aucun dossier trouvé.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-blue-200">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">ID</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Client</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Produit(s)</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Déclarations</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Rapports</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Statut</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-blue-100">
                        @foreach($dossiers as $dossier)
                        <tr>
                            <td class="px-4 py-2">{{ $dossier->id }}</td>
                            <td class="px-4 py-2">{{ $dossier->client->nom ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $dossier->produits ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $dossier->declarations_count ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $dossier->rapports_count ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $dossier->statut ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('chefservice.dossier.detail', $dossier->id) }}" class="px-3 py-1 rounded bg-yellow-500 text-blue-900 font-bold hover:bg-blue-900 hover:text-white transition">Voir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h3 class="text-xl font-bold text-blue-900 mb-4">Historique des actions utilisateurs</h3>
        @if($actions->isEmpty())
            <div class="bg-yellow-50 text-blue-900 p-4 rounded mb-4">Aucune action enregistrée.</div>
        @else
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-blue-200">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Date</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Utilisateur</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Type</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Action</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Description</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-blue-100">
                        @foreach($actions as $action)
                        <tr>
                            <td class="px-4 py-2">{{ $action->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">{{ $action->user ? ($action->user->name ?? 'Utilisateur #'.$action->user_id) : 'Utilisateur #'.$action->user_id }}</td>
                            <td class="px-4 py-2">{{ ucfirst($action->user_type) }}</td>
                            <td class="px-4 py-2">{{ str_replace('_', ' ', $action->action) }}</td>
                            <td class="px-4 py-2">{{ $action->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
