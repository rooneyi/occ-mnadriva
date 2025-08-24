@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold text-blue-900 mb-6">Créer un utilisateur</h2>
    @if(session('success'))
        <div class="mb-4 p-4 rounded bg-green-100 border border-green-400 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    <form method="POST" action="{{ route('chefservice.user.store') }}">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-blue-900 font-semibold mb-2">Nom</label>
            <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-blue-900 font-semibold mb-2">Email</label>
            <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label for="role" class="block text-blue-900 font-semibold mb-2">Rôle</label>
            <select name="role" id="role" class="w-full border rounded px-3 py-2" required>
                <option value="client">Client</option>
                <option value="controleur">Contrôleur</option>
                <option value="chef_service">Chef de service</option>
                <option value="laborantin">Laborantin</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-blue-900 font-semibold mb-2">Mot de passe</label>
            <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2" required>
        </div>
        <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded font-bold hover:bg-yellow-500 hover:text-blue-900 transition">Créer</button>
    </form>
</div>
@endsection