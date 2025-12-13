<?php

namespace Database\Factories;

use App\Models\GameLink;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GameLinkFactory extends Factory
{
    protected $model = GameLink::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'token' => $this->faker->sha256(),
            'is_active' => true,
            'expired_at' => Carbon::now()->addDays(7),
        ];
    }
}
