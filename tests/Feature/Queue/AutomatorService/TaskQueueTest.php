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
            'id' => 20,
            'processflow_history_id' => 1,
            'formbuilder_data_id' => 2,
            'entity_id' => 3,
            'entity_type' => 'customer',
            'user_id' => 4,
            'processflow_id' => 5,
            'processflow_step_id' => 6,
            'title' => 'Create DDQ',
            'route' => 'https://example.com/create-ddq',
            'start_time' => '2023-05-15',
            'end_time' => '2023-05-20',
            'task_status' => 0,
        ];
        TaskCreated::dispatch($request);

        Queue::assertPushed(TaskCreated::class, function ($job) use ($request) {
            return $job->getData() == $request;
        });
    }



}
