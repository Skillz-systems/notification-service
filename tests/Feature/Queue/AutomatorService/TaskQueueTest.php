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



    public function test_it_receives_task_created__update_job_from_automator_services(): void
    {
        Queue::fake();

        $request = [
            "title" => "create DDQ",
            "id" => 1,
            "email" => "test@nnpc.com",
            'user_id' => $this->user()->id,
        ];

        TaskCreated::dispatch($request);

        Queue::assertPushed(TaskCreated::class, function ($job) use ($request) {
            return $job->getData() == $request;
        });
    }



}