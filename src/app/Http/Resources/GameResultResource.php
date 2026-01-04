<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\GameResult
 */
class GameResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'random_number' => $this->random_number,
            'result' => $this->result,
            'win_amount' => $this->win_amount / 100,
            'played_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
