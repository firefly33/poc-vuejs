<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class TasksApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test that the tasks API endpoint returns a successful response.
     */
    public function test_tasks_api_returns_successful_response(): void
    {
        // Create some test tasks
        Task::factory(3)->create();

        // Make GET request to tasks API
        $response = $this->get('/api/tasks');

        // Assert successful response
        $response->assertStatus(200);
    }

    /**
     * Test that the tasks API returns correct JSON structure.
     */
    public function test_tasks_api_returns_correct_json_structure(): void
    {
        // Create test tasks
        Task::factory(2)->create();

        // Make GET request to tasks API
        $response = $this->get('/api/tasks');

        // Assert JSON structure
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'created_at',
                    'updated_at'
                ]
            ],
            'count'
        ]);
    }

    /**
     * Test that the tasks API returns correct data format.
     */
    public function test_tasks_api_returns_correct_data_format(): void
    {
        // Create test tasks
        $tasks = Task::factory(2)->create();

        // Make GET request to tasks API
        $response = $this->get('/api/tasks');

        // Get response data
        $responseData = $response->json();

        // Assert response format
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Tasks retrieved successfully', $responseData['message']);
        $this->assertCount(2, $responseData['data']);
        $this->assertEquals(2, $responseData['count']);

        // Assert that each task has required fields
        foreach ($responseData['data'] as $task) {
            $this->assertArrayHasKey('id', $task);
            $this->assertArrayHasKey('title', $task);
            $this->assertArrayHasKey('description', $task);
            $this->assertArrayHasKey('status', $task);
            $this->assertArrayHasKey('created_at', $task);
            $this->assertArrayHasKey('updated_at', $task);
            
            // Assert data types
            $this->assertIsInt($task['id']);
            $this->assertIsString($task['title']);
            $this->assertIsString($task['status']);
            $this->assertIsString($task['created_at']);
            $this->assertIsString($task['updated_at']);
            
            // Assert status values are valid
            $this->assertContains($task['status'], ['todo', 'in-progress', 'done']);
        }
    }

    /**
     * Test that the tasks API returns tasks ordered by creation date (newest first).
     */
    public function test_tasks_api_returns_tasks_ordered_by_newest_first(): void
    {
        // Create tasks with specific timestamps
        $olderTask = Task::factory()->create(['created_at' => now()->subHours(2)]);
        $newerTask = Task::factory()->create(['created_at' => now()->subHours(1)]);

        // Make GET request to tasks API
        $response = $this->get('/api/tasks');

        // Get response data
        $responseData = $response->json();

        // Assert that newer task comes first
        $this->assertEquals($newerTask->id, $responseData['data'][0]['id']);
        $this->assertEquals($olderTask->id, $responseData['data'][1]['id']);
    }

    /**
     * Test that the tasks API works with empty database.
     */
    public function test_tasks_api_works_with_empty_database(): void
    {
        // Make GET request to tasks API (no tasks in database)
        $response = $this->get('/api/tasks');

        // Assert successful response
        $response->assertStatus(200);

        // Get response data
        $responseData = $response->json();

        // Assert response format with empty data
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Tasks retrieved successfully', $responseData['message']);
        $this->assertEmpty($responseData['data']);
        $this->assertEquals(0, $responseData['count']);
    }

    /**
     * Test that tasks can be filtered by status.
     */
    public function test_tasks_api_can_be_filtered_by_status(): void
    {
        // Create tasks with different statuses
        Task::factory()->create(['status' => 'todo']);
        Task::factory()->create(['status' => 'in-progress']);
        Task::factory()->create(['status' => 'done']);

        // Test filtering by 'todo' status
        $response = $this->get('/api/tasks?status=todo');
        $responseData = $response->json();

        $this->assertEquals(1, $responseData['count']);
        $this->assertEquals('todo', $responseData['data'][0]['status']);

        // Test filtering by 'in-progress' status
        $response = $this->get('/api/tasks?status=in-progress');
        $responseData = $response->json();

        $this->assertEquals(1, $responseData['count']);
        $this->assertEquals('in-progress', $responseData['data'][0]['status']);
    }
}