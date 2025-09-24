<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $taskService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskService = new TaskService();
    }

    public function test_get_all_tasks_returns_collection()
    {
        Task::factory()->count(3)->create();

        $result = $this->taskService->getAllTasks();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_get_all_tasks_orders_by_newest_first()
    {
        $oldTask = Task::factory()->create(['created_at' => now()->subDay()]);
        $newTask = Task::factory()->create(['created_at' => now()]);

        $result = $this->taskService->getAllTasks();

        $this->assertEquals($newTask->id, $result->first()->id);
        $this->assertEquals($oldTask->id, $result->last()->id);
    }

    public function test_get_all_tasks_filters_by_status()
    {
        Task::factory()->create(['status' => Task::STATUS_TODO]);
        Task::factory()->create(['status' => Task::STATUS_IN_PROGRESS]);
        Task::factory()->count(2)->create(['status' => Task::STATUS_DONE]);

        $todoTasks = $this->taskService->getAllTasks(Task::STATUS_TODO);
        $doneTasks = $this->taskService->getAllTasks(Task::STATUS_DONE);

        $this->assertCount(1, $todoTasks);
        $this->assertCount(2, $doneTasks);
        $this->assertEquals(Task::STATUS_TODO, $todoTasks->first()->status);
        $this->assertEquals(Task::STATUS_DONE, $doneTasks->first()->status);
    }

    public function test_get_all_tasks_ignores_invalid_status()
    {
        Task::factory()->count(3)->create();

        $result = $this->taskService->getAllTasks('invalid-status');

        $this->assertCount(3, $result);
    }

    public function test_get_tasks_count_returns_correct_count()
    {
        Task::factory()->count(5)->create();

        $count = $this->taskService->getTasksCount();

        $this->assertEquals(5, $count);
    }

    public function test_get_tasks_count_filters_by_status()
    {
        Task::factory()->count(2)->create(['status' => Task::STATUS_TODO]);
        Task::factory()->count(3)->create(['status' => Task::STATUS_DONE]);

        $todoCount = $this->taskService->getTasksCount(Task::STATUS_TODO);
        $doneCount = $this->taskService->getTasksCount(Task::STATUS_DONE);

        $this->assertEquals(2, $todoCount);
        $this->assertEquals(3, $doneCount);
    }

    public function test_create_task_creates_task_with_provided_data()
    {
        $data = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => Task::STATUS_IN_PROGRESS,
        ];

        $task = $this->taskService->createTask($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals('Test Description', $task->description);
        $this->assertEquals(Task::STATUS_IN_PROGRESS, $task->status);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_create_task_uses_default_status_when_not_provided()
    {
        $data = [
            'title' => 'Test Task',
            'description' => 'Test Description',
        ];

        $task = $this->taskService->createTask($data);

        $this->assertEquals(Task::STATUS_TODO, $task->status);
    }

    public function test_create_task_handles_null_description()
    {
        $data = [
            'title' => 'Test Task',
        ];

        $task = $this->taskService->createTask($data);

        $this->assertEquals('Test Task', $task->title);
        $this->assertNull($task->description);
        $this->assertEquals(Task::STATUS_TODO, $task->status);
    }

    public function test_update_task_updates_provided_fields()
    {
        $task = Task::factory()->create([
            'title' => 'Original Title',
            'description' => 'Original Description',
            'status' => Task::STATUS_TODO,
        ]);

        $data = [
            'title' => 'Updated Title',
            'status' => Task::STATUS_DONE,
        ];

        $updatedTask = $this->taskService->updateTask($task, $data);

        $this->assertEquals('Updated Title', $updatedTask->title);
        $this->assertEquals('Original Description', $updatedTask->description);
        $this->assertEquals(Task::STATUS_DONE, $updatedTask->status);
    }

    public function test_update_task_preserves_existing_values_when_not_provided()
    {
        $task = Task::factory()->create([
            'title' => 'Original Title',
            'description' => 'Original Description',
            'status' => Task::STATUS_TODO,
        ]);

        $data = ['title' => 'New Title'];

        $updatedTask = $this->taskService->updateTask($task, $data);

        $this->assertEquals('New Title', $updatedTask->title);
        $this->assertEquals('Original Description', $updatedTask->description);
        $this->assertEquals(Task::STATUS_TODO, $updatedTask->status);
    }

    public function test_update_task_returns_fresh_instance()
    {
        $task = Task::factory()->create();
        $originalUpdatedAt = $task->updated_at;

        // Ensure time passes
        sleep(1);

        $updatedTask = $this->taskService->updateTask($task, ['title' => 'New Title']);

        $this->assertNotEquals($originalUpdatedAt, $updatedTask->updated_at);
        $this->assertEquals('New Title', $updatedTask->title);
    }

    public function test_delete_task_removes_task_from_database()
    {
        $task = Task::factory()->create();
        $taskId = $task->id;

        $result = $this->taskService->deleteTask($task);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('tasks', ['id' => $taskId]);
    }

    public function test_get_valid_statuses_returns_correct_statuses()
    {
        $validStatuses = $this->taskService->getValidStatuses();

        $expected = [
            Task::STATUS_TODO,
            Task::STATUS_IN_PROGRESS,
            Task::STATUS_DONE,
        ];

        $this->assertEquals($expected, $validStatuses);
    }
}