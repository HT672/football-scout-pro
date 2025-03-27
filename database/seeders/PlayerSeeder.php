<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Position;
use App\Models\Team;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all teams and positions
        $teams = Team::all();
        $positions = Position::all();
        
        // Manchester United players
        $manUtd = $teams->where('name', 'Manchester United')->first();
        $gkPosition = $positions->where('code', 'GK')->first();
        $cbPosition = $positions->where('code', 'CB')->first();
        $lbPosition = $positions->where('code', 'LB')->first();
        $rbPosition = $positions->where('code', 'RB')->first();
        $dmPosition = $positions->where('code', 'DM')->first();
        $cmPosition = $positions->where('code', 'CM')->first();
        $amPosition = $positions->where('code', 'AM')->first();
        $lwPosition = $positions->where('code', 'LW')->first();
        $rwPosition = $positions->where('code', 'RW')->first();
        $stPosition = $positions->where('code', 'ST')->first();
        
        $players = [
            [
                'first_name' => 'David',
                'last_name' => 'De Gea',
                'date_of_birth' => '1990-11-07',
                'nationality' => 'Spain',
                'team_id' => $manUtd->id,
                'position_id' => $gkPosition->id,
                'height' => 192,
                'weight' => 82,
                'preferred_foot' => 'right',
                'jersey_number' => 1,
                'bio' => 'Spanish goalkeeper known for his reflexes and shot-stopping ability.',
                'market_value' => 15.0,
            ],
            [
                'first_name' => 'Harry',
                'last_name' => 'Maguire',
                'date_of_birth' => '1993-03-05',
                'nationality' => 'England',
                'team_id' => $manUtd->id,
                'position_id' => $cbPosition->id,
                'height' => 194,
                'weight' => 85,
                'preferred_foot' => 'right',
                'jersey_number' => 5,
                'bio' => 'English center-back and team captain.',
                'market_value' => 30.0,
            ],
            [
                'first_name' => 'Bruno',
                'last_name' => 'Fernandes',
                'date_of_birth' => '1994-09-08',
                'nationality' => 'Portugal',
                'team_id' => $manUtd->id,
                'position_id' => $amPosition->id,
                'height' => 179,
                'weight' => 73,
                'preferred_foot' => 'right',
                'jersey_number' => 8,
                'bio' => 'Portuguese attacking midfielder known for his creativity and goal-scoring ability.',
                'market_value' => 70.0,
            ],
            [
                'first_name' => 'Marcus',
                'last_name' => 'Rashford',
                'date_of_birth' => '1997-10-31',
                'nationality' => 'England',
                'team_id' => $manUtd->id,
                'position_id' => $lwPosition->id,
                'height' => 185,
                'weight' => 76,
                'preferred_foot' => 'right',
                'jersey_number' => 10,
                'bio' => 'English forward known for his pace and direct attacking play.',
                'market_value' => 65.0,
            ],
            
            // Real Madrid players
            [
                'first_name' => 'Thibaut',
                'last_name' => 'Courtois',
                'date_of_birth' => '1992-05-11',
                'nationality' => 'Belgium',
                'team_id' => $teams->where('name', 'Real Madrid')->first()->id,
                'position_id' => $gkPosition->id,
                'height' => 199,
                'weight' => 96,
                'preferred_foot' => 'left',
                'jersey_number' => 1,
                'bio' => 'Belgian goalkeeper known for his commanding presence and shot-stopping ability.',
                'market_value' => 75.0,
            ],
            [
                'first_name' => 'Vinicius',
                'last_name' => 'Jr',
                'date_of_birth' => '2000-07-12',
                'nationality' => 'Brazil',
                'team_id' => $teams->where('name', 'Real Madrid')->first()->id,
                'position_id' => $lwPosition->id,
                'height' => 176,
                'weight' => 73,
                'preferred_foot' => 'right',
                'jersey_number' => 7,
                'bio' => 'Brazilian winger known for his dribbling skills and pace.',
                'market_value' => 120.0,
            ],
            
            // Barcelona players
            [
                'first_name' => 'Marc-André',
                'last_name' => 'ter Stegen',
                'date_of_birth' => '1992-04-30',
                'nationality' => 'Germany',
                'team_id' => $teams->where('name', 'Barcelona')->first()->id,
                'position_id' => $gkPosition->id,
                'height' => 187,
                'weight' => 85,
                'preferred_foot' => 'right',
                'jersey_number' => 1,
                'bio' => 'German goalkeeper known for his ball-playing abilities.',
                'market_value' => 55.0,
            ],
            [
                'first_name' => 'Pedri',
                'last_name' => 'González',
                'date_of_birth' => '2002-11-25',
                'nationality' => 'Spain',
                'team_id' => $teams->where('name', 'Barcelona')->first()->id,
                'position_id' => $cmPosition->id,
                'height' => 174,
                'weight' => 68,
                'preferred_foot' => 'right',
                'jersey_number' => 16,
                'bio' => 'Spanish midfield prodigy known for his technical skills and vision.',
                'market_value' => 80.0,
            ],
            
            // Add more players as needed for the other teams
        ];
        
        foreach ($players as $player) {
            Player::create($player);
        }
    }
}