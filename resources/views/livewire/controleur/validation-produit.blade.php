@extends('layouts.app')

@section('content')
<div>
    <h2 class="text-xl font-bold mb-4">Valider ou rejeter un produit</h2>
    <div class="mb-4">
        <label for="produitSelect" class="block font-semibold mb-1">Sélectionner un produit :</label>
        <select id="produitSelect" wire:model="produitId" class="border rounded p-2 w-full mb-2">
            <option value="">-- Choisir un produit --</option>
            @foreach(App\Models\Produit::all() as $p)
                <option value="{{ $p->id_produit }}">{{ $p->nom_produit }} ({{ $p->id_produit }})</option>
            @endforeach
        </select>
    </div>
    @php
        if($p->$produitId) {
            $produit = App\Models\Produit::find($produitId);
        }
    @endphp
    @if($produit)
        <div class="mb-4">
            <strong>Nom :</strong> {{ $p->nom_produit }}<br>
            <strong>Description :</strong> {{ $p->description }}<br>
            <strong>Date fabrication :</strong> {{ $p->date_fabrication }}<br>
            <strong>Date expiration :</strong> {{ $p->date_expiration }}<br>
            <strong>Mois restants avant expiration :</strong> {{ $moisRestants ?? 'N/A' }}<br>
            <strong>Statut automatique :</strong> <span class="font-bold {{ $statutAuto == 'passable' ? 'text-green-600' : 'text-red-600' }}">{{ $statutAuto ?? 'N/A' }}</span><br>
            <strong>Statut actuel :</strong> {{ $produit->statut ?? 'N/A' }}
        </div>
        <form wire:submit.prevent="valider" class="inline-block mr-2">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Valider</button>
        </form>
        <form wire:submit.prevent="rejeter" class="inline-block">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Rejeter</button>
        </form>
        @if(session('success'))
            <div class="text-green-600 mt-2">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="text-red-600 mt-2">{{ session('error') }}</div>
        @endif
    @else
        <div class="text-gray-500">Aucun produit sélectionné.</div>
    @endif
</div>
@endsection
