<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'date_of_birth' => $this->date_of_birth,
            'age' => $this->age,
            'nationality' => $this->nationality,
            'height' => $this->height,
            'weight' => $this->weight,
            'preferred_foot' => $this->preferred_foot,
            'jersey_number' => $this->jersey_number,
            'market_value' => $this->market_value,
            'photo_url' => $this->photo ? asset('storage/' . $this->photo) : null,
            'team' => $this->when($this->team, function () {
                return [
                    'id' => $this->team->id,
                    'name' => $this->team->name,
                    'logo_url' => $this->team->logo ? asset('storage/' . $this->team->logo) : null,
                ];
            }),
            'position' => $this->when($this->position, function () {
                return [
                    'id' => $this->position->id,
                    'name' => $this->position->name,
                    'code' => $this->position->code,
                ];
            }),
            'stats' => $this->when($this->stats, function () {
                $stats = $this->stats->groupBy('season');
                $result = [];
                
                foreach ($stats as $season => $seasonStats) {
                    $result[$season] = [
                        'matches' => $seasonStats->count(),
                        'minutes_played' => $seasonStats->sum('minutes_played'),
                        'goals' => $seasonStats->sum('goals'),
                        'assists' => $seasonStats->sum('assists'),
                        'yellow_cards' => $seasonStats->sum('yellow_cards'),
                        'red_cards' => $seasonStats->sum('red_cards'),
                    ];
                }
                
                return $result;
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}