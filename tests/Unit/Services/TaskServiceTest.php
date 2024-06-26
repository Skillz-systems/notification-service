<?php

use Tests\TestCase;
use App\Models\NotificationTask;
use App\Models\User;
use App\Services\NotificationTaskService;
use App\Http\Resources\TaskCollection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private NotificationTaskService $service;

    protected $user;



    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NotificationTaskService();
        $this->user = User::factory()->create();
    }

    public function test_task_service_create_valid_task()
    {


        $request = [
            'id' => 40,
            'processflow_history_id' => 1,
            'formbuilder_data_id' => 2,
            'entity_id' => 3,
            'entity_type' => 'customer',
            'user_id' => $this->user->id,
            'processflow_id' => 5,
            'processflow_step_id' => 6,
            'title' => 'Create DDQ',
            'route' => 'https://example.com/create-ddq',
            'start_time' => '2023-05-15',
            'end_time' => '2023-05-20',
            'task_status' => 0,
        ];

        $task = $this->service->create($request);

        $this->assertInstanceOf(NotificationTask::class, $task);
        $this->assertEquals(1, $task->processflow_history_id);
        $this->assertEquals(2, $task->formbuilder_data_id);
        $this->assertEquals(3, $task->entity_id);
        $this->assertEquals('customer', $task->entity_type);
        $this->assertEquals($this->user->id, $task->user_id);
        $this->assertEquals(5, $task->processflow_id);
        $this->assertEquals(6, $task->processflow_step_id);
        $this->assertEquals('Create DDQ', $task->title);
        $this->assertEquals('https://example.com/create-ddq', $task->route);
        $this->assertEquals('2023-05-15', $task->start_time);
        $this->assertEquals('2023-05-20', $task->end_time);
        $this->assertEquals(0, $task->task_status);
    }

    public function test_task_service_update_valid_task()
    {

        $task = NotificationTask::factory(['user_id' => $this->user->id])->create();

        $request = [
            'id' => $task->id,
            'processflow_history_id' => 7,
            'formbuilder_data_id' => 8,
            'entity_id' => 9,
            'entity_type' => 'supplier',
            'user_id' => $this->user->id,
            'processflow_id' => 11,
            'processflow_step_id' => 12,
            'title' => 'Update DDQ',
            'route' => 'https://example.com/update-ddq',
            'start_time' => '2023-05-16',
            'end_time' => '2023-05-21',
            'task_status' => 1,
        ];

        $updated = $this->service->update($request, $task->id);

        $this->assertTrue($updated);
    }

    public function test_task_service_destroy_task()
    {
        $task = NotificationTask::factory(['user_id' => $this->user->id])->create();
        $deleted = $this->service->destroy($task->id);
        $this->assertTrue($deleted);
        $this->assertNull(NotificationTask::find($task->id));
    }

    public function test_task_service_validate_create_data_valid_data()
    {
        $this->expectException(ValidationException::class);

        $request = [
            // 'processflow_history_id' => 1,
            // 'formbuilder_data_id' => 2,
            // 'entity_id' => 3,
            // 'entity_type' => 'customer',
            // 'user_id' => 4,
            // 'processflow_id' => 5,
            // 'processflow_step_id' => 6,
            // 'title' => 'Create DDQ',
            // 'route' => 'https://example.com/create-ddq',
            // 'start_time' => '2023-05-15',
            // 'end_time' => '2023-05-20',
            // 'task_status' => 0,
        ];

        $this->service->create($request);
    }
}