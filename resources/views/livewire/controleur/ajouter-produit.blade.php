<div>
    <h2 class="text-xl font-bold mb-4">Ajouter un produit</h2>
    <form wire:submit="ajouter">
        <input type="text" wire:model="categorie_produit" placeholder="Catégorie du produit" class="border rounded p-2 mb-2 w-full">
        <input type="text" wire:model="nom_produit" placeholder="Nom du produit" class="border rounded p-2 mb-2 w-full">
        <input type="text" wire:model="description_produit" placeholder="Description du produit" class="border rounded p-2 mb-2 w-full">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
    </form>
    @if(session('success'))
        <div class="text-green-600 mt-2">{{ session('success') }}</div>
    @endif
    <h3 class="text-lg font-semibold mt-6 mb-2">Liste des produits</h3>
    <ul>
        @foreach($produits as $produit)
            <li class="border-b py-2">{{ $produit->categorie_produit }} - {{ $produit->nom_produit }}</li>
        @endforeach
    </ul>
</div>

