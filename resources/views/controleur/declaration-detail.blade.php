@extends('layouts.app')

@section('content')
<div>
    <h2 class="text-xl font-bold mb-4">Demandes assignées au contrôleur</h2>
    <ul class="mb-6">
        @foreach($demandes as $demande)
            <li class="mb-2 p-2 border rounded">
                <strong>Déclaration #{{ $demande->id }}</strong> - {{ $demande->created_at->format('d/m/Y H:i') }}<br>
                <strong>Client :</strong> {{ $demande->client->name ?? 'N/A' }}<br>
                <strong>Statut :</strong> {{ $demande->statut ?? 'N/A' }}<br>
                <a href="{{ route('controleur.declaration.detail', $demande->id) }}" class="text-blue-600 underline">Voir détail</a>
            </li>
        @endforeach
    </ul>
    <h2 class="text-xl font-bold mb-4">Détail de la déclaration</h2>
    <div class="mb-4">
        <strong>Client :</strong> {{ $declaration->client->name ?? 'N/A' }}<br>
        <strong>Date de soumission :</strong> {{ $declaration->created_at->format('d/m/Y H:i') }}<br>
        <strong>Statut :</strong> {{ $declaration->statut ?? 'N/A' }}<br>
        <strong>Description :</strong> {{ $declaration->description ?? 'N/A' }}<br>
    </div>
    <h3 class="font-bold mb-2">Produits déclarés :</h3>
    <ul>
        @foreach($declaration->produits as $produit)
            <li>
                <strong>{{ $produit->nom_produit }}</strong> ({{ $produit->categorie_produit }})<br>
                Date fabrication : {{ $produit->date_fabrication }}<br>
                Date expiration : {{ $produit->date_expiration }}<br>
            </li>
        @endforeach
    </ul>
</div>
@endsection
