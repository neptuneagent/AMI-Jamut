<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Response;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_provide_rating()
    {
        $user = User::factory()->create(['role' => 'auditor']);
        $response = Response::factory()->create();

        $this->actingAs($user);

        $response = $this->post("/responses/{$response->id}/add-finding", [
            'rating' => 5,
            'comments' => 'Well done',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('ratings', [
            'response_id' => $response->id,
            'rating' => 5,
            'comments' => 'Well done',
        ]);
    }
}
