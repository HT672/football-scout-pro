<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Stat;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('check.scout.role')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    
    public function index()
    {
        $seasons = Stat::select('season')->distinct()->orderBy('season', 'desc')->pluck('season');
        $currentSeason = request('season', $seasons->first());
        
        $topScorers = Player::with(['stats' => function($query) use ($currentSeason) {
            $query->where('season', $currentSeason);
        }, 'team'])
        ->whereHas('stats', function($query) use ($currentSeason) {
            $query->where('season', $currentSeason);
        })
        ->get()
        ->sortByDesc(function($player) {
            return $player->stats->sum('goals');
        })
        ->take(10);
        
        $topAssists = Player::with(['stats' => function($query) use ($currentSeason) {
            $query->where('season', $currentSeason);
        }, 'team'])
        ->whereHas('stats', function($query) use ($currentSeason) {
            $query->where('season', $currentSeason);
        })
        ->get()
        ->sortByDesc(function($player) {
            return $player->stats->sum('assists');
        })
        ->take(10);
        
        return view('stats.index', compact('topScorers', 'topAssists', 'seasons', 'currentSeason'));
    }

    public function create()
    {
        $players = Player::with('team')->get()->pluck('full_name', 'id');
        return view('stats.create', compact('players'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'match_id' => 'nullable|exists:matches,id',
            'season' => 'required|string|max:10',
            'minutes_played' => 'required|integer|min:0|max:120',
            'goals' => 'required|integer|min:0',
            'assists' => 'required|integer|min:0',
            'yellow_cards' => 'required|integer|min:0',
            'red_cards' => 'required|integer|min:0',
            'passes' => 'required|integer|min:0',
            'pass_accuracy' => 'nullable|numeric|min:0|max:100',
            'shots' => 'required|integer|min:0',
            'shots_on_target' => 'required|integer|min:0|lte:shots',
            'tackles' => 'required|integer|min:0',
            'interceptions' => 'required|integer|min:0',
            'saves' => 'nullable|integer|min:0',
            'clean_sheets' => 'nullable|integer|min:0',
        ]);
        
        Stat::create($request->all());
        
        return redirect()->route('stats.index')
            ->with('success', 'Player stats added successfully.');
    }

    public function show($id)
    {
        $player = Player::with(['stats' => function($query) {
            $query->orderBy('season', 'desc');
        }, 'team', 'position'])->findOrFail($id);
        
        $seasons = $player->stats->pluck('season')->unique();
        $currentSeason = request('season', $seasons->first());
        
        $seasonStats = $player->stats
            ->where('season', $currentSeason)
            ->groupBy(function($stat) {
                return optional($stat->match)->id ?? 'total';
            });
            
        $totalStats = [
            'matches' => $seasonStats->count() - (isset($seasonStats['total']) ? 1 : 0),
            'minutes_played' => $player->stats->where('season', $currentSeason)->sum('minutes_played'),
            'goals' => $player->stats->where('season', $currentSeason)->sum('goals'),
            'assists' => $player->stats->where('season', $currentSeason)->sum('assists'),
            'yellow_cards' => $player->stats->where('season', $currentSeason)->sum('yellow_cards'),
            'red_cards' => $player->stats->where('season', $currentSeason)->sum('red_cards'),
            'passes' => $player->stats->where('season', $currentSeason)->sum('passes'),
            'pass_accuracy' => $player->stats->where('season', $currentSeason)->avg('pass_accuracy'),
            'shots' => $player->stats->where('season', $currentSeason)->sum('shots'),
            'shots_on_target' => $player->stats->where('season', $currentSeason)->sum('shots_on_target'),
            'tackles' => $player->stats->where('season', $currentSeason)->sum('tackles'),
            'interceptions' => $player->stats->where('season', $currentSeason)->sum('interceptions'),
            'saves' => $player->stats->where('season', $currentSeason)->sum('saves'),
            'clean_sheets' => $player->stats->where('season', $currentSeason)->sum('clean_sheets'),
        ];
        
        return view('stats.show', compact('player', 'seasons', 'currentSeason', 'seasonStats', 'totalStats'));
    }

    public function edit(Stat $stat)
    {
        $players = Player::with('team')->get()->pluck('full_name', 'id');
        return view('stats.edit', compact('stat', 'players'));
    }

    public function update(Request $request, Stat $stat)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'match_id' => 'nullable|exists:matches,id',
            'season' => 'required|string|max:10',
            'minutes_played' => 'required|integer|min:0|max:120',
            'goals' => 'required|integer|min:0',
            'assists' => 'required|integer|min:0',
            'yellow_cards' => 'required|integer|min:0',
            'red_cards' => 'required|integer|min:0',
            'passes' => 'required|integer|min:0',
            'pass_accuracy' => 'nullable|numeric|min:0|max:100',
            'shots' => 'required|integer|min:0',
            'shots_on_target' => 'required|integer|min:0|lte:shots',
            'tackles' => 'required|integer|min:0',
            'interceptions' => 'required|integer|min:0',
            'saves' => 'nullable|integer|min:0',
            'clean_sheets' => 'nullable|integer|min:0',
        ]);
        
        $stat->update($request->all());
        
        return redirect()->route('stats.show', $stat->player_id)
            ->with('success', 'Player stats updated successfully.');
    }

    public function destroy(Stat $stat)
    {
        $playerId = $stat->player_id;
        $stat->delete();
        
        return redirect()->route('stats.show', $playerId)
            ->with('success', 'Player stats deleted successfully.');
    }
}