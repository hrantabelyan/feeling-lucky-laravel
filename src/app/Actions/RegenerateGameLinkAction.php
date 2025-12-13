<?php

namespace App\Actions;

use App\Repositories\Contracts\GameLinkRepositoryInterface;
use App\Models\GameLink;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegenerateGameLinkAction
{
    public function __construct(
        protected GameLinkRepositoryInterface $gameLinkRepository
    ) {}

    public function execute(GameLink $link): GameLink
    {
        try {
            $token = Str::random(config('app.link_token_length', 20));
            $hashedToken = hash('sha256', $token);

            $this->gameLinkRepository->update($link, [
                'token' => $hashedToken,
                'expired_at' => Carbon::now()->addDays(config('app.link_expiration_days', 7)),
                'is_active' => true,
            ]);

            // Return the plain token so the user can see it once
            $link->token = $token;

            return $link;
        } catch (\Exception $e) {
            throw new \Exception('Failed to regenerate game link', 500);
        }
    }
}
