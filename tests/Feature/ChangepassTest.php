<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class PasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_change_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt($oldPassword = 'old-password'),
        ]);

        $this->actingAs($user);

        $response = $this->put('/admin/settings/update-password', [
            'old_password' => $oldPassword,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertStatus(200);
        $this->assertTrue(\Hash::check('new-password', $user->fresh()->password));
    }
}
