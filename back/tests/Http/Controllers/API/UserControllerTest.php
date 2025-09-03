<?php

namespace Tests\Http\Controllers\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /**
     * Test that the users API endpoint returns a successful response.
     */
    public function test_users_api_returns_successful_response(): void
    {
        // Create some test users
        User::factory(5)->create();

        // Make GET request to users API
        $response = $this->get('/api/users');

        // Assert successful response
        $response->assertStatus(200);
    }

    /**
     * Test that the users API returns correct JSON structure.
     */
    public function test_users_api_returns_correct_json_structure(): void
    {
        // Create test users
        User::factory(3)->create();

        // Make GET request to users API
        $response = $this->get('/api/users');

        // Assert JSON structure
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'created_at'
                ]
            ],
            'count'
        ]);
    }

    /**
     * Test that the users API returns correct data format.
     */
    public function test_users_api_returns_correct_data_format(): void
    {
        // Create test users
        $users = User::factory(2)->create();

        // Make GET request to users API
        $response = $this->get('/api/users');

        // Get response data
        $responseData = $response->json();

        // Assert response format
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Users retrieved successfully', $responseData['message']);
        $this->assertCount(2, $responseData['data']);
        $this->assertEquals(2, $responseData['count']);

        // Assert that each user has required fields
        foreach ($responseData['data'] as $user) {
            $this->assertArrayHasKey('id', $user);
            $this->assertArrayHasKey('name', $user);
            $this->assertArrayHasKey('email', $user);
            $this->assertArrayHasKey('created_at', $user);

            // Assert data types
            $this->assertIsInt($user['id']);
            $this->assertIsString($user['name']);
            $this->assertIsString($user['email']);
            $this->assertIsString($user['created_at']);
        }
    }

    /**
     * Test that the users API returns users in correct order (newest first).
     */
    public function test_users_api_returns_users_in_correct_order(): void
    {
        // Create users with specific timestamps
        $olderUser = User::factory()->create(['created_at' => now()->subHours(2)]);
        $newerUser = User::factory()->create(['created_at' => now()->subHours(1)]);

        // Make GET request to users API
        $response = $this->get('/api/users');

        // Get response data
        $responseData = $response->json();

        // Assert that newer user comes first
        $this->assertEquals($newerUser->id, $responseData['data'][0]['id']);
        $this->assertEquals($olderUser->id, $responseData['data'][1]['id']);
    }

    /**
     * Test that the users API works with empty database.
     */
    public function test_users_api_works_with_empty_database(): void
    {
        // Make GET request to users API (no users in database)
        $response = $this->get('/api/users');

        // Assert successful response
        $response->assertStatus(200);

        // Get response data
        $responseData = $response->json();

        // Assert response format with empty data
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Users retrieved successfully', $responseData['message']);
        $this->assertEmpty($responseData['data']);
        $this->assertEquals(0, $responseData['count']);
    }

    /**
     * Test that the users API returns only safe user fields (no sensitive data).
     */
    public function test_users_api_does_not_expose_sensitive_data(): void
    {
        // Create test user
        User::factory()->create();

        // Make GET request to users API
        $response = $this->get('/api/users');

        // Get response data
        $responseData = $response->json();

        // Assert that sensitive fields are not exposed
        foreach ($responseData['data'] as $user) {
            $this->assertArrayNotHasKey('password', $user);
            $this->assertArrayNotHasKey('remember_token', $user);
            $this->assertArrayNotHasKey('email_verified_at', $user);
        }
    }
}
