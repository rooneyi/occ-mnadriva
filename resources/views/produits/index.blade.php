@extends('components.layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Liste des produits</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('produits.create') }}" class="btn btn-primary mb-3">Ajouter un produit</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Catégorie</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($produits as $produit)
            <tr>
                <td>{{ $produit->id_produit }}</td>
                <td>{{ $produit->categorie_produit }}</td>
                <td>{{ $produit->nom_produit }}</td>
                <td>{{ $produit->description }}</td>
                <td>
                    <a href="{{ route('produits.edit', $produit->id_produit) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('produits.destroy', $produit->id_produit) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
