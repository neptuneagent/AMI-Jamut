<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class FormCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_form()
    {
        $this->actingAs(User::factory()->create(['role' => 'jamut']));

        $response = $this->post('/forms', [
            'title' => 'Test Form',
            'description' => 'This is a test form.',
        ]);

        $response->assertRedirect('/forms');
        $this->assertDatabaseHas('forms', [
            'title' => 'Test Form',
        ]);
    }
}
