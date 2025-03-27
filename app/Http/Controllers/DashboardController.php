<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $totalPlayers = Player::count();
        $totalTeams = Team::count();
        $upcomingMatches = FootballMatch::where('match_date', '>', now())
            ->orderBy('match_date')
            ->take(5)
            ->get();
            
        $playersByPosition = Player::with('position')
            ->get()
            ->groupBy('position.name')
            ->map(function ($players) {
                return count($players);
            });
            
        return view('dashboard', compact('totalPlayers', 'totalTeams', 'upcomingMatches', 'playersByPosition'));
    }
}