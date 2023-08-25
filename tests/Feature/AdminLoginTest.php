<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminLoginValidCredentials()
    {
        // Create an admin user
        $admin = \App\Models\User::factory(App\Models\User::class)->create([
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $response = $this->postJson('/api/v1/admin/login', [
            'email'    => $admin->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);
    }

    public function testAdminLoginInvalidCredentials()
    {
        $response = $this->postJson('/api/v1/admin/login', [
            'email'    => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Invalid credentials',
                 ]);
    }

    public function testAdminLoginMissingFields()
    {
        $response = $this->postJson('/api/v1/admin/login', []);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                 ]);
    }
}
