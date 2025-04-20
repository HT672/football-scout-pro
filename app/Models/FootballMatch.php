<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    use HasFactory;

    protected $table = 'football_matches';

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'match_date',
        'venue',
        'competition',
        'home_score',
        'away_score',
        'status',
        'match_summary',
        'attendance',
    ];

    protected $casts = [
        'match_date' => 'datetime',
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function events()
    {
        return $this->hasMany(MatchEvent::class, 'match_id');
    }

    public function stats()
    {
        return $this->hasMany(Stat::class, 'match_id');
    }

    public function getScoreAttribute()
    {
        return "{$this->home_score} - {$this->away_score}";
    }

    public function getMatchTitleAttribute()
    {
        return "{$this->homeTeam->name} vs {$this->awayTeam->name}";
    }
}