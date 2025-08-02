<div>
    <h2 class="text-xl font-bold mb-4">Prendre ou téléverser des photos du produit</h2>
    <form wire:submit.prevent="save">
        <input type="file" wire:model="photos" multiple class="mb-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer les photos</button>
    </form>
    @if(session('success'))
        <div class="text-green-600 mt-2">{{ session('success') }}</div>
    @endif
</div>

