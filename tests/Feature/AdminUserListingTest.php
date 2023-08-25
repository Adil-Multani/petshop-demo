<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserListingTest extends TestCase
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

    public function testAdminUserListingValid()
    {
        \App\Models\User::factory(App\Models\User::class)->create(['is_marketing' => true]);
        \App\Models\User::factory(App\Models\User::class)->create(['is_marketing' => false]);
        \App\Models\User::factory(App\Models\User::class)->create(['is_marketing' => true]);

        $response = $this->getJson('/api/v1/admin/user-listing', [
            'Authorization' => 'Bearer ' . $this->adminToken,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);
    }

    public function testAdminUserListingWithFilters()
    {
        $response = $this->getJson('/api/v1/admin/user-listing', [
            'Authorization' => 'Bearer ' . $this->adminToken,
            'is_marketing'  => true, // Apply filter for is_marketing = true
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);
    }

    public function testUserListingSortByValidColumn()
    {
        // Create users with different created_at timestamps
        \App\Models\User::factory(App\Models\User::class)->create(['created_at' => now()->subDays(2)]);
        \App\Models\User::factory(App\Models\User::class)->create(['created_at' => now()->subDays(1)]);
        \App\Models\User::factory(App\Models\User::class)->create(['created_at' => now()]);

        $response = $this->getJson('/api/v1/admin/user-listing', [
            'Authorization' => 'Bearer ' . $this->adminToken,
            'sort_by'       => 'created_at', // Sort by valid column 'created_at'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);
    }

    public function testAdminUserListingWithoutToken()
    {
        $response = $this->getJson('/api/v1/admin/user-listing');

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }
}
