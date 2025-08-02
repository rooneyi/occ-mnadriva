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
</div>
@endsection
