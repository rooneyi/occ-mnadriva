@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
    <h2>Tableau de bord du Contrôleur</h2>
    <a href="{{ route('controleur.notifications') }}" class="btn btn-outline-primary position-relative">
        Notifications
        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
        @if($unread > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unread }}
            </span>
        @endif
    </a>
</div>
    <div class="card mt-4">
        <div class="card-header">Demandes assignées</div>
        <div class="card-body">
            @if($demandes->isEmpty())
                <p>Aucune demande assignée.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demandes as $demande)
                        <tr>
                            <td>{{ $demande->id }}</td>
                            <td>{{ $demande->client->name ?? '-' }}</td>
                            <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                            <td>{{ $demande->statut }}</td>
                            <td>
                                <a href="{{ route('controleur.demande.show', $demande->id) }}" class="btn btn-primary btn-sm">Voir</a>
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
