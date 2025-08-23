<div>
    <h2 class="text-xl font-bold mb-4">Valider ou rejeter un produit</h2>
    <div class="mb-4">
        @if(session('success'))
            <div class="text-green-600 bg-green-200 border rounded p-2 mt-2">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="text-red-600 bg-red-200 border rounded p-2 mt-2">{{ session('error') }}</div>
        @endif
        @if(session('commentaire_success'))
            <div class="text-green-600 bg-green-200 border rounded p-2 mb-2">{{ session('commentaire_success') }}</div>
        @endif
        <label for="produitSelect" class="block font-semibold mb-1">Sélectionner un produit :</label>
        <select id="produitSelect" wire:model.lazy="produitId" class="border rounded p-2 w-full mb-2">
            <option value="">-- Choisir un produit --</option>
            @foreach(App\Models\Produit::all() as $p)
                <option value="{{ $p->id_produit }}">{{ $p->nom_produit }} ({{ $p->id_produit }})</option>
            @endforeach
        </select>
    </div>
    @if($produit)
        <div class="mb-4">
            <strong>Nom :</strong> {{ $produit->nom_produit }}<br>
            <strong>Description :</strong> {{ $produit->description }}<br>
            <strong>Date fabrication :</strong> {{ $produit->date_fabrication }}<br>
            <strong>Date expiration :</strong> {{ $produit->date_expiration }}<br>
            <strong>Mois restants avant expiration :</strong> {{ $moisRestants ?? 'N/A' }}<br>
            <strong>Statut automatique :</strong> <span class="font-bold {{ $statutAuto == 'passable' ? 'text-green-600' : 'text-red-600' }}">{{ $statutAuto ?? 'N/A' }}</span><br>
            <strong>Statut actuel :</strong> {{ $produit->statut ?? 'N/A' }}
        </div>
        <div class="mb-4">
            <h3 class="text-lg font-bold text-blue-900 mb-2">Déclaration associée</h3>
            @if($declaration)
                <div class="p-2 bg-gray-100 rounded">
                    <strong>Numéro de déclaration :</strong> {{ $declaration->id_declaration }}<br>
                    <strong>Client :</strong> {{ $declaration->client->name ?? '-' }}<br>
                    <strong>Date de soumission :</strong> {{ $declaration->date_soumission }}<br>
                    <strong>Statut :</strong> {{ $declaration->statut }}<br>
                </div>
            @else
                <span class="text-gray-500">Aucune déclaration associée.</span>
            @endif
        </div>
        <div class="mb-4">
            <h3 class="text-lg font-bold text-blue-900 mb-2">Rapport du laborantin</h3>
            @if($rapportLaborantin)
                <div class="p-2 bg-gray-100 rounded">
                    <strong>Désignation produit :</strong> {{ $rapportLaborantin->designation_produit }}<br>
                    <strong>Laborantin :</strong> {{ $rapportLaborantin->laborantin->name ?? '-' }}<br>
                    <strong>Conclusion :</strong> {{ $rapportLaborantin->conclusion }}<br>
                    <strong>Statut du rapport :</strong> {{ $rapportLaborantin->statut }}<br>
                </div>
            @else
                <span class="text-gray-500">Aucun rapport du laborantin disponible pour ce produit.</span>
            @endif
        </div>
        @if(!$rapportSoumis)
            <div class="text-yellow-700 bg-yellow-100 border rounded p-2 mb-2">
                Le rapport d'analyse du laborantin n'a pas encore été soumis. Vous ne pouvez pas valider ce produit tant que le rapport n'est pas disponible.
            </div>
        @endif
        <form wire:submit.prevent="valider" class="inline-block mr-2">
            <button type="submit"
                class="px-4 py-2 rounded text-white @if(!$rapportSoumis || empty($produit->date_fabrication) || empty($produit->date_expiration) || $statutAuto !== 'passable') bg-gray-400 cursor-not-allowed @else bg-green-600 @endif"
                @if(!$rapportSoumis || empty($produit->date_fabrication) || empty($produit->date_expiration) || $statutAuto !== 'passable') disabled @endif>
                Valider
            </button>
        </form>
        <form wire:submit.prevent="rejeter" class="inline-block">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Rejeter</button>
        </form>


    @else
        <div class="text-gray-500">Aucun produit sélectionné.</div>
    @endif
</div>
