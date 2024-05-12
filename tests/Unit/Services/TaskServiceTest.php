<?php


use Tests\TestCase;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TaskService();
    }


    public function test_task_service_create_valid_task()
    {
        $request = [
            'id' => 1,
            'owner_id' => 1,
            'title' => 'Test Task',
            'for' => 'staff',
            'status' => 'visible',
            'content' => 'This is a test task.',
            'owner_email' => 'test@example.com',
            'url' => 'https://test.com',
        ];


        $task = $this->service->create($request);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals('staff', $task->for);
        $this->assertEquals('visible', $task->status);
        $this->assertEquals('This is a test task.', $task->content);
        $this->assertEquals('test@example.com', $task->owner_email);
        $this->assertEquals('https://test.com', $task->url);
    }

    public function test_task_service_update_valid_task()
    {
        $task = Task::factory()->create();

        $request = [
            'id' => $task->id,
            'owner_id' => 1,
            'title' => 'Updated Task',
            'for' => 'customer',
            'status' => 'hidden',
            'content' => 'This is an updated task.',
            'owner_email' => 'test@example.com',
            'url' => 'https://test.com',
        ];


        $task = $this->service->update($request, $task->id);

        $this->assertTrue($task);
    }

    public function test_task_service_destroy_task()
    {
        $task = Task::factory()->create();

        $deleted = $this->service->destroy($task->id);

        $this->assertTrue($deleted);
        $this->assertNull(Task::find($task->id));
    }

    public function test_task_service_validate_create_data_valid_data()
    {
        $this->expectException(ValidationException::class);

        $request = [
            'id' => 1,
            // 'user_id' => 1,
            // 'title' => 'Test Task',
            // 'for' => 'staff',
            'status' => 'visible',
            'content' => 'This is a test task.',
            'user_email' => 'test@example.com',
            'url' => 'https://test.com',
        ];
        $this->service->create($request);

    }
}