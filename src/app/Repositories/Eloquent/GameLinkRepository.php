<?php

namespace App\Repositories\Eloquent;

use App\Models\GameLink;
use App\Models\User;
use App\Repositories\Contracts\GameLinkRepositoryInterface;

class GameLinkRepository implements GameLinkRepositoryInterface
{
    public function create(User $user, array $data): GameLink
    {
        /** @var GameLink */
        return $user->gameLinks()->create($data);
    }

    public function deactivateAllForUser(User $user): void
    {
        $user->gameLinks()->where('is_active', true)->update(['is_active' => false]);
    }

    public function findByToken(string $token): ?GameLink
    {
        return GameLink::where('token', hash('sha256', $token))->first();
    }

    public function update(GameLink $link, array $data): bool
    {
        return $link->update($data);
    }
}
