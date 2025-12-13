<?php

namespace Tests\Unit\Models;

use App\Models\GameLink;
use App\Models\GameResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_result_has_fillable_attributes(): void
    {
        $result = new GameResult();
        $this->assertEquals(['user_id', 'game_link_id', 'random_number', 'result', 'win_amount'], $result->getFillable());
    }

    public function test_game_result_casts_attributes(): void
    {
        $result = new GameResult();
        $casts = $result->getCasts();
        
        $this->assertEquals('boolean', $casts['result']);
        $this->assertEquals('datetime', $casts['created_at']);
        $this->assertEquals('datetime', $casts['updated_at']);
        $this->assertEquals('datetime', $casts['deleted_at']);
    }

    public function test_game_result_belongs_to_user(): void
    {
        $result = GameResult::factory()->create();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $result->user());
        $this->assertInstanceOf(User::class, $result->user);
    }

    public function test_game_result_belongs_to_link(): void
    {
        $result = GameResult::factory()->create();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $result->link());
        $this->assertInstanceOf(GameLink::class, $result->link);
    }
}
