<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

// Authentification et inscription client (Livewire)
Route::get('/client/register', function() {
    return view('client.register');
})->name('client.register');


// Tableau de bord client
Route::get('/client/dashboard', [App\Http\Controllers\ClientController::class, 'dashboard'])->name('client.dashboard');

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
    Route::match(['get', 'post'], 'controleur/produit/{id_produit}/scan', [App\Http\Controllers\ControleurController::class, 'scanProduit'])->name('controleur.produit.scan');
    Route::get('/controleur/notifications', [App\Http\Controllers\ControleurController::class, 'notifications'])->name('controleur.notifications');
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
    Route::get('/laborantin/analyse/{declarationId}', [App\Http\Controllers\LaborantinController::class, 'showAnalyseForm'])->name('laborantin.analyse.form');
    Route::post('/laborantin/analyse/{declarationId}/submit', [App\Http\Controllers\LaborantinController::class, 'submitAnalyse'])->name('laborantin.analyse.submit');
    Route::get('/laborantin/historique', [App\Http\Controllers\LaborantinController::class, 'historique'])->name('laborantin.historique');
});

// Ajout de la route pour mot de passe oublié
Route::get('/forgot-password', function() {
    return view('auth.forgot-password');
})->name('password.request');

require __DIR__.'/auth.php';
