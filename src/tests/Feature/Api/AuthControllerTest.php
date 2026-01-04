<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'username' => 'testuser',
            'phonenumber' => '+1234567890',
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'token',
                'link_url',
                'is_active',
                'expired_at',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'phonenumber' => '+1234567890',
        ]);

        $this->assertDatabaseCount('game_links', 1);
    }

    public function test_registration_validation_fails(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'username' => '',
            'phonenumber' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'phonenumber']);
    }

    public function test_existing_user_update_phonenumber(): void
    {
        $user = User::factory()->create([
            'username' => 'existinguser',
            'phonenumber' => '+1111111111',
        ]);

        $response = $this->postJson('/api/v1/register', [
            'username' => 'existinguser',
            'phonenumber' => '+2222222222',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'phonenumber' => '+2222222222',
        ]);

        $this->assertDatabaseCount('users', 1);
    }
}
