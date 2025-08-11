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
            <label class="block font-semibold mb-1">Produits et quantités</label>
            <div class="space-y-2">
                @foreach($produits as $produit)
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="produit_{{ $produit->id_produit }}" wire:model="selectedProduits" value="{{ $produit->id_produit }}">
                        <label for="produit_{{ $produit->id_produit }}" class="flex-1">
                            {{ $produit->nom_produit }} ({{ $produit->categorie_produit }})
                            @if($produit->description)
                                - {{ $produit->description }}
                            @endif
                        </label>
                        @if(in_array($produit->id_produit, (array) $selectedProduits))
                            <input type="number" min="1" wire:model="quantites.{{ $produit->id_produit }}" placeholder="Quantité" class="w-24 px-2 py-1 border border-blue-900 rounded">
                        @endif
                    </div>
                @endforeach
            </div>
            @error('selectedProduits') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            @error('quantites') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
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
        <div>
            <label for="id_controleur" class="block font-semibold mb-1">Contrôleur assigné</label>
            <select id="id_controleur" wire:model="id_controleur" class="w-full px-4 py-2 border border-blue-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300">
                <option value="">-- Sélectionner un contrôleur --</option>
                @foreach($controleurs as $controleur)
                    <option value="{{ $controleur->id }}">
                        {{ $controleur->name }}
                    </option>
                @endforeach
            </select>
            @error('id_controleur') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-bold py-3 rounded-lg text-lg transition">Soumettre</button>
    </form>
</div>
