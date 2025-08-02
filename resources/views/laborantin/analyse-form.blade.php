@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Formulaire d'analyse laboratoire</h1>
    <form method="POST" action="{{ route('laborantin.rapport.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Déclaration :</label>
            <select name="id_declaration" id="declarationSelect" class="border rounded p-2 w-full">
                @foreach($declarations as $declaration)
                    <option value="{{ $declaration->id }}"
                        data-produit="{{ $declaration->designation_produit }}"
                        data-date-fabrication="{{ $declaration->date_fabrication }}"
                        data-date-expiration="{{ $declaration->date_expiration }}">
                        #{{ $declaration->id }} - {{ $declaration->designation_produit ?? $declaration->nom_declaration ?? 'Déclaration' }} ({{ $declaration->client->name ?? 'Client' }} | {{ $declaration->client->numero ?? 'N° inconnu' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Produit :</label>
            <select name="designation_produit" id="produitSelect" class="border rounded p-2 w-full">
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
            <input type="date" name="date_fabrication" id="dateFabricationInput" class="border rounded p-2 w-full">
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
        const declarationSelect = document.getElementById('declarationSelect');
        const produitSelect = document.getElementById('produitSelect');
        const dateFabricationInput = document.getElementById('dateFabricationInput');
        const dateExpirationInput = document.getElementById('dateExpirationInput');

        function remplirChamps() {
            const selectedOption = declarationSelect.options[declarationSelect.selectedIndex];
            const produit = selectedOption.getAttribute('data-produit');
            const dateFabrication = selectedOption.getAttribute('data-date-fabrication');
            const dateExpiration = selectedOption.getAttribute('data-date-expiration');
            let found = false;
            for (let i = 0; i < produitSelect.options.length; i++) {
                if (produitSelect.options[i].value.trim().toLowerCase() === (produit ? produit.trim().toLowerCase() : '')) {
                    produitSelect.selectedIndex = i;
                    found = true;
                    break;
                }
            }
            if (!found) {
                produitSelect.selectedIndex = 0;
            }
            dateFabricationInput.value = dateFabrication || '';
            dateExpirationInput.value = dateExpiration || '';
            console.log('Champs préremplis:', {produit, dateFabrication, dateExpiration});
        }

        declarationSelect.addEventListener('change', remplirChamps);
        // Préremplir au chargement si une déclaration est sélectionnée
        if (declarationSelect.selectedIndex > -1) {
            remplirChamps();
        }
    });
    </script>
    @endpush
</div>
@endsection
