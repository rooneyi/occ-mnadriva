<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dossier;
use App\Models\Action;
use App\Services\NotificationService;

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
        // Récupérer l'historique des actions via le service de notification
        $actions = NotificationService::getDashboardActions(50);
        
        // Récupérer les statistiques par type d'utilisateur
        $userTypes = Action::select('user_type')
            ->selectRaw('count(*) as count')
            ->groupBy('user_type')
            ->pluck('count', 'user_type');
            
        // Récupérer les actions récentes par type
        $recentActions = Action::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function($action) {
                return [
                    'user' => $action->user ? $action->user->name : 'Système',
                    'type' => $action->user_type,
                    'action' => $action->action,
                    'description' => $action->description,
                    'date' => $action->created_at->format('d/m/Y H:i')
                ];
            });
        // Statistiques de trafic : nombre de dossiers créés par jour (30 derniers jours)
        $trafficStats = Dossier::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        // Dernières déclarations soumises (visibilité directe)
        $recentDeclarations = \App\Models\Declaration::with(['client', 'produits'])
            ->latest()
            ->limit(20)
            ->get();

        return view('chefservice.dashboard', [
            'dossiers' => $dossiers,
            'statuts' => $statuts,
            'actions' => $actions,
            'userTypes' => $userTypes,
            'recentActions' => $recentActions,
            'trafficStats' => $trafficStats,
            'recentDeclarations' => $recentDeclarations
        ]);
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

    // Affiche le formulaire de création d'utilisateur
    public function createUser()
    {
        return view('chefservice.create-user');
    }

    // Traite la création d'un nouvel utilisateur
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:client,controleur,chef_service,laborantin',
            'password' => 'required|string|min:6',
        ]);

        $user = new \App\Models\User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('chefservice.user.create')->with('success', 'Utilisateur créé avec succès.');
    }
}
