@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <h2 class="text-2xl font-bold text-blue-900 mb-6">Détail du produit</h2>
    <div class="mb-8">
        @if($produit)
            <h3 class="text-lg font-bold text-blue-900 mb-4">{{ $produit->nom_produit }}</h3>
            <p class="text-blue-800"><strong>Catégorie :</strong> {{ $produit->categorie_produit }}</p>
            <p class="text-blue-800"><strong>Description :</strong> {{ $produit->description }}</p>
        @else
            <p class="text-red-600">Aucun produit lié à cette demande.</p>
        @endif
    </div>
    <div class="mb-8">
        <h3 class="text-lg font-bold text-blue-900 mb-4">Scanner / Mettre à jour les dates</h3>
        @if($produit)
            <form action="{{ route('controleur.produit.scan', $produit->id_produit) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="date_fabrication" class="form-label">Date de fabrication</label>
                    <input type="date" name="date_fabrication" id="date_fabrication" class="form-control" value="{{ old('date_fabrication', $produit->date_fabrication) }}" required>
                </div>
                <div class="mb-3">
                    <label for="date_expiration" class="form-label">Date d'expiration</label>
                    <input type="date" name="date_expiration" id="date_expiration" class="form-control" value="{{ old('date_expiration', $produit->date_expiration) }}" required>
                </div>
                <button type="submit" class="btn btn-success">Scanner / Mettre à jour</button>
            </form>
        @endif
        @if(isset($validite))
            <div class="alert mt-3 {{ $validite['passable'] ? 'alert-success' : 'alert-danger' }}">
                <strong>Validité :</strong> {{ $validite['message'] }}
            </div>
        @endif
    </div>
    <div class="mb-8">
        <h3 class="text-lg font-bold text-blue-900 mb-4">Déclaration associée</h3>
        @if(isset($declaration))
            <div class="p-4 bg-gray-100 rounded">
                <p><strong>Numéro de déclaration :</strong> {{ $declaration->id_declaration }}</p>
                <p><strong>Client :</strong> {{ $declaration->client->name ?? '-' }}</p>
                <p><strong>Date de soumission :</strong> {{ $declaration->date_soumission }}</p>
                <p><strong>Statut :</strong> {{ $declaration->statut }}</p>
            </div>
        @endif
    </div>
    <div class="mb-8">
        <h3 class="text-lg font-bold text-blue-900 mb-4">Rapport du laborantin</h3>
        @if(isset($rapportLaborantin) && $rapportLaborantin)
            <div class="p-4 bg-gray-100 rounded">
                <p><strong>Désignation produit :</strong> {{ $rapportLaborantin->designation_produit }}</p>
                <p><strong>Laborantin :</strong> {{ $rapportLaborantin->laborantin->name ?? '-' }}</p>
                <p><strong>Conclusion :</strong> {{ $rapportLaborantin->conclusion }}</p>
                <p><strong>Statut du rapport :</strong> {{ $rapportLaborantin->statut }}</p>
            </div>
        @else
            <p class="text-gray-500">Aucun rapport du laborantin disponible pour ce produit.</p>
        @endif
    {{-- Intégration du composant Livewire pour les commentaires --}}
    @if($produit)
        <div class="mb-8">
            @livewire('controleur.commentaires-produit', ['produitId' => $produit->id_produit])
        </div>
    @endif
</div>
@endsection
