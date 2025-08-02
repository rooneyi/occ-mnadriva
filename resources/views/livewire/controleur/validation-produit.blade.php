<div>
    <h2 class="text-xl font-bold mb-4">Valider ou rejeter un produit</h2>
    @if($produit)
        <div class="mb-4">
            <strong>Nom :</strong> {{ $produit->nom }}<br>
            <strong>Description :</strong> {{ $produit->description }}<br>
            <strong>Statut actuel :</strong> {{ $produit->statut }}
        </div>
        <form wire:submit.prevent="valider" class="inline-block mr-2">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Valider</button>
        </form>
        <form wire:submit.prevent="rejeter" class="inline-block">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Rejeter</button>
        </form>
        @if(session('success'))
            <div class="text-green-600 mt-2">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="text-red-600 mt-2">{{ session('error') }}</div>
        @endif
    @else
        <div class="text-gray-500">Aucun produit sélectionné.</div>
    @endif
</div>

