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
            <label class="block font-semibold mb-1">Produit :</label>
            <select name="id_produit" id="produitSelect" class="border rounded p-2 w-full" required>
                @foreach($produits as $produit)
                    <option value="{{ $produit->id_produit }}"
                        data-nom-produit="{{ $produit->nom_produit }}"
                        data-date-fabrication="{{ $produit->date_fabrication }}"
                        data-date-expiration="{{ $produit->date_expiration }}"
                        data-declaration-id="{{ optional($produit->declarations->first())->id_declaration ?? '' }}"
                        data-declaration-date="{{ optional($produit->declarations->first())->date_soumission ?? '' }}"
                        data-declaration-client="{{ optional($produit->declarations->first())->user->name ?? '' }}">
                        {{ $produit->nom_produit }} ({{ $produit->categorie_produit }})
                    </option>
                @endforeach
            </select>
            <div id="declarationDetails" class="mb-4 hidden">
                <h2 class="font-semibold mb-2">Déclaration liée :</h2>
                <div id="declarationInfo"></div>
            </div>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Quantité :</label>
            <input type="number" name="quantite" class="border rounded p-2 w-full" required>
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
            <label class="block font-semibold mb-1">Date fabrication :</label>
            <input type="date" name="date_fabrication" id="dateFabricationInput" class="border rounded p-2 w-full" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Date expiration :</label>
            <input type="date" name="date_expiration" id="dateExpirationInput" class="border rounded p-2 w-full">
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Conclusion :</label>
            <textarea name="conclusion" class="border rounded p-2 w-full" required></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Générer et soumettre le rapport</button>
    </form>
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const produitSelect = document.getElementById('produitSelect');
        const dateFabricationInput = document.getElementById('dateFabricationInput');
        const dateExpirationInput = document.getElementById('dateExpirationInput');

        function remplirChamps() {
            const selectedOption = produitSelect.options[produitSelect.selectedIndex];
            const dateFabrication = selectedOption.getAttribute('data-date-fabrication');
            const dateExpiration = selectedOption.getAttribute('data-date-expiration');
            dateFabricationInput.value = dateFabrication || '';
            dateExpirationInput.value = dateExpiration || '';

            // Affichage déclaration liée
            const declarationId = selectedOption.getAttribute('data-declaration-id');
            const declarationDate = selectedOption.getAttribute('data-declaration-date');
            const declarationClient = selectedOption.getAttribute('data-declaration-client');
            const declarationDiv = document.getElementById('declarationDetails');
            const declarationInfo = document.getElementById('declarationInfo');
            if (declarationId) {
                declarationDiv.classList.remove('hidden');
                declarationInfo.innerHTML = `
                    <p><b>ID déclaration :</b> ${declarationId}</p>
                    <p><b>Date soumission :</b> ${declarationDate}</p>
                    <p><b>Client :</b> ${declarationClient}</p>
                `;
            } else {
                declarationDiv.classList.add('hidden');
                declarationInfo.innerHTML = '';
            }
        }

        produitSelect.addEventListener('change', remplirChamps);
        // Préremplir au chargement si un produit est sélectionné
        if (produitSelect.selectedIndex > -1) {
            remplirChamps();
        }
    });
    </script>
    @endpush
</div>
@endsection
