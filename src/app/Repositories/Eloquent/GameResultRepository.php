<?php

namespace App\Repositories\Eloquent;

use App\Models\GameLink;
use App\Models\GameResult;
use App\Repositories\Contracts\GameResultRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GameResultRepository implements GameResultRepositoryInterface
{
    public function create(GameLink $link, array $data): GameResult
    {
        /** @var GameResult */
        return $link->results()->create($data);
    }

    public function getLatestForLinkUser(GameLink $link, int $limit = 3): Collection
    {
        return GameResult::where('user_id', $link->user_id)
            ->latest()
            ->take($limit)
            ->get();
    }
}
