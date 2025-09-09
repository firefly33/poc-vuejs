<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private TaskService $taskService
    ) {}

    /**
     * Display a listing of tasks.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Get status filter from query parameters
        $status = $request->query('status');

        // Validate status if provided
        if ($status && !in_array($status, $this->taskService->getValidStatuses())) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status provided',
                'valid_statuses' => $this->taskService->getValidStatuses(),
            ], 400);
        }

        // Get tasks from service
        $tasks = $this->taskService->getAllTasks($status);

        return response()->json([
            'success' => true,
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks,
            'count' => $tasks->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
