<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Form;

class FormFillTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_fill_form()
    {
        $user = User::factory()->create(['role' => 'prodi']);
        $form = Form::factory()->create();

        $this->actingAs($user);

        $response = $this->post("/forms/{$form->id}/submit", [
            'response_data' => 'Sample response data',
        ]);

        $response->assertRedirect('/forms/available');
        $this->assertDatabaseHas('responses', [
            'form_id' => $form->id,
            'response_data' => 'Sample response data',
        ]);
    }
}
