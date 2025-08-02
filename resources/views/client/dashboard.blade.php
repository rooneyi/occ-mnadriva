@extends('components.layouts.app')

@section('content')
<div class="container mx-auto py-8">
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
        <!-- Télécharger le rapport d'analyse -->
        <a href="{{ route('client.rapport.download', ['rapportId' => 1]) }}" class="block p-6 bg-white rounded shadow hover:bg-blue-50 transition">
            <h2 class="text-lg font-semibold mb-2">Télécharger le rapport d'analyse</h2>
            <p>Téléchargez le rapport d'analyse de vos produits.</p>
        </a>
    </div>
</div>
@endsection

