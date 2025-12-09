<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\StudySet;
use Laravel\Sanctum\Sanctum;

class CardTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_card()
    {
        // 1. Create User & Login
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // 2. Create Study Set
        $set = StudySet::create([
            'user_id' => $user->id,
            'title' => 'Test Set',
            'description' => 'Test Description'
        ]);

        // 3. Create Card Payload
        $payload = [
            'study_set_id' => $set->id,
            'japanese_word' => 'Test Word',
            'japanese_reading' => 'Test Reading',
            'meaning' => 'Test Meaning',
            'is_mastered' => false
        ];

        // 4. Send Request
        $response = $this->postJson('/api/cards', $payload);

        // 5. Assertions
        $response->assertStatus(201);
        $this->assertDatabaseHas('cards', [
            'japanese_word' => 'Test Word',
            'study_set_id' => $set->id
        ]);
    }
}
