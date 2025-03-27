<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'match_date' => $this->match_date,
            'venue' => $this->venue,
            'competition' => $this->competition,
            'status' => $this->status,
            'home_score' => $this->home_score,
            'away_score' => $this->away_score,
            'attendance' => $this->attendance,
            'match_summary' => $this->match_summary,
            'home_team' => [
                'id' => $this->homeTeam->id,
                'name' => $this->homeTeam->name,
                'logo_url' => $this->homeTeam->logo ? asset('storage/' . $this->homeTeam->logo) : null,
            ],
            'away_team' => [
                'id' => $this->awayTeam->id,
                'name' => $this->awayTeam->name,
                'logo_url' => $this->awayTeam->logo ? asset('storage/' . $this->awayTeam->logo) : null,
            ],
            'events' => $this->whenLoaded('events', function () {
                return $this->events->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'minute' => $event->minute,
                        'event_type' => $event->event_type,
                        'description' => $event->description,
                        'player' => [
                            'id' => $event->player->id,
                            'name' => $event->player->full_name,
                            'team_id' => $event->player->team_id,
                        ],
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}