<?php

namespace Database\Seeders;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\Position;
use App\Models\Stat;
use App\Models\Team;
use Illuminate\Database\Seeder;

class MatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::all();
        
        // Check if we have the necessary teams
        if ($teams->isEmpty() || 
            !$teams->where('name', 'Manchester United')->first() ||
            !$teams->where('name', 'Liverpool')->first() ||
            !$teams->where('name', 'Real Madrid')->first() ||
            !$teams->where('name', 'Barcelona')->first() ||
            !$teams->where('name', 'Manchester City')->first() ||
            !$teams->where('name', 'Bayern Munich')->first() ||
            !$teams->where('name', 'Juventus')->first() ||
            !$teams->where('name', 'Paris Saint-Germain')->first()) {
            
            $this->command->error('Missing required teams. Please run TeamSeeder first.');
            return;
        }
        
        $players = Player::all();
        
        // If no players exist, create basic players for each team
        if ($players->count() < 20) {
            $this->createBasicPlayers($teams);
            $players = Player::all(); // Refresh player list
        }
        
        // Create some past matches
        $matches = [
            [
                'home_team_id' => $teams->where('name', 'Manchester United')->first()->id,
                'away_team_id' => $teams->where('name', 'Liverpool')->first()->id,
                'match_date' => now()->subDays(30),
                'venue' => 'Old Trafford',
                'competition' => 'Premier League',
                'home_score' => 2,
                'away_score' => 1,
                'status' => 'completed',
                'attendance' => 75000,
                'match_summary' => 'A thrilling encounter between two English giants.',
            ],
            [
                'home_team_id' => $teams->where('name', 'Real Madrid')->first()->id,
                'away_team_id' => $teams->where('name', 'Barcelona')->first()->id,
                'match_date' => now()->subDays(15),
                'venue' => 'Santiago Bernabéu',
                'competition' => 'La Liga',
                'home_score' => 3,
                'away_score' => 2,
                'status' => 'completed',
                'attendance' => 81000,
                'match_summary' => 'An exciting El Clásico with plenty of goals.',
            ],
            [
                'home_team_id' => $teams->where('name', 'Manchester City')->first()->id,
                'away_team_id' => $teams->where('name', 'Bayern Munich')->first()->id,
                'match_date' => now()->subDays(7),
                'venue' => 'Etihad Stadium',
                'competition' => 'Champions League',
                'home_score' => 2,
                'away_score' => 2,
                'status' => 'completed',
                'attendance' => 54000,
                'match_summary' => 'A tactical battle between two European heavyweights.',
            ],
        ];
        
        // Create some upcoming matches
        $upcomingMatches = [
            [
                'home_team_id' => $teams->where('name', 'Barcelona')->first()->id,
                'away_team_id' => $teams->where('name', 'Paris Saint-Germain')->first()->id,
                'match_date' => now()->addDays(7),
                'venue' => 'Camp Nou',
                'competition' => 'Champions League',
                'status' => 'scheduled',
            ],
            [
                'home_team_id' => $teams->where('name', 'Juventus')->first()->id,
                'away_team_id' => $teams->where('name', 'Manchester United')->first()->id,
                'match_date' => now()->addDays(14),
                'venue' => 'Allianz Stadium',
                'competition' => 'Champions League',
                'status' => 'scheduled',
            ],
            [
                'home_team_id' => $teams->where('name', 'Liverpool')->first()->id,
                'away_team_id' => $teams->where('name', 'Manchester City')->first()->id,
                'match_date' => now()->addDays(21),
                'venue' => 'Anfield',
                'competition' => 'Premier League',
                'status' => 'scheduled',
            ],
        ];
        
        $allMatches = array_merge($matches, $upcomingMatches);
        
        foreach ($allMatches as $match) {
            FootballMatch::create($match);
        }
        
        // Add match events and stats for completed matches
        foreach ($matches as $matchData) {
            $match = FootballMatch::where('home_team_id', $matchData['home_team_id'])
                ->where('away_team_id', $matchData['away_team_id'])
                ->where('match_date', $matchData['match_date'])
                ->first();
                
            if (!$match) continue;
            
            // Get players from both teams
            $homeTeamPlayers = $players->where('team_id', $match->home_team_id);
            $awayTeamPlayers = $players->where('team_id', $match->away_team_id);
            
            // If either team has no players, create some
            if ($homeTeamPlayers->isEmpty()) {
                $this->createPlayersForTeam($match->home_team_id);
                $homeTeamPlayers = Player::where('team_id', $match->home_team_id)->get();
            }
            
            if ($awayTeamPlayers->isEmpty()) {
                $this->createPlayersForTeam($match->away_team_id);
                $awayTeamPlayers = Player::where('team_id', $match->away_team_id)->get();
            }
            
            // Add goal events based on the score
            for ($i = 0; $i < $match->home_score; $i++) {
                if ($homeTeamPlayers->isEmpty()) {
                    continue;
                }
                
                $scorer = $homeTeamPlayers->random();
                $minute = rand(1, 90);
                
                MatchEvent::create([
                    'match_id' => $match->id,
                    'player_id' => $scorer->id,
                    'minute' => $minute,
                    'event_type' => 'goal',
                    'description' => 'Goal scored by ' . $scorer->full_name,
                ]);
                
                // Possibly add an assist
                if (rand(0, 1) && $homeTeamPlayers->where('id', '!=', $scorer->id)->count() > 0) {
                    $assist = $homeTeamPlayers->where('id', '!=', $scorer->id)->random();
                    
                    MatchEvent::create([
                        'match_id' => $match->id,
                        'player_id' => $assist->id,
                        'minute' => $minute,
                        'event_type' => 'assist',
                        'description' => 'Assist by ' . $assist->full_name,
                    ]);
                }
            }
            
            for ($i = 0; $i < $match->away_score; $i++) {
                if ($awayTeamPlayers->isEmpty()) {
                    continue;
                }
                
                $scorer = $awayTeamPlayers->random();
                $minute = rand(1, 90);
                
                MatchEvent::create([
                    'match_id' => $match->id,
                    'player_id' => $scorer->id,
                    'minute' => $minute,
                    'event_type' => 'goal',
                    'description' => 'Goal scored by ' . $scorer->full_name,
                ]);
                
                // Possibly add an assist
                if (rand(0, 1) && $awayTeamPlayers->where('id', '!=', $scorer->id)->count() > 0) {
                    $assist = $awayTeamPlayers->where('id', '!=', $scorer->id)->random();
                    
                    MatchEvent::create([
                        'match_id' => $match->id,
                        'player_id' => $assist->id,
                        'minute' => $minute,
                        'event_type' => 'assist',
                        'description' => 'Assist by ' . $assist->full_name,
                    ]);
                }
            }
            
            // Add some yellow cards
            $allPlayers = $homeTeamPlayers->merge($awayTeamPlayers);
            
            if ($allPlayers->count() > 0) {
                $yellowCardsCount = min(rand(2, 6), $allPlayers->count());
                $yellowCardPlayers = $allPlayers->random($yellowCardsCount);
                
                foreach ($yellowCardPlayers as $player) {
                    MatchEvent::create([
                        'match_id' => $match->id,
                        'player_id' => $player->id,
                        'minute' => rand(15, 85),
                        'event_type' => 'yellow_card',
                        'description' => 'Yellow card for ' . $player->full_name,
                    ]);
                }
                
                // Possibly add a red card
                if (rand(0, 5) === 0 && $allPlayers->count() > 0) {
                    $redCardPlayer = $allPlayers->random();
                    
                    MatchEvent::create([
                        'match_id' => $match->id,
                        'player_id' => $redCardPlayer->id,
                        'minute' => rand(60, 90),
                        'event_type' => 'red_card',
                        'description' => 'Red card for ' . $redCardPlayer->full_name,
                    ]);
                }
            }
            
            // Add stats for all players
            foreach ($homeTeamPlayers as $player) {
                $isGoalkeeper = $player->position && $player->position->code === 'GK';
                
                Stat::create([
                    'player_id' => $player->id,
                    'match_id' => $match->id,
                    'season' => '2024-2025',
                    'minutes_played' => rand(1, 90),
                    'goals' => MatchEvent::where('match_id', $match->id)
                        ->where('player_id', $player->id)
                        ->where('event_type', 'goal')
                        ->count(),
                    'assists' => MatchEvent::where('match_id', $match->id)
                        ->where('player_id', $player->id)
                        ->where('event_type', 'assist')
                        ->count(),
                    'yellow_cards' => MatchEvent::where('match_id', $match->id)
                        ->where('player_id', $player->id)
                        ->where('event_type', 'yellow_card')
                        ->count(),
                    'red_cards' => MatchEvent::where('match_id', $match->id)
                        ->where('player_id', $player->id)
                        ->where('event_type', 'red_card')
                        ->count(),
                    'passes' => rand(10, 100),
                    'pass_accuracy' => rand(50, 95),
                    'shots' => $isGoalkeeper ? 0 : rand(0, 5),
                    'shots_on_target' => $isGoalkeeper ? 0 : rand(0, 3),
                    'tackles' => rand(0, 10),
                    'interceptions' => rand(0, 8),
                    'saves' => $isGoalkeeper ? rand(1, 8) : 0,
                    'clean_sheets' => $isGoalkeeper && $match->away_score === 0 ? 1 : 0,
                ]);
            }
            
            foreach ($awayTeamPlayers as $player) {
                $isGoalkeeper = $player->position && $player->position->code === 'GK';
                
                Stat::create([
                    'player_id' => $player->id,
                    'match_id' => $match->id,
                    'season' => '2024-2025',
                    'minutes_played' => rand(1, 90),
                    'goals' => MatchEvent::where('match_id', $match->id)
                        ->where('player_id', $player->id)
                        ->where('event_type', 'goal')
                        ->count(),
                    'assists' => MatchEvent::where('match_id', $match->id)
                        ->where('player_id', $player->id)
                        ->where('event_type', 'assist')
                        ->count(),
                    'yellow_cards' => MatchEvent::where('match_id', $match->id)
                        ->where('player_id', $player->id)
                        ->where('event_type', 'yellow_card')
                        ->count(),
                    'red_cards' => MatchEvent::where('match_id', $match->id)
                        ->where('player_id', $player->id)
                        ->where('event_type', 'red_card')
                        ->count(),
                    'passes' => rand(10, 100),
                    'pass_accuracy' => rand(50, 95),
                    'shots' => $isGoalkeeper ? 0 : rand(0, 5),
                    'shots_on_target' => $isGoalkeeper ? 0 : rand(0, 3),
                    'tackles' => rand(0, 10),
                    'interceptions' => rand(0, 8),
                    'saves' => $isGoalkeeper ? rand(1, 8) : 0,
                    'clean_sheets' => $isGoalkeeper && $match->home_score === 0 ? 1 : 0,
                ]);
            }
        }
    }
    
    /**
     * Create basic players for all teams
     */
    private function createBasicPlayers($teams)
    {
        $positions = Position::all();
        
        if ($positions->isEmpty()) {
            // If no positions exist, create basic ones
            $basicPositions = [
                ['name' => 'Goalkeeper', 'code' => 'GK', 'description' => 'Protects the goal'],
                ['name' => 'Defender', 'code' => 'DEF', 'description' => 'Defends against attacks'],
                ['name' => 'Midfielder', 'code' => 'MID', 'description' => 'Controls the midfield'],
                ['name' => 'Forward', 'code' => 'FWD', 'description' => 'Scores goals'],
            ];
            
            foreach ($basicPositions as $position) {
                Position::create($position);
            }
            
            $positions = Position::all();
        }
        
        foreach ($teams as $team) {
            $this->createPlayersForTeam($team->id, $positions);
        }
    }

    /**
     * Create basic players for team
     */
    private function createPlayersForTeam($teamId, $positions = null)
    {
        if (!$positions) {
            $positions = Position::all();
            
            if ($positions->isEmpty()) {
                // Create basic positions if none exist
                Position::create(['name' => 'Goalkeeper', 'code' => 'GK', 'description' => 'Protects the goal']);
                Position::create(['name' => 'Defender', 'code' => 'DEF', 'description' => 'Defends against attacks']);
                Position::create(['name' => 'Midfielder', 'code' => 'MID', 'description' => 'Controls the midfield']);
                Position::create(['name' => 'Forward', 'code' => 'FWD', 'description' => 'Scores goals']);
                
                $positions = Position::all();
            }
        }
        
        $team = Team::find($teamId);
        if (!$team) return;
        
        $nationalities = ['England', 'Spain', 'France', 'Germany', 'Italy', 'Brazil', 'Argentina', 'Portugal', 'Netherlands', 'Belgium'];
        
        // Create a goalkeeper
        Player::create([
            'first_name' => 'Goalkeeper',
            'last_name' => $team->name,
            'date_of_birth' => now()->subYears(rand(22, 35))->subMonths(rand(1, 11)),
            'nationality' => $nationalities[array_rand($nationalities)],
            'team_id' => $team->id,
            'position_id' => $positions->where('code', 'GK')->first() ? 
                $positions->where('code', 'GK')->first()->id : 
                $positions->first()->id,
            'height' => rand(185, 195),
            'weight' => rand(75, 90),
            'preferred_foot' => ['right', 'left'][rand(0, 1)],
            'jersey_number' => 1,
        ]);
        
        // Create defenders
        for ($i = 0; $i < 4; $i++) {
            Player::create([
                'first_name' => 'Defender',
                'last_name' => ($i+1) . ' ' . $team->name,
                'date_of_birth' => now()->subYears(rand(20, 34))->subMonths(rand(1, 11)),
                'nationality' => $nationalities[array_rand($nationalities)],
                'team_id' => $team->id,
                'position_id' => $positions->whereIn('code', ['DEF', 'CB', 'RB', 'LB'])->first() ? 
                    $positions->whereIn('code', ['DEF', 'CB', 'RB', 'LB'])->random()->id : 
                    $positions->random()->id,
                'height' => rand(175, 195),
                'weight' => rand(70, 90),
                'preferred_foot' => ['right', 'left'][rand(0, 1)],
                'jersey_number' => $i + 2,
            ]);
        }
        
        // Create midfielders
        for ($i = 0; $i < 4; $i++) {
            Player::create([
                'first_name' => 'Midfielder',
                'last_name' => ($i+1) . ' ' . $team->name,
                'date_of_birth' => now()->subYears(rand(20, 33))->subMonths(rand(1, 11)),
                'nationality' => $nationalities[array_rand($nationalities)],
                'team_id' => $team->id,
                'position_id' => $positions->whereIn('code', ['MID', 'CM', 'DM', 'AM'])->first() ? 
                    $positions->whereIn('code', ['MID', 'CM', 'DM', 'AM'])->random()->id : 
                    $positions->random()->id,
                'height' => rand(170, 185),
                'weight' => rand(65, 80),
                'preferred_foot' => ['right', 'left'][rand(0, 1)],
                'jersey_number' => $i + 6,
            ]);
        }
        
        // Create forwards
        for ($i = 0; $i < 3; $i++) {
            Player::create([
                'first_name' => 'Forward',
                'last_name' => ($i+1) . ' ' . $team->name,
                'date_of_birth' => now()->subYears(rand(20, 32))->subMonths(rand(1, 11)),
                'nationality' => $nationalities[array_rand($nationalities)],
                'team_id' => $team->id,
                'position_id' => $positions->whereIn('code', ['FWD', 'ST', 'RW', 'LW'])->first() ? 
                    $positions->whereIn('code', ['FWD', 'ST', 'RW', 'LW'])->random()->id : 
                    $positions->random()->id,
                'height' => rand(175, 190),
                'weight' => rand(70, 85),
                'preferred_foot' => ['right', 'left'][rand(0, 1)],
                'jersey_number' => $i + 10,
            ]);
        }
    }
}