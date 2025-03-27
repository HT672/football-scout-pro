<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'player_id',
        'minute',
        'event_type',
        'description',
    ];

    public function match()
    {
        return $this->belongsTo(FootballMatch::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
