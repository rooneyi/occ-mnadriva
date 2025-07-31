<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laborantin extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_laborantin';
    protected $fillable = [
        'id_utilisateur',
    ];
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur', 'id_utilisateur');
    }
    // Fonctionnalités Laborantin
    public function remplirResultatAnalyse($rapportId, $data)
    {
        // Remplit les résultats d'analyse pour un rapport donné
        $rapport = RapportAnalyse::find($rapportId);
        if ($rapport && $rapport->id_laborantin == $this->id_laborantin) {
            $rapport->fill($data);
            $rapport->save();
            return $rapport;
        }
        return null;
    }

    public function genererRapport($declarationId, $data)
    {
        // Génère un nouveau rapport d'analyse pour une déclaration
        return RapportAnalyse::create(array_merge($data, [
            'id_laborantin' => $this->id_laborantin,
            'id_declaration' => $declarationId
        ]));
    }

    public function soumettreRapport($rapportId)
    {
        // Change le statut du rapport à 'soumis' (à adapter selon la structure)
        $rapport = RapportAnalyse::find($rapportId);
        if ($rapport && $rapport->id_laborantin == $this->id_laborantin) {
            $rapport->statut = 'soumis';
            $rapport->save();
            return $rapport;
        }
        return null;
    }

    public function consulterHistorique()
    {
        // Retourne l'historique des rapports réalisés par ce laborantin
        return RapportAnalyse::where('id_laborantin', $this->id_laborantin)->get();
    }
}
