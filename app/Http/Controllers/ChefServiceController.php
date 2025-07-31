<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dossier;

class ChefServiceController extends Controller
{
    // Vue d'ensemble de tous les dossiers
    public function tableauDeBord()
    {
        $dossiers = Dossier::with(['declarations', 'rapports'])->get();
        return view('chefservice.dashboard', compact('dossiers'));
    }
}

