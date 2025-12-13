<?php

namespace App\Repositories\Contracts;

use App\Models\GameLink;
use App\Models\User;

interface GameLinkRepositoryInterface
{
    public function create(User $user, array $data): GameLink;
    public function deactivateAllForUser(User $user): void;
    public function findByToken(string $token): ?GameLink;
    public function update(GameLink $link, array $data): bool;
}
