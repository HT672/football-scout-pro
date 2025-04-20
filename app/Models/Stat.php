<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'match_id',
        'season',
        'minutes_played',
        'goals',
        'assists',
        'yellow_cards',
        'red_cards',
        'passes',
        'pass_accuracy',
        'shots',
        'shots_on_target',
        'tackles',
        'interceptions',
        'saves',
        'clean_sheets',
    ];

    protected $casts = [
        'pass_accuracy' => 'decimal:2',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function match()
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }
}