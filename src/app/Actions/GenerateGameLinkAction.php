<?php

namespace App\Actions;

use App\Repositories\Contracts\GameLinkRepositoryInterface;
use App\Models\User;
use App\Models\GameLink;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GenerateGameLinkAction
{
    public function __construct(
        protected GameLinkRepositoryInterface $gameLinkRepository
    ) {}

    public function execute(User $user, ?GameLink $linkToDeactivate = null): GameLink
    {
        try {
            if ($linkToDeactivate) {
                $this->gameLinkRepository->update($linkToDeactivate, ['is_active' => false]);
            } else {
                $this->gameLinkRepository->deactivateAllForUser($user);
            }

            $token = Str::random(20);
            $hashedToken = hash('sha256', $token);

            $gameLink = $this->gameLinkRepository->create($user, [
                'token' => $hashedToken,
                'expired_at' => Carbon::now()->addDays(7),
                'is_active' => true,
            ]);

            // Return the plain token so the user can see it once
            $gameLink->token = $token;

            return $gameLink;
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate game link', 500);
        }
    }
}
