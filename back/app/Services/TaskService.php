<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    /**
     * Get all tasks with optional status filtering.
     *
     * @param  string|null  $status
     * @return Collection
     */
    public function getAllTasks(?string $status = null): Collection
    {
        $query = Task::newestFirst();

        if ($status && in_array($status, Task::getValidStatuses())) {
            $query->byStatus($status);
        }

        return $query->get();
    }

    /**
     * Get tasks count by status.
     *
     * @param  string|null  $status
     * @return int
     */
    public function getTasksCount(?string $status = null): int
    {
        $query = Task::query();

        if ($status && in_array($status, Task::getValidStatuses())) {
            $query->byStatus($status);
        }

        return $query->count();
    }

    /**
     * Create a new task.
     *
     * @param  array  $data
     * @return Task
     */
    public function createTask(array $data): Task
    {
        return Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? Task::STATUS_TODO,
        ]);
    }

    /**
     * Update an existing task.
     *
     * @param  Task  $task
     * @param  array  $data
     * @return Task
     */
    public function updateTask(Task $task, array $data): Task
    {
        $task->update([
            'title' => $data['title'] ?? $task->title,
            'description' => $data['description'] ?? $task->description,
            'status' => $data['status'] ?? $task->status,
        ]);

        return $task->fresh();
    }

    /**
     * Delete a task.
     *
     * @param  Task  $task
     * @return bool
     */
    public function deleteTask(Task $task): bool
    {
        return $task->delete();
    }

    /**
     * Get valid status values.
     *
     * @return array
     */
    public function getValidStatuses(): array
    {
        return Task::getValidStatuses();
    }
}