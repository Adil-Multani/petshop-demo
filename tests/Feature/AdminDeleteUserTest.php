<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDeleteUserTest extends TestCase
{
    use RefreshDatabase;

    protected $adminToken;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // Create an admin user and generate a JWT token
        $admin = \App\Models\User::factory(App\Models\User::class)->create([
            'email'    => 'admin@example.com',
            'password' => bcrypt('adminpassword'),
            'is_admin' => true,
        ]);

        $this->adminToken = generateToken($admin); // Adjust this to generate a valid JWT token

        // Create a user for deleting
        $this->user = \App\Models\User::factory(App\Models\User::class)->create();
    }

    public function testAdminDeleteUserValid()
    {
        $response = $this->deleteJson('/api/v1/admin/user-delete/' . $this->user->uuid, [], [
            'Authorization' => 'Bearer ' . $this->adminToken,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);
    }

    public function testAdminDeleteUserNonExistentUser()
    {
        $nonExistentUuid = 'non-existent-uuid';
        $response        = $this->deleteJson('/api/v1/admin/user-delete/' . $nonExistentUuid, [], [
            'Authorization' => 'Bearer ' . $this->adminToken,
        ]);

        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function testAdminDeleteUserWithoutToken()
    {
        $response = $this->deleteJson('/api/v1/admin/user-delete/' . $this->user->uuid);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }
}
