@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <h2 class="text-2xl font-bold text-blue-900 mb-6">Détail de la demande #{{ $demande->id }}</h2>
    <div class="mb-8">
        <p class="text-blue-800">Client : <span class="font-semibold">{{ $demande->client->name ?? '-' }}</span></p>
        <p class="text-blue-800">Date : <span class="font-semibold">{{ $demande->created_at->format('d/m/Y') }}</span></p>
        <p class="text-blue-800">Statut : <span class="font-semibold">{{ $demande->statut }}</span></p>
    </div>
    <div class="mb-8">
        <h3 class="text-lg font-bold text-blue-900 mb-4">Gestion des produits</h3>
        @livewire('produit-crud', ['demande' => $demande])
    </div>
    <div class="mb-8">
        <h3 class="text-lg font-bold text-blue-900 mb-4">Actions sur les produits</h3>
        <ul class="space-y-3">
            <li>
                <span class="font-semibold text-blue-900">Ajouter un produit :</span>
                <a href="{{ route('controleur.produit.add', $demande->id) }}" class="px-3 py-1 rounded bg-green-500 text-white font-bold hover:bg-blue-900 hover:text-white transition">Ajouter produit</a>
            </li>
            <li>
                <span class="font-semibold text-blue-900">Prendre une photo :</span>
                <form action="{{ route('controleur.produit.photo', $demande->id) }}" method="POST" enctype="multipart/form-data" class="inline">
                    @csrf
                    <input type="file" name="photo" class="inline-block">
                    <button type="submit" class="px-3 py-1 rounded bg-yellow-500 text-blue-900 font-bold hover:bg-blue-900 hover:text-white transition">Envoyer</button>
                </form>
            </li>
            <li>
                <span class="font-semibold text-blue-900">Ajouter un commentaire :</span>
                <form action="{{ route('controleur.produit.commentaire', $demande->id) }}" method="POST" class="inline">
                    @csrf
                    <input type="text" name="commentaire" placeholder="Votre commentaire" class="px-2 py-1 rounded border border-blue-200">
                    <button type="submit" class="px-3 py-1 rounded bg-blue-900 text-white font-bold hover:bg-yellow-500 hover:text-blue-900 transition">Ajouter</button>
                </form>
            </li>
            <li>
                <span class="font-semibold text-blue-900">Valider le produit :</span>
                <a href="{{ route('controleur.produit.valider', $demande->id) }}" class="px-3 py-1 rounded bg-blue-900 text-white font-bold hover:bg-yellow-500 hover:text-blue-900 transition">Valider</a>
            </li>
            <li>
                <span class="font-semibold text-blue-900">Rejeter le produit :</span>
                <a href="{{ route('controleur.produit.rejeter', $demande->id) }}" class="px-3 py-1 rounded bg-red-600 text-white font-bold hover:bg-yellow-500 hover:text-blue-900 transition">Rejeter</a>
            </li>
        </ul>
    </div>
</div>
@endsection
