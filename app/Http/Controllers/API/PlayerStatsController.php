<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Models\Stat;
use Illuminate\Http\Request;

class PlayerStatsController extends Controller
{
    public function index(Request $request)
    {
        $season = $request->input('season', '2024-2025');
        $category = $request->input('category', 'goals');
        $limit = $request->input('limit', 10);
        
        $players = Player::with(['stats' => function($query) use ($season) {
            $query->where('season', $season);
        }, 'team', 'position'])
        ->whereHas('stats', function($query) use ($season) {
            $query->where('season', $season);
        })
        ->get()
        ->sortByDesc(function($player) use ($category) {
            return $player->stats->sum($category);
        })
        ->take($limit)
        ->values();
        
        return PlayerResource::collection($players)
            ->additional([
                'meta' => [
                    'season' => $season,
                    'category' => $category,
                ],
            ]);
    }
    
    public function playerStats($id, Request $request)
    {
        $player = Player::with(['stats', 'team', 'position'])->findOrFail($id);
        $season = $request->input('season');
        
        if ($season) {
            $player->setRelation('stats', $player->stats->where('season', $season));
        }
        
        return new PlayerResource($player);
    }
    
    public function teamTopScorers($id, Request $request)
    {
        $season = $request->input('season', '2024-2025');
        $limit = $request->input('limit', 5);
        
        $players = Player::with(['stats' => function($query) use ($season) {
            $query->where('season', $season);
        }, 'team', 'position'])
        ->where('team_id', $id)
        ->whereHas('stats', function($query) use ($season) {
            $query->where('season', $season);
        })
        ->get()
        ->sortByDesc(function($player) {
            return $player->stats->sum('goals');
        })
        ->take($limit)
        ->values();
        
        return PlayerResource::collection($players);
    }
}