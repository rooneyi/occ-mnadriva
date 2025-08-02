@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Formulaire de résultats d'analyse</h2>
    <form action="{{ route('laborantin.analyse.submit', $declaration->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Code laboratoire</label>
            <input type="text" name="code_lab" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Produit</label>
            <input type="text" name="designation_produit" class="form-control" value="{{ $declaration->designation_produit }}" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantité</label>
            <input type="number" name="quantite" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Méthode d'essai</label>
            <input type="text" name="methode_essai" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Aspect extérieur</label>
            <input type="text" name="aspect_exterieur" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Résultat d'analyse</label>
            <textarea name="resultat_analyse" class="form-control" required rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Date de fabrication</label>
            <input type="date" name="date_fabrication" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Date d'expiration</label>
            <input type="date" name="date_expiration" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Conclusion</label>
            <textarea name="conclusion" class="form-control" required rows="2"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Soumettre le rapport</button>
    </form>
</div>
@endsection
