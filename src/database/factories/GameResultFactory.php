<?php

namespace Database\Factories;

use App\Models\GameLink;
use App\Models\GameResult;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameResultFactory extends Factory
{
    protected $model = GameResult::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'game_link_id' => GameLink::factory(),
            'random_number' => $this->faker->numberBetween(1, 1000),
            'result' => $this->faker->boolean(),
            'win_amount' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
