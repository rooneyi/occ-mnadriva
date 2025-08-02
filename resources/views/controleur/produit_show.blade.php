@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Détail du produit</h2>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $produit->nom_produit }}</h5>
            <p class="card-text"><strong>Catégorie :</strong> {{ $produit->categorie_produit }}</p>
            <p class="card-text"><strong>Description :</strong> {{ $produit->description }}</p>
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
            @if(isset($validite))
                <div class="alert mt-3 {{ $validite['passable'] ? 'alert-success' : 'alert-danger' }}">
                    <strong>Validité :</strong> {{ $validite['message'] }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
