@extends('components.layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Détail du produit</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $produit->nom_produit }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Catégorie : {{ $produit->categorie_produit }}</h6>
            <p class="card-text">Description : {{ $produit->description }}</p>
            <a href="{{ route('produits.edit', $produit->id_produit) }}" class="btn btn-warning">Modifier</a>
            <a href="{{ route('produits.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>
@endsection
