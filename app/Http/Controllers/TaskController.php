<?php



namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Resources\TaskCollection;
use Illuminate\Http\Resources\Json\JsonResource;

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
     *     summary="Get tasks by user ID and status",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="paginate",
     *         in="query",
     *         description="Pagination count",
     *         required=false,
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of tasks",
     *         @OA\JsonContent(ref="#/components/schemas/TaskCollection")
     *     )
     * )
     */
    public function show(int $id, int $paginate = 20): JsonResource
    {
        $tasks = $this->service->getTasksByUserIdAndStatus($id, Task::PENDING);
        return new TaskCollection($tasks);
    }


}