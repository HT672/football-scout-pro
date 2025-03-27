<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'name' => $this->name,
            'country' => $this->country,
            'league' => $this->league,
            'manager' => $this->manager,
            'stadium' => $this->stadium,
            'founded' => $this->founded,
            'description' => $this->description,
            'logo_url' => $this->logo ? asset('storage/' . $this->logo) : null,
            'players_count' => $this->when(isset($this->players_count), $this->players_count),
            'players' => PlayerResource::collection($this->whenLoaded('players')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}