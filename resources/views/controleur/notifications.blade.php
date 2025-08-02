@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Notifications</h2>
    <div class="mt-4">
        @forelse(auth()->user()->notifications as $notification)
            <div class="alert alert-info mb-2">
                <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong><br>
                {{ $notification->data['message'] ?? '' }}<br>
                <small>{{ $notification->created_at->diffForHumans() }}</small>
            </div>
        @empty
            <div class="alert alert-secondary">Aucune notification.</div>
        @endforelse
    </div>
</div>
@endsection
