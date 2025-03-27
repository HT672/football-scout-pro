<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\FootballMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('check.scout.role')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $teams = Team::withCount('players')->orderBy('name')->paginate(12);
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams',
            'country' => 'required|string|max:255',
            'league' => 'required|string|max:255',
            'manager' => 'nullable|string|max:255',
            'stadium' => 'nullable|string|max:255',
            'founded' => 'nullable|numeric|min:1800|max:' . date('Y'),
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->all();
        
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('teams', 'public');
        }
        
        Team::create($data);
        
        return redirect()->route('teams.index')
            ->with('success', 'Team created successfully.');
    }

    public function show(Team $team)
    {
        $team->load(['players' => function($query) {
            $query->with('position')->orderBy('jersey_number');
        }]);
        
        $upcomingMatches = FootballMatch::where(function($query) use ($team) {
                $query->where('home_team_id', $team->id)
                    ->orWhere('away_team_id', $team->id);
            })
            ->where('match_date', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('match_date')
            ->take(5)
            ->get();
            
        $recentMatches = FootballMatch::where(function($query) use ($team) {
                $query->where('home_team_id', $team->id)
                    ->orWhere('away_team_id', $team->id);
            })
            ->where('status', 'completed')
            ->orderBy('match_date', 'desc')
            ->take(5)
            ->get();
            
        return view('teams.show', compact('team', 'upcomingMatches', 'recentMatches'));
    }

    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'country' => 'required|string|max:255',
            'league' => 'required|string|max:255',
            'manager' => 'nullable|string|max:255',
            'stadium' => 'nullable|string|max:255',
            'founded' => 'nullable|numeric|min:1800|max:' . date('Y'),
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->all();
        
        if ($request->hasFile('logo')) {
            if ($team->logo) {
                Storage::disk('public')->delete($team->logo);
            }
            $data['logo'] = $request->file('logo')->store('teams', 'public');
        }
        
        $team->update($data);
        
        return redirect()->route('teams.show', $team)
            ->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team)
    {
        if ($team->logo) {
            Storage::disk('public')->delete($team->logo);
        }
        
        $team->delete();
        
        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully.');
    }
}