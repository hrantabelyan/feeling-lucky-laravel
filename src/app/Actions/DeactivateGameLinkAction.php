<?php

namespace App\Actions;

use App\Models\GameLink;
use App\Repositories\Contracts\GameLinkRepositoryInterface;

class DeactivateGameLinkAction
{
    public function __construct(
        protected GameLinkRepositoryInterface $gameLinkRepository
    ) {}

    public function execute(GameLink $link): bool
    {
        try {
            return $this->gameLinkRepository->update($link, ['is_active' => false]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to deactivate game link', 500);
        }
    }
}
