<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Models\Player;
use App\Models\Position;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlayerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('check.scout.role')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    
    public function index()
    {
        $players = Player::with(['team', 'position'])->paginate(15);
        return view('players.index', compact('players'));
    }

    public function create()
    {
        $teams = Team::orderBy('name')->pluck('name', 'id');
        $positions = Position::orderBy('name')->pluck('name', 'id');
        return view('players.create', compact('teams', 'positions'));
    }

    public function store(StorePlayerRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('players', 'public');
        }
        
        Player::create($data);
        
        return redirect()->route('players.index')
            ->with('success', 'Player created successfully.');
    }

    public function show(Player $player)
    {
        $player->load(['team', 'position', 'stats' => function($query) {
            $query->orderBy('season', 'desc');
        }]);
        
        $seasonStats = $player->stats
            ->groupBy('season')
            ->map(function ($stats) {
                return [
                    'matches' => $stats->count(),
                    'goals' => $stats->sum('goals'),
                    'assists' => $stats->sum('assists'),
                    'minutes_played' => $stats->sum('minutes_played'),
                    'yellow_cards' => $stats->sum('yellow_cards'),
                    'red_cards' => $stats->sum('red_cards'),
                ];
            });
            
        return view('players.show', compact('player', 'seasonStats'));
    }

    public function edit(Player $player)
    {
        $teams = Team::orderBy('name')->pluck('name', 'id');
        $positions = Position::orderBy('name')->pluck('name', 'id');
        return view('players.edit', compact('player', 'teams', 'positions'));
    }

    public function update(UpdatePlayerRequest $request, Player $player)
    {
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            if ($player->photo) {
                Storage::disk('public')->delete($player->photo);
            }
            $data['photo'] = $request->file('photo')->store('players', 'public');
        }
        
        $player->update($data);
        
        return redirect()->route('players.show', $player)
            ->with('success', 'Player updated successfully.');
    }

    public function destroy(Player $player)
    {
        if ($player->photo) {
            Storage::disk('public')->delete($player->photo);
        }
        
        $player->delete();
        
        return redirect()->route('players.index')
            ->with('success', 'Player deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $position = $request->input('position');
        $team = $request->input('team');
        
        $players = Player::with(['team', 'position'])
            ->when($query, function ($q) use ($query) {
                return $q->where(function ($innerQ) use ($query) {
                    $innerQ->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%");
                });
            })
            ->when($position, function ($q) use ($position) {
                return $q->where('position_id', $position);
            })
            ->when($team, function ($q) use ($team) {
                return $q->where('team_id', $team);
            })
            ->paginate(15)
            ->appends(request()->query());
            
        $positions = Position::orderBy('name')->pluck('name', 'id');
        $teams = Team::orderBy('name')->pluck('name', 'id');
        
        return view('players.index', compact('players', 'positions', 'teams', 'query', 'position', 'team'));
    }

    public function compare(Request $request)
    {
        $playerIds = $request->input('players', []);
        
        if (count($playerIds) < 2) {
            return redirect()->route('players.index')
                ->with('error', 'Please select at least 2 players to compare.');
        }
        
        $players = Player::with(['team', 'position', 'stats'])->findOrFail($playerIds);
        
        $seasons = $players->flatMap(function ($player) {
            return $player->stats->pluck('season');
        })->unique()->sort()->values()->all();
        
        return view('players.compare', compact('players', 'seasons'));
    }
}