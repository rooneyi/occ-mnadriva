<div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-8 mt-8">
    <h2 class="text-2xl font-bold text-blue-900 mb-6">Soumettre une déclaration</h2>
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
    @endif
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="submit" class="space-y-5">
        <div>
            <label for="designation_produit" class="block font-semibold mb-1">Désignation du produit</label>
            <select id="designation_produit" wire:model="designation_produit" class="w-full px-4 py-2 border border-blue-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300">
                <option value="">-- Sélectionner un produit --</option>
                @foreach($produits as $produit)
                    <option value="{{ $produit->nom_produit }}">
                        {{ $produit->nom_produit }} ({{ $produit->categorie_produit }})
                        @if($produit->description)
                            - {{ $produit->description }}
                        @endif
                    </option>
                @endforeach
            </select>
            @error('designation_produit') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="quantiter" class="block font-semibold mb-1">Quantité</label>
            <input type="number" id="quantiter" wire:model="quantiter" class="w-full px-4 py-2 border border-blue-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300" min="1">
            @error('quantiter') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="unite" class="block font-semibold mb-1">Unité</label>
            <input type="text" id="unite" wire:model="unite" class="w-full px-4 py-2 border border-blue-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300">
            @error('unite') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="numero_impot" class="block font-semibold mb-1">Numéro d'impôt</label>
            <input type="text" id="numero_impot" wire:model="numero_impot" class="w-full px-4 py-2 border border-blue-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300">
            @error('numero_impot') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="fichier" class="block font-semibold mb-1">Joindre La licence</label>
            <input type="file" id="fichier" wire:model="fichier" class="w-full px-4 py-2 border border-blue-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300">
            @error('fichier') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-bold py-3 rounded-lg text-lg transition">Soumettre</button>
    </form>
</div>
