@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    @livewire('client.notifications')
    <div class="mt-6">
        <a href="{{ route('client.dashboard') }}" class="text-blue-600 hover:underline">&larr; Retour au tableau de bord</a>
    </div>
</div>
@endsection
