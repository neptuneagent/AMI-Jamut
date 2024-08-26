<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_a_login_form()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login'); // Sesuaikan dengan konten halaman login Anda
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'), // Pastikan kata sandi yang sesuai dengan hash yang dihasilkan
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/home'); // Ubah sesuai dengan rute setelah login berhasil
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'), // Pastikan kata sandi yang sesuai dengan hash yang dihasilkan
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email'); // Laravel Breeze dan Jetstream memberikan kesalahan ini
        $this->assertGuest();
    }

    /** @test */
    public function remember_me_functionality()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'), // Pastikan kata sandi yang sesuai dengan hash yang dihasilkan
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => 'on',
        ]);

        $response->assertRedirect('/home'); // Ubah sesuai dengan rute setelah login berhasil
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($response->headers->getCookies()[0]); // Pastikan cookie remember me dibuat
    }
}
