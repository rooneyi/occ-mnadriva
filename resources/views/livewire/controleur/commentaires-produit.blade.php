<div>
    <h2 class="text-xl font-bold mb-4">Commentaires sur le produit</h2>
    <form wire:submit.prevent="ajouterCommentaire" class="mb-4">
        @if(session('success'))
            <div class="text-green-600 bg-green-200 border rounded p-2 mb-2">{{ session('success') }}</div>
        @endif
        <select wire:model.lazy="produitId" class="w-full border rounded p-2 mb-2">
            @foreach($produits as $produit)
                <option value="{{ $produit->id_produit }}">{{ $produit->nom_produit }}</option>
            @endforeach
        </select>
        <textarea wire:model.defer="commentaire" class="w-full border rounded p-2 mb-2" placeholder="Ajouter un commentaire..." @if(!$produitId) disabled @endif></textarea>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded @if(!$produitId) bg-gray-400 cursor-not-allowed @endif" @if(!$produitId) disabled @endif>Envoyer</button>
    </form>

    <ul>
        @forelse($commentaires as $c)
            <li class="border-b py-2">
                <strong>{{ $c->user->name ?? 'Utilisateur' }} :</strong> {{ $c->contenu }}
                <span class="text-xs text-gray-500">({{ $c->created_at->format('d/m/Y H:i') }})</span>
            </li>
        @empty
            <li class="text-gray-500">Aucun commentaire pour ce produit.</li>
        @endforelse
    </ul>
</div>
