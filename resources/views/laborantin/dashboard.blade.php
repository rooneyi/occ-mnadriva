@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row gap-8 max-w-6xl mx-auto mt-10">
    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-blue-50 rounded-lg shadow p-6 mb-6 md:mb-0">
        <h3 class="text-lg font-bold text-blue-900 mb-4">Menu Laborantin</h3>
        <ul class="space-y-3">
            <li>
                <a href="{{ route('laborantin.dashboard') }}" class="block px-4 py-2 rounded text-blue-900 font-semibold hover:bg-yellow-100 transition">Tableau de bord</a>
            </li>
            <li>
                <a href="{{ route('laborantin.historique') }}" class="block px-4 py-2 rounded text-blue-900 font-semibold hover:bg-yellow-100 transition">Historique des analyses</a>
            </li>
            <li>
                <a href="{{ route('laborantin.dashboard') }}#analyses" class="block px-4 py-2 rounded text-blue-900 font-semibold hover:bg-yellow-100 transition">Analyses à réaliser</a>
            </li>
            <!-- Ajoute d'autres actions si besoin -->
        </ul>
    </aside>
    <!-- Main content -->
    <div class="flex-1">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-blue-900 mb-4 sm:mb-0">Tableau de bord du Laborantin</h2>
            <a href="{{ route('laborantin.historique') }}" class="px-4 py-2 rounded bg-blue-900 text-white font-semibold hover:bg-yellow-500 hover:text-blue-900 transition">Historique des analyses</a>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-blue-900 mb-4" id="analyses">Analyses à réaliser</h3>
            @if($analyses->isEmpty())
                <div class="bg-yellow-50 text-blue-900 p-4 rounded mb-4">Aucune analyse à effectuer.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-blue-200">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">ID</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Déclaration</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Produit</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-blue-100">
                            @foreach($analyses as $analyse)
                            <tr>
                                <td class="px-4 py-2">{{ $analyse->id ?? $analyse->id_rapport }}</td>
                                <td class="px-4 py-2">{{ $analyse->declaration->id ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $analyse->designation_produit ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('laborantin.analyse.form', $analyse->id ?? $analyse->id_rapport) }}" class="px-3 py-1 rounded bg-yellow-500 text-blue-900 font-bold hover:bg-blue-900 hover:text-white transition">Remplir</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
