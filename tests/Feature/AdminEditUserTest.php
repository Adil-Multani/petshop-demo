<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEditUserTest extends TestCase
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

        // Create a user for editing
        $this->user = \App\Models\User::factory(App\Models\User::class)->create();
    }

    public function testAdminEditUserValidData()
    {
        $response = $this->postJson('/api/v1/admin/user-edit/' . $this->user->uuid, [
            'first_name'            => 'Updated John',
            'last_name'             => 'Updated Doe',
            'email'                 => 'updated.john.doe@example.com',
            'password'              => 'updatedpassword123',
            'password_confirmation' => 'updatedpassword123',
            'address'               => '456 Updated St',
            'phone_number'          => '9876543210',
            'is_marketing'          => true,
        ], [
            'Authorization' => 'Bearer ' . $this->adminToken,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);
    }

    public function testAdminEditUserInvalidData()
    {
        $response = $this->postJson('/api/v1/admin/user-edit/' . $this->user->uuid, [
            'first_name'            => 'Updated John',
            'last_name'             => 'Updated Doe',
            'email'                 => 'updated.john.doe@example.com',
            'password'              => 'updatedpassword123',
            'password_confirmation' => 'updatedpassword456', // Invalid confirmation
            'address'               => '456 Updated St',
            'phone_number'          => '9876543210',
            'is_marketing'          => true,
        ], [
            'Authorization' => 'Bearer ' . $this->adminToken,
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function testAdminEditUserNonExistentUser()
    {
        $nonExistentUuid = 'non-existent-uuid';
        $response        = $this->postJson('/api/v1/admin/user-edit/' . $nonExistentUuid, [
            'first_name'            => 'Updated John',
            'last_name'             => 'Updated Doe',
            'email'                 => 'updated.john.doe@example.com',
            'password'              => 'updatedpassword123',
            'password_confirmation' => 'updatedpassword123',
            'address'               => '456 Updated St',
            'phone_number'          => '9876543210',
            'is_marketing'          => true,
        ], [
            'Authorization' => 'Bearer ' . $this->adminToken,
        ]);

        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function testAdminEditUserWithoutToken()
    {
        $response = $this->postJson('/api/v1/admin/user-edit/' . $this->user->uuid, [
            'first_name'            => 'Updated John',
            'last_name'             => 'Updated Doe',
            'email'                 => 'updated.john.doe@example.com',
            'password'              => 'updatedpassword123',
            'password_confirmation' => 'updatedpassword123',
            'address'               => '456 Updated St',
            'phone_number'          => '9876543210',
            'is_marketing'          => true,
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }
}
