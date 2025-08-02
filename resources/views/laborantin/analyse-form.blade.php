@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Formulaire d'analyse laboratoire</h1>
    <form method="POST" action="{{ route('laborantin.rapport.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Déclaration :</label>
            <select name="id_declaration" class="border rounded p-2 w-full">
                @foreach($declarations as $declaration)
                    <option value="{{ $declaration->id }}">#{{ $declaration->id }} - {{ $declaration->client->name ?? 'Client' }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Produit :</label>
            <select name="designation_produit" class="border rounded p-2 w-full">
                @foreach($produits as $produit)
                    <option value="{{ $produit->nom_produit }}">{{ $produit->nom_produit }} ({{ $produit->categorie_produit }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Quantité :</label>
            <input type="number" name="quantite" class="border rounded p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Méthode d'essai :</label>
            <input type="text" name="methode_essai" class="border rounded p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Aspect extérieur :</label>
            <input type="text" name="aspect_exterieur" class="border rounded p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Résultat d'analyse :</label>
            <textarea name="resultat_analyse" class="border rounded p-2 w-full" required></textarea>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Date fabrication :</label>
            <input type="date" name="date_fabrication" class="border rounded p-2 w-full">
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Date expiration :</label>
            <input type="date" name="date_expiration" class="border rounded p-2 w-full">
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Conclusion :</label>
            <textarea name="conclusion" class="border rounded p-2 w-full" required></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Générer et soumettre le rapport</button>
    </form>
</div>
@endsection
