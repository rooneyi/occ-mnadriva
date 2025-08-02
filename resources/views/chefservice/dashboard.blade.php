@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tableau de bord - Chef de Service</h2>
    <form method="get" class="row g-3 mb-3">
        <div class="col-md-3">
            <input type="text" name="client" value="{{ request('client') }}" class="form-control" placeholder="Nom du client">
        </div>
        <div class="col-md-3">
            <select name="statut" class="form-select">
                <option value="">Tous statuts</option>
                @foreach($statuts as $statut)
                    <option value="{{ $statut }}" @if(request('statut')==$statut) selected @endif>{{ $statut }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" type="submit">Filtrer</button>
            <a href="{{ route('chefservice.dashboard') }}" class="btn btn-outline-secondary">Réinitialiser</a>
        </div>
        <div class="col-md-3 text-end">
            <a href="{{ route('chefservice.export', request()->all()) }}" class="btn btn-success">Exporter Excel</a>
        </div>
    </form>
    <div class="card mt-4">
        <div class="card-header">Vue d'ensemble des dossiers</div>
        <div class="card-body">
            @if($dossiers->isEmpty())
                <div class="alert alert-secondary">Aucun dossier trouvé.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Produit(s)</th>
                            <th>Déclarations</th>
                            <th>Rapports</th>
                            <th>Statut</th>
                            <th>Détail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dossiers as $dossier)
                        <tr>
                            <td>{{ $dossier->id ?? $dossier->id_dossier }}</td>
                            <td>{{ $dossier->client->name ?? '-' }}</td>
                            <td>
                                @foreach($dossier->produits ?? [] as $produit)
                                    <span class="badge bg-info">{{ $produit->designation }}</span>
                                @endforeach
                            </td>
                            <td>{{ $dossier->declarations->count() ?? 0 }}</td>
                            <td>{{ $dossier->rapports->count() ?? 0 }}</td>
                            <td>{{ $dossier->statut ?? '-' }}</td>
                        <td>
                            <a href="{{ route('chefservice.dossier.detail', $dossier->id ?? $dossier->id_dossier) }}" class="btn btn-sm btn-outline-info">Voir détail</a>
                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
