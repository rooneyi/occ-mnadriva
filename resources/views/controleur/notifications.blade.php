@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    @livewire('controleur.notifications')
    <div class="mt-6">
        <a href="{{ route('controleur.dashboard') }}" class="text-blue-600 hover:underline">&larr; Retour au tableau de bord</a>
    </div>
</div>
@endsection
