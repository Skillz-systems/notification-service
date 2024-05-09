<?php


use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $service;

    protected $user;



    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TaskService();
        $this->user = User::factory()->create();
    }


    public function test_task_service_create_valid_task()
    {


        $request = [
            'id' => 1,
            'user_id' => $this->user->id,
            'title' => 'Test Task',
            'for' => 'staff',
            'status' => 'visible',
            'content' => 'This is a test task.',
            'user_email' => 'test@example.com',
            'url' => 'https://test.com',
        ];


        $task = $this->service->create($request);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals('staff', $task->for);
        $this->assertEquals('visible', $task->status);
        $this->assertEquals('This is a test task.', $task->content);
        $this->assertEquals('test@example.com', $task->user_email);
        $this->assertEquals('https://test.com', $task->url);
    }

    public function test_task_service_update_valid_task()
    {

        $task = Task::factory(['user_id' => $this->user->id])->create();

        $request = [
            'id' => $task->id,
            'user_id' => $this->user->id,
            'title' => 'Updated Task',
            'for' => 'customer',
            'status' => 'hidden',
            'content' => 'This is an updated task.',
            'user_email' => 'test@example.com',
            'url' => 'https://test.com',
        ];


        $task = $this->service->update($request, $task->id);

        $this->assertTrue($task);
    }

    public function test_task_service_destroy_task()
    {
        $task = Task::factory(['user_id' => $this->user->id])->create();

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


    // public function test_getTasksByUserId_returns_tasks_for_given_user_id()
    // {
    //     $user_id = 1;
    //     $task = factory(Task::class)->create(['user_id' => $user_id]);
    //     $task2 = factory(Task::class)->create(['user_id' => $user_id]);
    //     $task3 = factory(Task::class)->create(['user_id' => 2]);

    //     $taskService = new TaskService();
    //     $tasks = $taskService->getTasksByUserId($user_id);

    //     $this->assertEquals(2, $tasks->count());
    //     $this->assertTrue($tasks->contains($task));
    //     $this->assertTrue($tasks->contains($task2));
    //     $this->assertFalse($tasks->contains($task3));
    // }

    // public function test_getTasksByUserIdAndStatus_returns_tasks_for_given_user_id_and_status()
    // {
    //     $user_id = 1;
    //     $task = factory(Task::class)->create(['user_id' => $user_id, 'status' => 'completed']);
    //     $task2 = factory(Task::class)->create(['user_id' => $user_id, 'status' => 'hidden']);
    //     $task3 = factory(Task::class)->create(['user_id' => 2, 'status' => 'completed']);

    //     $taskService = new TaskService();
    //     $tasks = $taskService->getTasksByUserIdAndStatus($user_id, 'completed');

    //     $this->assertEquals(1, $tasks->count());
    //     $this->assertTrue($tasks->contains($task));
    //     $this->assertFalse($tasks->contains($task2));
    //     $this->assertFalse($tasks->contains($task3));
    // }

    // public function test_getTasksByUserIdAndStatus_returns_empty_array_when_no_tasks_found()
    // {
    //     $user_id = 1;
    //     $taskService = new TaskService();
    //     $tasks = $taskService->getTasksByUserIdAndStatus($user_id, 'completed');

    //     $this->assertEquals(0, $tasks->count());
    // }

    // public function test_getTasksByUserIdAndStatus_returns_tasks_for_given_user_id_and_invalid_status()
    // {
    //     $user_id = 1;
    //     $task = factory(Task::class)->create(['user_id' => $user_id, 'status' => 'completed']);
    //     $task2 = factory(Task::class)->create(['user_id' => $user_id, 'status' => 'hidden']);

    //     $taskService = new TaskService();
    //     $tasks = $taskService->getTasksByUserIdAndStatus($user_id, 'invalid_status');

    //     $this->assertEquals(1, $tasks->count());
    //     $this->assertTrue($tasks->contains($task));
    //     $this->assertTrue($tasks->contains($task2));
    // }

    // public function test_getTasksByUserIdAndStatus_returns_tasks_for_given_user_id_and_status_case_insensitive()
    // {
    //     $user_id = 1;
    //     $task = factory(Task::class)->create(['user_id' => $user_id, 'status' => 'completed']);
    //     $task2 = factory(Task::class)->create(['user_id' => $user_id, 'status' => 'hidden']);

    //     $taskService = new TaskService();
    //     $tasks = $taskService->getTasksByUserIdAndStatus($user_id, 'cOmPleTed');

    //     $this->assertEquals(1, $tasks->count());
    //     $this->assertTrue($tasks->contains($task));
    //     $this->assertTrue($tasks->contains($task2));
    // }
}