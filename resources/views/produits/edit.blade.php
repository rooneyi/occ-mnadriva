@extends('components.layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Modifier le produit</h2>
    <form action="{{ route('produits.update', $produit->id_produit) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="categorie_produit" class="form-label">Catégorie</label>
            <input type="text" name="categorie_produit" id="categorie_produit" class="form-control" required value="{{ old('categorie_produit', $produit->categorie_produit) }}">
        </div>
        <div class="mb-3">
            <label for="nom_produit" class="form-label">Nom du produit</label>
            <input type="text" name="nom_produit" id="nom_produit" class="form-control" required value="{{ old('nom_produit', $produit->nom_produit) }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $produit->description) }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('produits.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
