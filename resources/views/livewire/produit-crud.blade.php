<div class="max-w-2xl mx-auto bg-white rounded shadow p-6 mt-8">
    <h2 class="text-xl font-bold mb-4 text-blue-900">Gestion des produits</h2>
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="ajouter" class="flex flex-col md:flex-row gap-3 mb-6">
        <input type="text" wire:model="categorie_produit" placeholder="Catégorie du produit" class="flex-1 px-3 py-2 border border-blue-900 rounded-lg focus:ring-2 focus:ring-yellow-300" />
        <input type="text" wire:model="nom_produit" placeholder="Nom du produit" class="flex-1 px-3 py-2 border border-blue-900 rounded-lg focus:ring-2 focus:ring-yellow-300" />
        <button type="submit" class="bg-blue-800 hover:bg-blue-900 text-white font-semibold px-6 py-2 rounded-lg">Ajouter</button>
    </form>
    @error('categorie_produit') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    @error('nom_produit') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    <table class="w-full mt-4 text-left border-t">
        <thead>
            <tr>
                <th class="py-2">#</th>
                <th class="py-2">Catégorie</th>
                <th class="py-2">Nom</th>
                <th class="py-2">Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($produits as $produit)
            <tr class="border-b">
                <td class="py-2">{{ $produit->id_produit }}</td>
                <td class="py-2">{{ $produit->categorie_produit }}</td>
                <td class="py-2">{{ $produit->nom_produit }}</td>
                <td class="py-2">
                    <button wire:click="supprimer({{ $produit->id_produit }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        Supprimer
                    </button>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center py-4 text-gray-500">Aucun produit enregistré.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
