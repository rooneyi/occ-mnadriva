<div>
    <h2 class="text-xl font-bold mb-4">Demandes assignées</h2>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Produit</th>
                <th class="py-2 px-4 border-b">Statut</th>
                <th class="py-2 px-4 border-b">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($demandes as $demande)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $demande->id }}</td>
                    <td class="py-2 px-4 border-b">{{ $demande->produit_nom ?? 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ $demande->statut ?? 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ $demande->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-2 px-4 text-center">Aucune demande assignée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

