<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['name' => 'Goalkeeper', 'code' => 'GK', 'description' => 'Protects the goal and prevents the opposition from scoring'],
            ['name' => 'Right-Back', 'code' => 'RB', 'description' => 'Defends the right side of the field and supports attacks'],
            ['name' => 'Left-Back', 'code' => 'LB', 'description' => 'Defends the left side of the field and supports attacks'],
            ['name' => 'Center-Back', 'code' => 'CB', 'description' => 'Central defender responsible for stopping opposition attacks'],
            ['name' => 'Defensive Midfielder', 'code' => 'DM', 'description' => 'Sits in front of defense to provide protection and distribute the ball'],
            ['name' => 'Central Midfielder', 'code' => 'CM', 'description' => 'Links defense and attack in the center of the field'],
            ['name' => 'Attacking Midfielder', 'code' => 'AM', 'description' => 'Creative player who operates between midfield and forward lines'],
            ['name' => 'Right Winger', 'code' => 'RW', 'description' => 'Attacks from the right side of the field'],
            ['name' => 'Left Winger', 'code' => 'LW', 'description' => 'Attacks from the left side of the field'],
            ['name' => 'Striker', 'code' => 'ST', 'description' => 'Main offensive player responsible for scoring goals'],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
