<div>
    <h2>Soumettre une déclaration</h2>
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="submit">
        <div class="mb-3">
            <label>Produits à déclarer</label>
            <select wire:model="selectedProduits" multiple class="form-control">
                @foreach($produits as $produit)
                    <option value="{{ $produit->id_produit }}">{{ $produit->nom }}</option>
                @endforeach
            </select>
            @error('selectedProduits') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label>Unité</label>
            <input type="text" wire:model="unite" class="form-control">
            @error('unite') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label>Numéro d'import</label>
            <input type="text" wire:model="numero_import" class="form-control">
            @error('numero_import') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label>Document (licence)</label>
            <input type="file" wire:model="document" class="form-control">
            @error('document') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>
</div>

