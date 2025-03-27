<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'nationality',
        'team_id',
        'position_id',
        'photo',
        'height',
        'weight',
        'preferred_foot',
        'jersey_number',
        'bio',
        'market_value',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'market_value' => 'decimal:2',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function stats()
    {
        return $this->hasMany(Stat::class);
    }

    public function matchEvents()
    {
        return $this->hasMany(MatchEvent::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }
}