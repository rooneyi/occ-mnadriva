<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Declaration;

class Dossier extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_dossier';
    protected $fillable = [
        'nom_dossier',
    ];
    public function declarations()
    {
        return $this->belongsToMany(Declaration::class, 'dossier_declaration', 'id_dossier', 'id_declaration');
    }
    public function rapports()
    {
        return $this->belongsToMany(RapportAnalyse::class, 'dossier_rapport_analyse', 'id_dossier', 'id_rapport');
    }
}

