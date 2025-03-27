<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Manchester United',
                'country' => 'England',
                'league' => 'Premier League',
                'manager' => 'Erik ten Hag',
                'stadium' => 'Old Trafford',
                'founded' => 1878,
                'description' => 'Manchester United Football Club is a professional football club based in Old Trafford, Greater Manchester, England.',
            ],
            [
                'name' => 'Real Madrid',
                'country' => 'Spain',
                'league' => 'La Liga',
                'manager' => 'Carlo Ancelotti',
                'stadium' => 'Santiago Bernabéu',
                'founded' => 1902,
                'description' => 'Real Madrid Club de Fútbol, commonly referred to as Real Madrid, is a Spanish professional football club based in Madrid.',
            ],
            [
                'name' => 'Bayern Munich',
                'country' => 'Germany',
                'league' => 'Bundesliga',
                'manager' => 'Thomas Tuchel',
                'stadium' => 'Allianz Arena',
                'founded' => 1900,
                'description' => 'Fußball-Club Bayern München e. V., commonly known as FC Bayern München, is a German professional sports club based in Munich, Bavaria.',
            ],
            [
                'name' => 'Paris Saint-Germain',
                'country' => 'France',
                'league' => 'Ligue 1',
                'manager' => 'Luis Enrique',
                'stadium' => 'Parc des Princes',
                'founded' => 1970,
                'description' => 'Paris Saint-Germain Football Club, commonly referred to as Paris Saint-Germain, Paris, Paris SG or simply PSG is a French professional football club based in Paris.',
            ],
            [
                'name' => 'Liverpool',
                'country' => 'England',
                'league' => 'Premier League',
                'manager' => 'Jürgen Klopp',
                'stadium' => 'Anfield',
                'founded' => 1892,
                'description' => 'Liverpool Football Club is a professional football club based in Liverpool, England.',
            ],
            [
                'name' => 'Manchester City',
                'country' => 'England',
                'league' => 'Premier League',
                'manager' => 'Pep Guardiola',
                'stadium' => 'Etihad Stadium',
                'founded' => 1880,
                'description' => 'Manchester City Football Club is an English football club based in Manchester that competes in the Premier League.',
            ],
            [
                'name' => 'Barcelona',
                'country' => 'Spain',
                'league' => 'La Liga',
                'manager' => 'Xavi Hernandez',
                'stadium' => 'Camp Nou',
                'founded' => 1899,
                'description' => 'Futbol Club Barcelona, commonly referred to as Barcelona and colloquially known as Barça, is a Spanish professional football club based in Barcelona, Catalonia, Spain.',
            ],
            [
                'name' => 'Juventus',
                'country' => 'Italy',
                'league' => 'Serie A',
                'manager' => 'Massimiliano Allegri',
                'stadium' => 'Allianz Stadium',
                'founded' => 1897,
                'description' => 'Juventus Football Club, colloquially known as Juve, is a professional football club based in Turin, Piedmont, Italy.',
            ],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}