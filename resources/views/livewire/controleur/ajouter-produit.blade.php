<div>
    <h2 class="text-xl font-bold mb-4">Ajouter un produit</h2>
    <form wire:submit="ajouter">
        @if(session('success'))
            <div class="text-green-600 bg-green-200 border rounded p-2  m-2">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="text-red-600 bg-red-200 border rounded p-2  m-2">{{ session('success') }}</div>
        @endif
        <input type="text" wire:model="categorie_produit" placeholder="Catégorie du produit" class="border rounded p-2 mb-2 w-full">
        <input type="text" wire:model="nom_produit" placeholder="Nom du produit" class="border rounded p-2 mb-2 w-full">
        <input type="text" wire:model="description_produit" placeholder="Description du produit" class="border rounded p-2 mb-2 w-full">
        <input type="date" wire:model="date_fabrication" placeholder="Nom du produit" class="border rounded p-2 mb-2 w-full">
        <input type="date" wire:model="date_expiration" placeholder="Description du produit" class="border rounded p-2 mb-2 w-full">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
    </form>

    <h3 class="text-lg font-semibold mt-6 mb-2">Liste des produits</h3>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="border px-4 py-2">Catégorie</th>
                <th class="border px-4 py-2">Nom</th>
                <th class="border px-4 py-2">Description</th>
                <th class="border px-4 py-2">Date de fabrication</th>
                <th class="border px-4 py-2">Date d'expiration</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $produit)
                <tr>
                    <td class="border px-4 py-2">{{ $produit->categorie_produit }}</td>
                    <td class="border px-4 py-2">{{ $produit->nom_produit }}</td>
                    <td class="border px-4 py-2">{{ $produit->description_produit }}</td>
                    <td class="border px-4 py-2">{{ $produit->date_fabrication }}</td>
                    <td class="border px-4 py-2">{{ $produit->date_expiration }}</td>
                    <td class="border px-4 py-2">
{{--                        <button wire:click="modifier({{ $produit->id }})" class="bg-yellow-500 text-white px-3 py-1 rounded">Modifier</button>--}}
                        <button wire:click="supprimer({{ $produit->id }})" class="bg-red-600 text-white px-3 py-1 rounded">Supprimer</button>
                    </td>
                </tr>
            @endforeach
    </table>

</div>

