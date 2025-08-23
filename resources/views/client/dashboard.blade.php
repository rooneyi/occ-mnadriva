@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    @if (session('success'))
        <div class="mb-4 p-4 rounded bg-green-100 border border-green-400 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 rounded bg-red-100 border border-red-400 text-red-800">
            {{ session('error') }}
        </div>
    @endif
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold mb-6">Mon Dossier</h1>
        <a href="{{ route('client.notifications') }}" class="relative inline-block px-4 py-2 text-blue-900 border border-blue-900 rounded-lg hover:bg-blue-900 hover:text-white transition">
            Notifications
            @php $unread = auth()->user()->unreadNotifications->count(); @endphp
            @if($unread > 0)
                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $unread }}</span>
            @endif
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Soumettre une déclaration -->
        <a href="{{ route('client.declaration') }}" class="block p-6 bg-white rounded shadow hover:bg-blue-50 transition">
            <h2 class="text-lg font-semibold mb-2">Soumettre une déclaration</h2>
            <p>Remplissez le formulaire numérique pour déclarer votre dossier.</p>
        </a>
        <!-- Déclarer un ou plusieurs produits -->
        <a href="{{ route('client.declaration') }}#produits" class="block p-6 bg-white rounded shadow hover:bg-blue-50 transition">
            <h2 class="text-lg font-semibold mb-2">Déclarer un ou plusieurs produits</h2>
            <p>Ajoutez un ou plusieurs produits à votre déclaration.</p>
        </a>
        <!-- Joindre un document (licence) -->
        <a href="{{ route('client.declaration') }}#licence" class="block p-6 bg-white rounded shadow hover:bg-blue-50 transition">
            <h2 class="text-lg font-semibold mb-2">Joindre un document (licence)</h2>
            <p>Ajoutez une licence ou un document à votre déclaration.</p>
        </a>
        <!-- Notifications sur le statut du dossier -->
        <a href="{{ route('client.notifications') }}" class="block p-6 bg-white rounded shadow hover:bg-blue-50 transition">
            <h2 class="text-lg font-semibold mb-2">Notifications</h2>
            <p>Consultez les notifications sur le statut de vos dossiers.</p>
        </a>
        <!-- Liste des rapports d'analyse -->
        <div class="block p-6 bg-white rounded shadow hover:bg-blue-50 transition">
            <h2 class="text-lg font-semibold mb-2">Rapports d'analyse</h2>
            <p>Téléchargez vos rapports d'analyse de produits.</p>
            @if($declarations->isNotEmpty())
                @foreach($declarations as $declaration)
                    @if($declaration->rapports && $declaration->rapports->isNotEmpty())
                        <ul class="list-disc ml-5">
                        @foreach($declaration->rapports as $rapport)
                            <li>
                                <span class="font-semibold">Rapport #{{ $rapport->id_rapport ?? $rapport->id }}</span>
                                <span>({{ $rapport->designation_produit ?? 'Produit' }})</span>
                                <span class="text-xs text-gray-500">{{ $rapport->created_at ? $rapport->created_at->format('d/m/Y') : '-' }}</span>
                                @if(in_array($rapport->statut, ['valide', 'rejete']))
                                    <a href="{{ route('client.rapport.download', ['rapportId' => $rapport->id_rapport ?? $rapport->id]) }}" class="ml-2 px-2 py-1 bg-blue-600 text-white rounded text-xs">Télécharger</a>
                                @else
                                    <span class="ml-2 px-2 py-1 bg-gray-400 text-white rounded text-xs cursor-not-allowed" title="Rapport non validé ou rejeté">Non disponible</span>
                                @endif
                            </li>
                        @endforeach
                        </ul>
                    @endif
                @endforeach
            @else
                <p class="text-gray-500">Aucun rapport disponible.</p>
            @endif
        </div>
    </div>
</div>
@endsection

