@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Détails de la Déclaration</h1>
    <div class="bg-white rounded shadow p-6">
        <p><strong>ID de la Déclaration :</strong> {{ $declaration->id }}</p>
        <p><strong>Produit :</strong> {{ $declaration->produit->designation ?? 'N/A' }}</p>
        <p><strong>Quantité :</strong> {{ $declaration->quantite ?? 'N/A' }}</p>
        <p><strong>Statut :</strong> {{ $declaration->statut }}</p>
        <p><strong>Date de Soumission :</strong> {{ $declaration->date_soumission }}</p>
    </div>
    <div class="mt-6">
        <a href="{{ route('controleur.dashboard') }}" class="text-blue-600 hover:underline">&larr; Retour au tableau de bord</a>
    </div>
</div>
@endsection
