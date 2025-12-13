<?php

namespace Tests\Unit\Models;

use App\Models\GameLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_link_has_fillable_attributes(): void
    {
        $link = new GameLink();
        $this->assertEquals(['user_id', 'token', 'expired_at', 'is_active'], $link->getFillable());
    }

    public function test_game_link_casts_attributes(): void
    {
        $link = new GameLink();
        $casts = $link->getCasts();
        
        $this->assertEquals('datetime', $casts['expired_at']);
        $this->assertEquals('datetime', $casts['created_at']);
        $this->assertEquals('datetime', $casts['updated_at']);
        $this->assertEquals('datetime', $casts['deleted_at']);
        $this->assertEquals('boolean', $casts['is_active']);
    }

    public function test_game_link_belongs_to_user(): void
    {
        $link = GameLink::factory()->create();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $link->user());
        $this->assertInstanceOf(User::class, $link->user);
    }

    public function test_game_link_has_many_results(): void
    {
        $link = GameLink::factory()->create();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $link->results());
    }

    public function test_valid_scope_returns_active_and_non_expired_links(): void
    {
        // Active and not expired
        GameLink::factory()->create([
            'is_active' => true,
            'expired_at' => now()->addDay(),
        ]);

        // Inactive
        GameLink::factory()->create([
            'is_active' => false,
            'expired_at' => now()->addDay(),
        ]);

        // Expired
        GameLink::factory()->create([
            'is_active' => true,
            'expired_at' => now()->subDay(),
        ]);

        $this->assertEquals(1, GameLink::valid()->count());
    }
}
