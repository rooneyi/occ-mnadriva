@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Détail du dossier</h2>
    <div class="card mt-4">
        <div class="card-header">Informations principales</div>
        <div class="card-body">
            <p><strong>ID :</strong> {{ $dossier->id ?? $dossier->id_dossier }}</p>
            <p><strong>Client :</strong> {{ $dossier->client->name ?? '-' }}</p>
            <p><strong>Statut :</strong> {{ $dossier->statut ?? '-' }}</p>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Produits</div>
        <div class="card-body">
            @foreach($dossier->produits ?? [] as $produit)
                <span class="badge bg-info">{{ $produit->designation }}</span>
            @endforeach
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Déclarations</div>
        <div class="card-body">
            @if($dossier->declarations->isEmpty())
                <div class="alert alert-secondary">Aucune déclaration.</div>
            @else
                <ul>
                    @foreach($dossier->declarations as $decl)
                        <li>ID: {{ $decl->id ?? $decl->id_declaration }} | Produit: {{ $decl->designation_produit }} | Quantité: {{ $decl->quantiter }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Rapports d'analyse</div>
        <div class="card-body">
            @if($dossier->rapports->isEmpty())
                <div class="alert alert-secondary">Aucun rapport.</div>
            @else
                <ul>
                    @foreach($dossier->rapports as $rapport)
                        <li>ID: {{ $rapport->id_rapport }} | Produit: {{ $rapport->designation_produit }} | Conclusion: {{ $rapport->conclusion }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    <a href="{{ route('chefservice.dashboard') }}" class="btn btn-secondary mt-4">Retour au tableau de bord</a>
</div>
@endsection
