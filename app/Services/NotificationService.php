<?php

namespace App\Services;

use App\Models\User;
use App\Models\Action;
use App\Models\Declaration;
use App\Notifications\ClientDeclarationSubmitted;
use App\Notifications\ControleurDeclarationNotification;
use App\Notifications\AnalyseSubmitted;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Notifie le client et le contrôleur lors de la soumission d'une déclaration
     */
    public static function notifyDeclarationSubmitted(Declaration $declaration, $produits)
    {
        // Notifier le client
        $client = $declaration->client;
        $client->notify(new ClientDeclarationSubmitted([
            'declaration_id' => $declaration->id_declaration,
            'date_soumission' => $declaration->date_soumission,
            'produits' => $produits->map(function($produit) {
                return [
                    'nom_produit' => $produit->nom,
                    'quantite' => $produit->pivot->quantite
                ];
            })->toArray(),
        ]));

        // Notifier le contrôleur
        $controleur = $declaration->controleur;
        if ($controleur) {
            $controleur->user->notify(new ControleurDeclarationNotification([
                'id' => $declaration->id_declaration,
                'date_soumission' => $declaration->date_soumission->format('d/m/Y H:i'),
                'client' => $client->name,
                'produits' => $produits->map(function($produit) {
                    return [
                        'nom_produit' => $produit->nom,
                        'quantite' => $produit->pivot->quantite
                    ];
                })->toArray(),
            ]));
        }

        // Enregistrer l'action pour le tableau de bord du chef de service
        Action::create([
            'user_id' => $client->id,
            'user_type' => 'client',
            'action' => 'declaration_soumise',
            'description' => 'Nouvelle déclaration soumise par ' . $client->name
        ]);
    }

    /**
     * Notifie le contrôleur lorsqu'un laborantin soumet une analyse
     */
    public static function notifyAnalyseSubmitted($rapport)
    {
        $declaration = $rapport->declaration;
        $controleur = $declaration->controleur;
        
        if ($controleur) {
            $controleur->user->notify(new AnalyseSubmitted($rapport));
        }

        // Enregistrer l'action pour le tableau de bord du chef de service
        Action::create([
            'user_id' => $rapport->laborantin->user_id ?? null,
            'user_type' => 'laborantin',
            'action' => 'analyse_soumise',
            'description' => 'Nouvelle analyse soumise pour la déclaration #' . $declaration->id_declaration
        ]);
    }

    /**
     * Récupère toutes les actions pour le tableau de bord du chef de service
     */
    public static function getDashboardActions($limit = 20)
    {
        return Action::with('user')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function($action) {
                return [
                    'user_name' => $action->user ? $action->user->name : 'Système',
                    'action' => $action->action,
                    'description' => $action->description,
                    'date' => $action->created_at->format('d/m/Y H:i')
                ];
            });
    }
}
