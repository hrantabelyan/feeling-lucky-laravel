<?php

namespace Tests\Feature\Api;

use App\Models\GameLink;
use App\Models\GameResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_game_link(): void
    {
        $token = 'valid-token';
        $hashedToken = hash('sha256', $token);

        $link = GameLink::factory()->create([
            'token' => $hashedToken,
        ]);

        $response = $this->getJson("/api/v1/games/{$token}");

        $response->assertOk()
            ->assertJson([
                'valid' => true,
                'data' => [
                    'username' => $link->user->username,
                ],
            ]);
    }

    public function test_cannot_access_expired_link(): void
    {
        $token = 'expired-token';
        $hashedToken = hash('sha256', $token);

        $link = GameLink::factory()->create([
            'token' => $hashedToken,
            'expired_at' => Carbon::now()->subDay(),
        ]);

        $response = $this->getJson("/api/v1/games/{$token}");

        $response->assertNotFound();
    }

    public function test_can_play_game(): void
    {
        $token = 'play-token';
        $hashedToken = hash('sha256', $token);

        $link = GameLink::factory()->create([
            'token' => $hashedToken,
        ]);

        $response = $this->postJson("/api/v1/games/{$token}/play");

        $response->assertCreated();
        $this->assertDatabaseCount('game_results', 1);
    }

    public function test_can_get_history(): void
    {
        $token = 'history-token';
        $hashedToken = hash('sha256', $token);

        $link = GameLink::factory()->create([
            'token' => $hashedToken,
        ]);

        GameResult::factory()->count(3)->create(['game_link_id' => $link->id, 'user_id' => $link->user_id]);

        $response = $this->getJson("/api/v1/games/{$token}/results");

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_regenerate_link(): void
    {
        $token = 'regenerate-token';
        $hashedToken = hash('sha256', $token);

        $link = GameLink::factory()->create([
            'token' => $hashedToken,
        ]);

        $response = $this->postJson("/api/v1/games/{$token}/regenerate");

        $response->assertCreated()
            ->assertJsonStructure([
                'token',
                'link_url',
                'is_active',
                'expired_at',
            ]);

        $this->assertDatabaseHas('game_links', [
            'id' => $link->id,
            'is_active' => true,
        ]);

        // Ensure token changed
        $link->refresh();
        $this->assertNotEquals($hashedToken, $link->token);
    }

    public function test_can_deactivate_link(): void
    {
        $token = 'deactivate-token';
        $hashedToken = hash('sha256', $token);

        $link = GameLink::factory()->create([
            'token' => $hashedToken,
            'is_active' => true,
        ]);

        $response = $this->postJson("/api/v1/games/{$token}/deactivate");

        $response->assertOk();

        $this->assertDatabaseHas('game_links', [
            'id' => $link->id,
            'is_active' => false,
        ]);
    }
}
