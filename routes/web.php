<?php

use App\Http\Controllers\ProjetController;
use App\Http\Controllers\TacheController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ChefDeProjetController;
use App\Http\Controllers\ResponsableMaterielController;
use App\Http\Controllers\ResponsableRHController;

// Home Route
Route::get('/', function () {
    return view('welcome'); // Or your main dashboard view
});

// Projet Routes
Route::resource('projets', ProjetController::class);

// Tache Routes
Route::resource('taches', TacheController::class);

// Equipement Routes
Route::resource('equipements', EquipementController::class);

// Employe Routes
Route::resource('employes', EmployeController::class);

// ChefDeProjet Routes
Route::get('chefsdeprojet', [ChefDeProjetController::class, 'index'])->name('chefsdeprojet.index');
Route::get('chefsdeprojet/{id}', [ChefDeProjetController::class, 'show'])->name('chefsdeprojet.show');

// ResponsableMateriel Routes
Route::get('responsablesmateriel', [ResponsableMaterielController::class, 'index'])->name('responsablesmateriel.index');
Route::get('responsablesmateriel/{id}', [ResponsableMaterielController::class, 'show'])->name('responsablesmateriel.show');

// ResponsableRH Routes
Route::get('responsablesrh', [ResponsableRHController::class, 'index'])->name('responsablesrh.index');
Route::get('responsablesrh/{id}', [ResponsableRHController::class, 'show'])->name('responsablesrh.show');
