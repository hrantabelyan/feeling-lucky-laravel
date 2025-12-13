<?php

namespace App\Actions;

use App\Repositories\Contracts\GameResultRepositoryInterface;
use App\Models\GameLink;
use App\Models\GameResult;

class PlayGameAction
{
    public function __construct(
        protected GameResultRepositoryInterface $gameResultRepository
    ) {}

    public function execute(GameLink $link): GameResult
    {
        try {
            $number = random_int(config('app.game_min_value', 1), config('app.game_max_value', 1000));
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate random number', 500);
        }
        $result = $number % 2 === 0; // Even = Win
        $winAmount = 0;

        if ($result) {
            if ($number > config('app.game_win_thresholds.high', 900)) {
                $winAmount = $number * config('app.game_win_multipliers.high', 0.70);
            } elseif ($number > config('app.game_win_thresholds.medium', 600)) {
                $winAmount = $number * config('app.game_win_multipliers.medium', 0.50);
            } elseif ($number > config('app.game_win_thresholds.low', 300)) {
                $winAmount = $number * config('app.game_win_multipliers.low', 0.30);
            } else {
                $winAmount = $number * config('app.game_win_multipliers.default', 0.10);
            }
        }
        
        $finalAmount = (int)($winAmount * 100);

        return $this->gameResultRepository->create($link, [
            'user_id' => $link->user_id,
            'random_number' => $number,
            'result' => $result,
            'win_amount' => $finalAmount,
        ]);
    }
}
