<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\GameLink
 */
class GameLinkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->token,
            'link_url' => url('/games/'.$this->token),
            'is_active' => $this->is_active,
            'expired_at' => $this->expired_at->format('Y-m-d H:i:s'),
        ];
    }
}
