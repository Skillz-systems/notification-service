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

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Get a task by ID of staff",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="paginate",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function show(int $id, int $paginate = 20)
    {
        $tasks = $this->service->getTasksByOwner($id, $paginate);
        return new TaskCollection($tasks);
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}/filter",
     *     summary="Get tasks with filters",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="paginate",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tasks not found"
     *     )
     * )
     */

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