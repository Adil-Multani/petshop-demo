<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCreateUserTest extends TestCase
{
    use RefreshDatabase;

    protected $adminToken;

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
    }

    public function testAdminCreateUserValidData()
    {
        $response = $this->postJson('/api/v1/admin/create', [
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'john.doe@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'address'               => '123 Main St',
            'phone_number'          => '1234567890',
            'is_marketing'          => true,
        ], [
            'Authorization' => 'Bearer ' . $this->adminToken,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                 ]);
    }

    public function testAdminCreateUserInvalidData()
    {
        $response = $this->postJson('/api/v1/admin/create', [
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'john.doe@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password456',
            'address'               => '123 Main St',
            'phone_number'          => '1234567890',
            'is_marketing'          => true,
        ], [
            'Authorization' => 'Bearer ' . $this->adminToken,
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function testAdminCreateUserExistingEmail()
    {
        // Create a user with an existing email
        \App\Models\User::factory(App\Models\User::class)->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->postJson('/api/v1/admin/create', [
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'existing@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'address'               => '123 Main St',
            'phone_number'          => '1234567890',
            'is_marketing'          => true,
        ], [
            'Authorization' => 'Bearer ' . $this->adminToken,
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function testAdminCreateUserWithoutToken()
    {
        $response = $this->postJson('/api/v1/admin/create', [
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'john.doe@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'address'               => '123 Main St',
            'phone_number'          => '1234567890',
            'is_marketing'          => true,
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }
}
