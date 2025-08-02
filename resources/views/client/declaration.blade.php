@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h2 class="text-2xl font-bold text-blue-900 mb-6">Déclaration client</h2>
    <p class="mb-4 text-blue-800">Remplissez le formulaire pour déclarer votre dossier ou vos produits.</p>
    @livewire('client.declaration-form')
</div>
@endsection
