<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Authentification et inscription client (Livewire)
Route::get('/client/register', function() {
    return view('client.register');
})->name('client.register');

Route::get('/client/login', function() {
    return view('client.login');
})->name('client.login');

// Redirection après login (Livewire ou contrôleur)
Route::post('/client/login', function() {
    return redirect()->route('client.dashboard');
})->name('client.login.submit');

// Tableau de bord client
Route::get('/client/dashboard', [App\Http\Controllers\ClientController::class, 'dashboard'])->name('client.dashboard');

// Formulaire de déclaration (Livewire)
Route::get('/client/declaration', function() {
    return view('client.declaration');
})->name('client.declaration');

// Téléchargement rapport d'analyse
Route::get('/client/rapport/{rapportId}/download', [App\Http\Controllers\ClientController::class, 'downloadRapport'])->name('client.rapport.download');

require __DIR__.'/auth.php';
