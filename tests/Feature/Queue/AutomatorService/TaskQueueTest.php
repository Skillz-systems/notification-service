<?php

namespace Tests\Feature\Queue\AutomatorService;

use App\Jobs\AutomatorService\TaskUpdated;
use Tests\TestCase;
use App\Models\Task;
use Illuminate\Support\Facades\Queue;
use App\Jobs\AutomatorService\TaskCreated;
use App\Jobs\AutomatorService\TaskDeleted;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskQueueTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_it_receives_task_delete_job_from_automator_service(): void
    {

        Queue::fake();

        $task = Task::factory()->create();

        TaskDeleted::dispatch($task->id);

        Queue::assertPushed(TaskDeleted::class, function ($job) use ($task) {
            return $job->getId() == $task->id;
        });
    }


    public function test_it_receives_task_created_job_from_automator_services(): void
    {
        Queue::fake();

        $request = [
            "title" => "create DDQ",
            "id" => 1,
            "email" => "test@nnpc.com",
        ];

        TaskCreated::dispatch($request);

        Queue::assertPushed(TaskCreated::class, function ($job) use ($request) {
            return $job->getData() == $request;
        });
    }
    public function test_it_receives_task_updated_job_from_automator_services(): void
    {
        Queue::fake();

        $task = Task::factory()->create();

        $request = [
            "title" => "updated",
            "id" => $task->id,
            "status" => "completed",
        ];

        TaskUpdated::dispatch($request);

        Queue::assertPushed(TaskUpdated::class, function ($job) use ($request) {
            return $job->getData() == $request;
        });
    }
}