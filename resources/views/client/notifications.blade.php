@extends('components.layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Notifications</h1>
    <div class="bg-white rounded shadow p-6">
        <p class="text-gray-700">Aucune notification pour le moment.</p>
    </div>
    <div class="mt-6">
        <a href="{{ route('client.dashboard') }}" class="text-blue-600 hover:underline">&larr; Retour au tableau de bord</a>
    </div>
</div>
@endsection
