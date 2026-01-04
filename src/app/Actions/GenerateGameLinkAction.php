<?php

namespace App\Actions;

use App\Models\GameLink;
use App\Models\User;
use App\Repositories\Contracts\GameLinkRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;

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

            $token = Str::random(config('app.link_token_length', 20));
            $hashedToken = hash('sha256', $token);

            $gameLink = $this->gameLinkRepository->create($user, [
                'token' => $hashedToken,
                'expired_at' => Carbon::now()->addDays(config('app.link_expiration_days', 7)),
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
