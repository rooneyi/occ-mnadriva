@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <h2 class="text-3xl font-bold text-blue-900 mb-6">Historique des analyses</h2>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-blue-900 mb-4">Mes rapports d'analyse</h3>
        @if($analyses->isEmpty())
            <div class="bg-yellow-50 text-blue-900 p-4 rounded mb-4">Aucun rapport d'analyse trouvé.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-blue-200">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">ID</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Produit</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Résultats</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Conclusion</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">Date</th>
                            <th class="px-4 py-2 text-left text-sm font-bold text-blue-900">PDF</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-blue-100">
                        @foreach($analyses as $analyse)
                        <tr>
                            <td class="px-4 py-2">{{ $analyse->id ?? $analyse->id_rapport }}</td>
                            <td class="px-4 py-2">{{ $analyse->designation_produit ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $analyse->resultats ?? $analyse->resultat_analyse ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $analyse->conclusion ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $analyse->created_at ? $analyse->created_at->format('d/m/Y') : '-' }}</td>
                            <td class="px-4 py-2">
                                @if(isset($analyse->pdf_path) && $analyse->statut == 'valide')
                                    <a href="{{ asset('storage/' . $analyse->pdf_path) }}" class="px-2 py-1 rounded bg-blue-900 text-white text-xs font-semibold hover:bg-yellow-500 hover:text-blue-900 transition" target="_blank">Télécharger PDF</a>
                                @else
                                    <span class="text-gray-400 text-xs">Non disponible</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
