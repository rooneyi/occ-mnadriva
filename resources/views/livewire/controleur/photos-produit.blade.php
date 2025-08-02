<div>
    <h2 class="text-xl font-bold mb-4">Prendre ou téléverser des photos du produit</h2>
    <form wire:submit.prevent="save">
        @if(session('success'))
            <div class="text-green-600 bg-green-200 border rounded p-2 m-2">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="text-red-600 bg-red-200 border rounded p-2 m-2">{{ session('error') }}</div>
        @endif
        <select wire:model="produitId" class="w-full border rounded p-2 mb-2">
            @foreach($produits as $produit)
                <option value="{{ $produit->id_produit }}">{{ $produit->nom_produit }}</option>
            @endforeach
        </select>
        <input type="file" wire:model="photos" multiple class="mb-2" class="border rounded p-2 w-full">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer les photos</button>
    </form>


    @if($photos && count($photos))
        <div class="mb-4">
            <h3 class="font-bold mb-2">Photos du produit :</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($photos as $photo)
                    <img src="{{ asset('storage/' . $photo->livewire-tmpfile()) }}" alt="Photo produit" class="w-32 h-32 object-cover rounded border">
                @endforeach
            </div>
        </div>
    @endif
</div>
