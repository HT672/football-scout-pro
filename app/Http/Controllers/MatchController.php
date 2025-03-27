<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('check.scout.role')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    
    public function index()
    {
        $upcomingMatches = FootballMatch::with(['homeTeam', 'awayTeam'])
            ->where('match_date', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('match_date')
            ->paginate(10, ['*'], 'upcoming');
            
        $recentMatches = FootballMatch::with(['homeTeam', 'awayTeam'])
            ->where('status', 'completed')
            ->orderBy('match_date', 'desc')
            ->paginate(10, ['*'], 'recent');
            
        return view('matches.index', compact('upcomingMatches', 'recentMatches'));
    }

    public function create()
    {
        $teams = Team::orderBy('name')->pluck('name', 'id');
        return view('matches.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'match_date' => 'required|date',
            'venue' => 'required|string|max:255',
            'competition' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,live,completed,postponed,cancelled',
        ]);
        
        FootballMatch::create($request->all());
        
        return redirect()->route('matches.index')
            ->with('success', 'Match created successfully.');
    }

    public function show(FootballMatch $match)
    {
        $match->load(['homeTeam', 'awayTeam', 'events' => function ($query) {
            $query->with('player')->orderBy('minute');
        }]);
        
        $homeTeamPlayers = Player::where('team_id', $match->home_team_id)
            ->with('position')
            ->get();
            
        $awayTeamPlayers = Player::where('team_id', $match->away_team_id)
            ->with('position')
            ->get();
            
        return view('matches.show', compact('match', 'homeTeamPlayers', 'awayTeamPlayers'));
    }

    public function edit(FootballMatch $match)
    {
        $teams = Team::orderBy('name')->pluck('name', 'id');
        return view('matches.edit', compact('match', 'teams'));
    }

    public function update(Request $request, FootballMatch $match)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'match_date' => 'required|date',
            'venue' => 'required|string|max:255',
            'competition' => 'nullable|string|max:255',
            'home_score' => 'required_if:status,completed,live|integer|min:0',
            'away_score' => 'required_if:status,completed,live|integer|min:0',
            'status' => 'required|in:scheduled,live,completed,postponed,cancelled',
            'match_summary' => 'nullable|string',
            'attendance' => 'nullable|integer|min:0',
        ]);
        
        $match->update($request->all());
        
        return redirect()->route('matches.show', $match)
            ->with('success', 'Match updated successfully.');
    }

    public function destroy(FootballMatch $match)
    {
        $match->delete();
        
        return redirect()->route('matches.index')
            ->with('success', 'Match deleted successfully.');
    }

    public function addEvent(Request $request, FootballMatch $match)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'minute' => 'required|integer|min:1|max:120',
            'event_type' => 'required|in:goal,assist,yellow_card,red_card,substitution_in,substitution_out,penalty_missed,penalty_saved,own_goal',
            'description' => 'nullable|string',
        ]);
        
        MatchEvent::create([
            'match_id' => $match->id,
            'player_id' => $request->player_id,
            'minute' => $request->minute,
            'event_type' => $request->event_type,
            'description' => $request->description,
        ]);
        
        return redirect()->route('matches.show', $match)
            ->with('success', 'Match event added successfully.');
    }

    public function liveUpdate(Request $request, FootballMatch $match)
    {
        $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'status' => 'required|in:live,completed',
        ]);
        
        $match->update($request->only(['home_score', 'away_score', 'status']));
        
        return response()->json([
            'success' => true,
            'match' => $match->fresh(['homeTeam', 'awayTeam']),
        ]);
    }
}