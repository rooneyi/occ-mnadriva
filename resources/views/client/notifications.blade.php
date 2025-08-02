@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Notifications</h1>
    <div class="bg-white rounded shadow p-6">
        @php
            $notifications = Auth::user()->notifications;
        @endphp
        @if($notifications->isEmpty())
            <p class="text-gray-700">Aucune notification pour le moment.</p>
        @else
            <ul>
                @foreach($notifications as $notification)
                    <li class="mb-4 border-b pb-2">
                        {{ $notification->data['message'] ?? $notification->data['body'] ?? 'Nouvelle notification.' }}
                        <span class="text-xs text-gray-500 float-right">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="mt-6">
        <a href="{{ route('client.dashboard') }}" class="text-blue-600 hover:underline">&larr; Retour au tableau de bord</a>
    </div>
</div>
@endsection
