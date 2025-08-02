@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Historique des analyses</h2>
    <div class="card mt-4">
        <div class="card-header">Mes rapports d'analyse</div>
        <div class="card-body">
            @if($analyses->isEmpty())
                <div class="alert alert-secondary">Aucun rapport d'analyse trouvé.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produit</th>
                            <th>Résultats</th>
                            <th>Conclusion</th>
                            <th>Date</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($analyses as $analyse)
                        <tr>
                            <td>{{ $analyse->id ?? $analyse->id_rapport }}</td>
                            <td>{{ $analyse->designation_produit ?? '-' }}</td>
                            <td>{{ $analyse->resultats ?? $analyse->resultat_analyse ?? '-' }}</td>
                            <td>{{ $analyse->conclusion ?? '-' }}</td>
                            <td>{{ $analyse->created_at ? $analyse->created_at->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if(isset($analyse->pdf_path))
                                <a href="{{ asset('storage/' . $analyse->pdf_path) }}" class="btn btn-sm btn-outline-primary" target="_blank">Télécharger PDF</a>
                            @else
                                -
                            @endif
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
