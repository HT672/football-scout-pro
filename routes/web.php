<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Player routes
Route::get('/players/search', [PlayerController::class, 'search'])->name('players.search');
Route::get('/players/compare', [PlayerController::class, 'compare'])->name('players.compare');
Route::resource('players', PlayerController::class);

// Team routes
Route::resource('teams', TeamController::class);

// Match routes
Route::post('/matches/{match}/events', [MatchController::class, 'addEvent'])->name('matches.events.store');
Route::post('/matches/{match}/live-update', [MatchController::class, 'liveUpdate'])->name('matches.live-update');
Route::resource('matches', MatchController::class);

// Stats routes
Route::resource('stats', StatsController::class);

// Authentication routes
Auth::routes();

// Dashboard (protected routes)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/admin/all-matches', function() {
    $matches = App\Models\FootballMatch::with(['homeTeam', 'awayTeam'])->get();
    return view('admin.all-matches', compact('matches'));
})->middleware(['auth', 'check.scout.role']);