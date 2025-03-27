<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $upcomingMatches = FootballMatch::where('match_date', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('match_date')
            ->take(5)
            ->get();
            
        $recentMatches = FootballMatch::where('status', 'completed')
            ->orderBy('match_date', 'desc')
            ->take(5)
            ->get();
            
        $topPlayers = Player::with(['stats', 'team'])
            ->whereHas('stats', function ($query) {
                $query->where('season', '2024-2025');
            })
            ->get()
            ->sortByDesc(function ($player) {
                return $player->stats->where('season', '2024-2025')->sum('goals');
            })
            ->take(5);
            
        $teams = Team::orderBy('name')->get();
        
        return view('home', compact('upcomingMatches', 'recentMatches', 'topPlayers', 'teams'));
    }
}