<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dossier;

class ChefServiceController extends Controller
{
    // Vue d'ensemble de tous les dossiers
    public function dashboard(Request $request)
    {
        $query = Dossier::with(['client', 'produits', 'declarations', 'rapports']);
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('client')) {
            $query->whereHas('client', function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->client.'%');
            });
        }
        $dossiers = $query->get();
        $statuts = Dossier::distinct()->pluck('statut');
        return view('chefservice.dashboard', compact('dossiers', 'statuts'));
    }

    public function exportExcel(Request $request)
    {
        $query = Dossier::with(['client', 'produits', 'declarations', 'rapports']);
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('client')) {
            $query->whereHas('client', function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->client.'%');
            });
        }
        $dossiers = $query->get();
        $exportData = [];
        foreach ($dossiers as $dossier) {
            $exportData[] = [
                'ID' => $dossier->id ?? $dossier->id_dossier,
                'Client' => $dossier->client->name ?? '-',
                'Produits' => $dossier->produits->pluck('designation')->implode(', '),
                'Nb Déclarations' => $dossier->declarations->count() ?? 0,
                'Nb Rapports' => $dossier->rapports->count() ?? 0,
                'Statut' => $dossier->statut ?? '-',
            ];
        }
        // Générer fichier Excel
        $filename = 'dossiers_chefservice_'.date('Ymd_His').'.csv';
        $handle = fopen('php://memory', 'w');
        fputcsv($handle, array_keys($exportData[0] ?? []));
        foreach ($exportData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];
        return response()->stream(function() use ($handle) {
            fpassthru($handle);
        }, 200, $headers);
    }

    public function show($id)
    {
        $dossier = Dossier::with(['client', 'produits', 'declarations', 'rapports'])->findOrFail($id);
        return view('chefservice.dossier-detail', compact('dossier'));
    }
}
