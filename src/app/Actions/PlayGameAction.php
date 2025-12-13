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
            $number = random_int(1, 1000);
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate random number', 500);
        }
        $result = $number % 2 === 0; // Even = Win
        $winAmount = 0;

        if ($result) {
            if ($number > 900) {
                $winAmount = $number * 0.70;
            } elseif ($number > 600) {
                $winAmount = $number * 0.50;
            } elseif ($number > 300) {
                $winAmount = $number * 0.30;
            } else {
                $winAmount = $number * 0.10;
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
