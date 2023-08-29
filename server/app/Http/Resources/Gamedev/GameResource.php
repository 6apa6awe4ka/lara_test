<?php

namespace App\Http\Resources\Gamedev;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'studio' => new StudioResource($this->studio),
            'genres' => GenreResource::collection($this->genres),
        ];
    }
}
