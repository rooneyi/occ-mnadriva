<?php

namespace App\Services;

use App\Models\Produit;
use App\Models\Declaration;
use App\Models\RapportAnalyse;
use Carbon\Carbon;

class ValidationService
{
    /**
     * Valide un produit selon les règles métier
     */
    public static function validerProduit(Produit $produit, array $rapportData = null): array
    {
        $result = [
            'valide' => true,
            'raison' => '',
            'details' => []
        ];

        // 1. Vérifier la date d'expiration
        if ($produit->date_expiration) {
            $expiration = Carbon::parse($produit->date_expiration);
            $moisRestants = now()->diffInMonths($expiration, false);
            
            if ($moisRestants < 0) {
                $result['valide'] = false;
                $result['details'][] = 'Le produit est expiré depuis ' . abs($moisRestants) . ' mois';
            } elseif ($moisRestants < 3) {
                $result['details'][] = 'Le produit expire bientôt (dans ' . $moisRestants . ' mois)';
            }
        }

        // 2. Vérifier le rapport d'analyse s'il est fourni
        if ($rapportData) {
            if ($rapportData['conclusion'] !== 'conforme') {
                $result['valide'] = false;
                $result['details'][] = 'Le rapport d\'analyse n\'est pas conforme';
            }

            // Vérifier la température de stockage si applicable
            if (isset($rapportData['temperature_stockage']) && $rapportData['temperature_stockage'] > 25) {
                $result['details'][] = 'Attention: Température de stockage élevée';
            }
        }

        // 3. Vérifier les quantités
        if ($produit->quantite <= 0) {
            $result['valide'] = false;
            $result['details'][] = 'La quantité doit être supérieure à zéro';
        }

        // 4. Vérifier l'emballage
        if ($produit->etat_emballage !== 'intact') {
            $result['valide'] = false;
            $result['details'][] = 'L\'emballage n\'est pas intact';
        }

        if (!$result['valide']) {
            $result['raison'] = implode(', ', $result['details']);
        }

        return $result;
    }

    /**
     * Valide une déclaration complète
     */
    public static function validerDeclaration(Declaration $declaration): array
    {
        $result = [
            'valide' => true,
            'produits' => []
        ];

        // Valider chaque produit de la déclaration
        foreach ($declaration->produits as $produit) {
            $rapport = $produit->rapports->first();
            $rapportData = $rapport ? [
                'conclusion' => $rapport->conclusion,
                'temperature_stockage' => $rapport->temperature_stockage ?? null
            ] : null;

            $validation = self::validerProduit($produit, $rapportData);
            $result['produits'][$produit->id] = $validation;

            if (!$validation['valide']) {
                $result['valide'] = false;
            }
        }

        return $result;
    }

    /**
     * Applique la décision de validation/rejet
     */
    public static function appliquerDecision(Declaration $declaration, bool $estValide, string $commentaire = ''): void
    {
        $declaration->statut = $estValide ? 'valide' : 'rejete';
        $declaration->commentaire_validation = $commentaire;
        $declaration->date_validation = now();
        $declaration->save();

        // Mettre à jour le statut des produits associés
        $declaration->produits()->update([
            'statut' => $estValide ? 'valide' : 'rejete'
        ]);

        // Enregistrer l'action
        Action::create([
            'user_id' => auth()->id(),
            'user_type' => 'controleur',
            'action' => $estValide ? 'validation_declaration' : 'rejet_declaration',
            'description' => sprintf(
                'Déclaration %s par le contrôleur. Commentaire: %s',
                $estValide ? 'validée' : 'rejetée',
                $commentaire
            )
        ]);

        // Notifier le client
        $declaration->client->notify(new \App\Notifications\DeclarationValideeNotification([
            'declaration_id' => $declaration->id,
            'est_valide' => $estValide,
            'commentaire' => $commentaire,
            'date_validation' => now()->format('d/m/Y H:i')
        ]));
    }
}
