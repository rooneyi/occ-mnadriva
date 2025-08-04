@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row gap-8 max-w-6xl mx-auto mt-10">

    <!-- Main content -->
    <div class="flex-1">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('error') }}</div>
        @endif
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-blue-900 mb-4 sm:mb-0">Tableau de bord du Laborantin</h2>
            <a href="{{ route('laborantin.historique') }}" class="px-4 py-2 rounded bg-blue-900 text-white font-semibold hover:bg-yellow-500 hover:text-blue-900 transition">Historique des analyses</a>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-blue-900 mb-4" id="analyses">Mes analyses soumises</h3>
            @if($analyses->isEmpty())
                <div class="bg-yellow-50 text-blue-900 p-4 rounded mb-4">Aucune analyse soumise.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-blue-200">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">ID</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Déclaration</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Produit</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Résultat</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Conclusion</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-blue-100">
                            @foreach($analyses as $analyse)
                            <tr>
                                <td class="px-4 py-2">{{ $analyse->id_rapport ?? $analyse->id }}</td>
                                <td class="px-4 py-2">{{ $analyse->id_declaration ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $analyse->designation_produit ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $analyse->resultat_analyse ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $analyse->conclusion ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $analyse->created_at ? $analyse->created_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="flex justify-end mb-4">
            <form action="{{ route('laborantin.genererRapportAuto') }}" method="POST">
                @csrf
                <select name="id_produit" class="px-4 py-2 rounded border mr-2" required>
                    <option value="">Sélectionner un produit</option>
                    @foreach(App\Models\Produit::all() as $produit)
                        <option value="{{ $produit->id_produit ?? $produit->id }}">
                            {{ $produit->nom_produit ?? $produit->designation ?? $produit->designation_produit ?? 'Produit' }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 rounded bg-green-700 text-white font-semibold hover:bg-green-500 transition">
                    Générer automatiquement un rapport d’analyse
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
