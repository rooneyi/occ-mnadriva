@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Demandes assignées</h2>
    @if($demandes->isEmpty())
        <div class="alert alert-info">Aucune demande assignée.</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produit</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($demandes as $demande)
                    <tr>
                        <td>{{ $demande->id_declaration }}</td>
                        <td>
                            @foreach($demande->produits as $produit)
                                {{ $produit->nom_produit }}<br>
                            @endforeach
                        </td>
                        <td>{{ $demande->user->name ?? '-' }}</td>
                        <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                        <td>{{ $demande->statut }}</td>
                        <td>
                            <a href="{{ route('controleur.demande.show', $demande->id_declaration) }}" class="btn btn-primary btn-sm">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
