<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Models\Stat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportPlayerStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:player-stats {file : The CSV file path} {season=2024-2025 : The season for these stats}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import player statistics from a CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        $season = $this->argument('season');
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }
        
        $csvFile = fopen($filePath, 'r');
        
        // Read header row
        $headers = fgetcsv($csvFile);
        
        // Convert headers to lowercase for easier matching
        $headers = array_map('strtolower', $headers);
        
        // Check if required columns exist
        $requiredColumns = ['player_id', 'minutes_played', 'goals', 'assists'];
        $missingColumns = array_diff($requiredColumns, $headers);
        
        if (!empty($missingColumns)) {
            $this->error('Missing required columns: ' . implode(', ', $missingColumns));
            return 1;
        }
        
        $stats = [];
        $line = 2; // Start at line 2 (after header)
        
        while (($row = fgetcsv($csvFile)) !== false) {
            $data = array_combine($headers, $row);
            
            // Find the player
            $playerId = $data['player_id'];
            $player = Player::find($playerId);
            
            if (!$player) {
                $this->warn("Player ID {$playerId} not found at line {$line}. Skipping.");
                $line++;
                continue;
            }
            
            // Convert and validate data
            $statData = [
                'player_id' => $playerId,
                'season' => $season,
                'match_id' => isset($data['match_id']) ? $data['match_id'] : null,
                'minutes_played' => $data['minutes_played'],
                'goals' => $data['goals'],
                'assists' => $data['assists'],
                'yellow_cards' => isset($data['yellow_cards']) ? $data['yellow_cards'] : 0,
                'red_cards' => isset($data['red_cards']) ? $data['red_cards'] : 0,
                'passes' => isset($data['passes']) ? $data['passes'] : 0,
                'pass_accuracy' => isset($data['pass_accuracy']) ? $data['pass_accuracy'] : null,
                'shots' => isset($data['shots']) ? $data['shots'] : 0,
                'shots_on_target' => isset($data['shots_on_target']) ? $data['shots_on_target'] : 0,
                'tackles' => isset($data['tackles']) ? $data['tackles'] : 0,
                'interceptions' => isset($data['interceptions']) ? $data['interceptions'] : 0,
                'saves' => isset($data['saves']) ? $data['saves'] : 0,
                'clean_sheets' => isset($data['clean_sheets']) ? $data['clean_sheets'] : 0,
            ];
            
            // Create new stat
            $stats[] = $statData;
            $line++;
        }
        
        fclose($csvFile);
        
        // Insert stats in batch
        Stat::insert($stats);
        
        $this->info('Successfully imported ' . count($stats) . ' player statistics.');
        
        return 0;
    }
}