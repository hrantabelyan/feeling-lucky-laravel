<?php

namespace App\Repositories\Contracts;

use App\Models\GameLink;
use App\Models\GameResult;
use Illuminate\Database\Eloquent\Collection;

interface GameResultRepositoryInterface
{
    public function create(GameLink $link, array $data): GameResult;
    public function getLatestForLinkUser(GameLink $link, int $limit = 3): Collection;
}
