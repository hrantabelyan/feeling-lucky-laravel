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
            'random_number' => $this->faker->numberBetween(config('app.game_min_value', 1), config('app.game_max_value', 1000)),
            'result' => $this->faker->boolean(),
            'win_amount' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
