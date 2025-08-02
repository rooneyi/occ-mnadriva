<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Toutes les routes Volt/Livewire d'authentification sont désactivées
    // Utilisez uniquement les routes personnalisées dans web.php
});

Route::middleware('auth')->group(function () {
    // Route de vérification email (optionnel)
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
});

// Route de déconnexion (optionnel, à adapter si besoin)
Route::post('logout', \App\Livewire\Actions\Logout::class)
    ->name('logout');
