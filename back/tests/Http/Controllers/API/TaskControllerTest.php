<?php

namespace Tests\Http\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_tasks_api_returns_successful_response(): void
    {
        $response = $this->get('/api/tasks');

        $response->assertStatus(200);
    }
}
