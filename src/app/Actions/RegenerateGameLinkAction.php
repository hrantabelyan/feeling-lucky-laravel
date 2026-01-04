<?php

namespace App\Actions;

use App\Models\GameLink;
use App\Repositories\Contracts\GameLinkRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;

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
