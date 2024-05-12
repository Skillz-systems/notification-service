<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Resources\TaskCollection;


/**
 * @OA\Info(
 *     description="API endpoints for managing tasks",
 *     version="1.0.0",
 *     title="Task API"
 * )
 */
class TaskController extends Controller
{

    protected $service;

    public function __construct(TaskService $taskService)
    {
        $this->service = $taskService;
    }


    public function show(int $id, int $paginate = 20)
    {
        $tasks = $this->service->getTasksByOwner($id, $paginate);
        return new TaskCollection($tasks);
    }



    public function filter(int $id, int $paginate = 20)
    {
        $filters = [
            'title' => 'Important',
            'due_at' => '2023-05-15',
            'for' => 'staff',
            'sort_column' => 'due_at',
            'sort_direction' => 'desc',
        ];
        $tasks = $$this->service->getTasksWithFilters($id, $filters, $paginate);
        return new TaskCollection($tasks);
    }




}