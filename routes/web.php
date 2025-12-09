<?php

use App\Http\Controllers\PreviewPortfolioController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SlugController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('pdf/resume/{resume}', ResumeController::class)
    ->name('pdf.resume')
    ->middleware(['auth', 'verified']);

Route::get('preview/portfolio/{resume}', PreviewPortfolioController::class)
    ->name('preview.portfolio')
    ->middleware(['auth', 'verified']);

Route::get('{slug}', [SlugController::class, 'show'])->name('show.portfolio');

require __DIR__.'/settings.php';
