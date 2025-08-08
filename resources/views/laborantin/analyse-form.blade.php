@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Formulaire d'analyse laboratoire</h1>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('laborantin.rapport.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Déclaration :</label>
            <select name="id_declaration" id="declarationSelect" class="border rounded p-2 w-full" required>
                <option value="">Sélectionner une déclaration</option>
                @foreach($declarations as $declaration)
                    <option value="{{ $declaration->id_declaration }}"
                        data-produit="{{ $declaration->produits->first()->nom_produit ?? '' }}"
                        data-quantite="{{ $declaration->produits->first()->quantite ?? '' }}"
                        data-date-fabrication="{{ $declaration->produits->first()->date_fabrication ?? '' }}"
                        data-date-expiration="{{ $declaration->produits->first()->date_expiration ?? '' }}">
                        #{{ $declaration->id_declaration }} - {{ $declaration->produits->first()->nom_produit ?? '' }} ({{ $declaration->date_soumission }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Produit :</label>
            <input type="text" name="designation_produit" id="designation_produit" class="border rounded p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Quantité :</label>
            <input type="number" name="quantite" id="quantite" class="border rounded p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Date de fabrication :</label>
            <input type="date" name="date_fabrication" id="date_fabrication" class="border rounded p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Date d'expiration :</label>
            <input type="date" name="date_expiration" id="date_expiration" class="border rounded p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Code lab :</label>
            <input type="text" name="code_lab" class="border rounded p-2 w-full" required>
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
            <label class="block font-semibold mb-1">Conclusion :</label>
            <textarea name="conclusion" class="border rounded p-2 w-full" required></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Générer et soumettre le rapport</button>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const declarationSelect = document.getElementById('declarationSelect');
            const designationProduit = document.getElementById('designation_produit');
            const quantite = document.getElementById('quantite');
            const dateFabrication = document.getElementById('date_fabrication');
            const dateExpiration = document.getElementById('date_expiration');
            declarationSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                designationProduit.value = selected.getAttribute('data-produit') || '';
                quantite.value = selected.getAttribute('data-quantite') || '';
                dateFabrication.value = selected.getAttribute('data-date-fabrication') || '';
                dateExpiration.value = selected.getAttribute('data-date-expiration') || '';
            });
            if (declarationSelect.value) {
                const selected = declarationSelect.options[declarationSelect.selectedIndex];
                designationProduit.value = selected.getAttribute('data-produit') || '';
                quantite.value = selected.getAttribute('data-quantite') || '';
                dateFabrication.value = selected.getAttribute('data-date-fabrication') || '';
                dateExpiration.value = selected.getAttribute('data-date-expiration') || '';
            }
        });
        </script>
    </form>
</div>
@endsection
