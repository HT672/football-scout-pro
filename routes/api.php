<?php

use App\Http\Controllers\API\PlayerStatsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::get('/players/stats', [PlayerStatsController::class, 'index']);
Route::get('/players/{id}/stats', [PlayerStatsController::class, 'playerStats']);
Route::get('/teams/{id}/top-scorers', [PlayerStatsController::class, 'teamTopScorers']);

Route::get('/teams/{id}/matches', function ($id) {
    $team = \App\Models\Team::findOrFail($id);
    
    $matches = \App\Models\FootballMatch::where(function($query) use ($id) {
            $query->where('home_team_id', $id)
                ->orWhere('away_team_id', $id);
        })
        ->with(['homeTeam', 'awayTeam'])
        ->orderBy('match_date', 'desc')
        ->get()
        ->take(20);
    
    return \App\Http\Resources\MatchResource::collection($matches);
});