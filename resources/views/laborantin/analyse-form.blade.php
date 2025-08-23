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
            <select name="id_declaration" id="declarationSelect" class="border rounded p-2 w-full" required onchange="afficherDetailsDeclaration()">
                <option value="">Sélectionner une déclaration</option>
                @foreach($declarations as $declaration)
                    @php $produit = $declaration->produits->first(); @endphp
                    <option value="{{ $declaration->id_declaration }}"
                        data-produit="{{ $produit ? $produit->nom_produit : '' }}"
                        data-quantite="{{ $produit ? ($produit->pivot->quantite ?? $produit->quantite ?? '') : '' }}"
                        data-date-fabrication="{{ $produit ? $produit->date_fabrication : '' }}"
                        data-date-expiration="{{ $produit ? $produit->date_expiration : '' }}"
                        @if(isset($preselectedDeclaration) && $preselectedDeclaration->id_declaration == $declaration->id_declaration) selected @endif>
                        #{{ $declaration->id_declaration }} - {{ $produit ? $produit->nom_produit : '' }} ({{ $declaration->date_soumission }})
                    </option>
                @endforeach
            </select>
            <div id="detailsDeclaration" class="mt-4 p-4 bg-gray-100 rounded hidden">
                <h4 class="font-bold mb-2">Détails de la déclaration sélectionnée :</h4>
                <div id="produitDetails"></div>
            </div>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Produit :</label>
            <input type="text" name="designation_produit" id="designation_produit" class="border rounded p-2 w-full" required value="{{ $preselectedProduit->nom_produit ?? $preselectedProduit->designation ?? $preselectedProduit->designation_produit ?? '' }}">
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Quantité :</label>
            <input type="number" name="quantite" id="quantite" class="border rounded p-2 w-full" required value="{{ $preselectedProduit->quantite ?? '' }}">
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Date de fabrication :</label>
            <input type="date" name="date_fabrication" id="date_fabrication" class="border rounded p-2 w-full" required value="{{ $preselectedProduit->date_fabrication ?? '' }}">
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Date d'expiration :</label>
            <input type="date" name="date_expiration" id="date_expiration" class="border rounded p-2 w-full" required value="{{ $preselectedProduit->date_expiration ?? '' }}">
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Code lab :</label>
            <input type="text" name="code_lab" value="test" class="border rounded p-2 w-full" required>
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
        function afficherDetailsDeclaration() {
            const declarationSelect = document.getElementById('declarationSelect');
            const designationProduit = document.getElementById('designation_produit');
            const quantite = document.getElementById('quantite');
            const dateFabrication = document.getElementById('date_fabrication');
            const dateExpiration = document.getElementById('date_expiration');
            const detailsDiv = document.getElementById('detailsDeclaration');
            const produitDetails = document.getElementById('produitDetails');
            const selected = declarationSelect.options[declarationSelect.selectedIndex];
            designationProduit.value = selected.getAttribute('data-produit') || '';
            quantite.value = selected.getAttribute('data-quantite') || '';
            dateFabrication.value = selected.getAttribute('data-date-fabrication') || '';
            dateExpiration.value = selected.getAttribute('data-date-expiration') || '';
            if (selected.value) {
                detailsDiv.classList.remove('hidden');
                produitDetails.innerHTML =
                    '<strong>Produit :</strong> ' + (selected.getAttribute('data-produit') || '-') + '<br>' +
                    '<strong>Quantité :</strong> ' + (selected.getAttribute('data-quantite') || '-') + '<br>' +
                    '<strong>Date de fabrication :</strong> ' + (selected.getAttribute('data-date-fabrication') || '-') + '<br>' +
                    '<strong>Date d\'expiration :</strong> ' + (selected.getAttribute('data-date-expiration') || '-') + '<br>';
            } else {
                detailsDiv.classList.add('hidden');
                produitDetails.innerHTML = '';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            afficherDetailsDeclaration();
        });
        </script>
    </form>
</div>
@endsection
