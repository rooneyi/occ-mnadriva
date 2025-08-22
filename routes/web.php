
<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');
// Formulaire d'analyse laboratoire prérempli
Route::get('/laborantin/analyse', [App\Http\Controllers\LaborantinController::class, 'showAnalyseForm'])->name('laborantin.analyse.form');

// Authentification et inscription client (Livewire)
Route::get('/client/register', function() {
    return view('client.register');
})->name('client.register');


// Tableau de bord client
Route::view('/client/dashboard', 'client.dashboard')->name('client.dashboard');

// Formulaire de déclaration (Livewire)
Route::get('/client/declaration', function() {
    return view('client.declaration');
})->middleware('auth')->name('client.declaration');

// Notifications client (factice)
Route::get('/client/notifications', function() {
    return view('client.notifications');
})->name('client.notifications');

// Dashboard Contrôleur
Route::middleware(['auth'])->group(function () {
    Route::get('/controleur/dashboard', [App\Http\Controllers\ControleurController::class, 'dashboard'])->name('controleur.dashboard');
    Route::view('/controleur/produits/create', 'controleur.produits.create')->name('controleur.produits.create');
    Route::get('/controleur/notifications', [App\Http\Controllers\ControleurController::class, 'notifications'])->name('controleur.notifications');
    // Pages Livewire Controleur (format simplifié)
    Route::view('/controleur/produits/photos', 'controleur_photos_produit')->name('controleur.produits.photos');
    Route::view('/controleur/produits/commentaires', 'controleur_commentaires_produit')->name('controleur.produits.commentaires');
    Route::view('/controleur/produits/validation', 'controleur_validation_produit')->name('controleur.produits.validation');
    Route::view('/controleur/demandes', 'controleur_demandes')->name('controleur.demandes');
    Route::get('/controleur/declaration/{id}', [App\Http\Controllers\ControleurController::class, 'showDeclaration'])->name('controleur.declaration.detail');
    Route::get('/controleur/demande/{id}', [App\Http\Controllers\ControleurController::class, 'showDemande'])->name('controleur.demande.show');
    Route::get('/controleur/produit/{id}/rejeter', [App\Http\Controllers\ControleurController::class, 'rejeterProduit'])->name('controleur.produit.rejeter');
});
// Login universel multi-rôles
Route::get('/login', [App\Http\Controllers\UniversalLoginController::class, 'showForm'])->name('login');
Route::post('/login', [App\Http\Controllers\UniversalLoginController::class, 'login'])->name('login.submit');


// Téléchargement rapport d'analyse
Route::get('/client/rapport/{rapportId}/download', [App\Http\Controllers\ClientController::class, 'downloadRapport'])->name('client.rapport.download');

// Chef de service
Route::middleware(['auth'])->group(function () {
    Route::get('/chefservice/dashboard', [App\Http\Controllers\ChefServiceController::class, 'dashboard'])->name('chefservice.dashboard');
    Route::get('/chefservice/export', [App\Http\Controllers\ChefServiceController::class, 'exportExcel'])->name('chefservice.export');
    Route::get('/chefservice/dossier/{id}', [App\Http\Controllers\ChefServiceController::class, 'show'])->name('chefservice.dossier.detail');
});

// Laborantin
Route::middleware(['auth'])->group(function () {
    Route::get('/laborantin/dashboard', [App\Http\Controllers\LaborantinController::class, 'dashboard'])->name('laborantin.dashboard');
    Route::get('/laborantin/analyse', [App\Http\Controllers\LaborantinController::class, 'showAnalyseForm'])->name('laborantin.analyse.form');
    Route::post('/laborantin/analyse', [App\Http\Controllers\LaborantinController::class, 'storeRapport'])->name('laborantin.rapport.store');
    Route::get('/laborantin/historique', [App\Http\Controllers\LaborantinController::class, 'historique'])->name('laborantin.historique');
    Route::post('/laborantin/generer-rapport-auto', [App\Http\Controllers\LaborantinController::class, 'genererRapportAuto'])->name('laborantin.genererRapportAuto');
});

// Ajout de la route pour mot de passe oublié
Route::get('/forgot-password', function() {
    return view('auth.forgot-password');
})->name('password.request');

// Page Commentaire
Route::get('/commentaire', function() {
    return view('commentaire');
})->name('commentaire');

// Détails déclaration client
Route::get('/client/declaration/{id}', [App\Http\Controllers\ClientController::class, 'showDeclaration'])->middleware('auth')->name('client.declaration.detail');

// Extraction OCR des dates depuis une photo (contrôleur)
Route::post('/extract-dates', [App\Http\Controllers\ControleurController::class, 'extractDates'])->name('extract.dates');


require __DIR__.'/auth.php';
