<?php


use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use App\Http\Resources\TaskCollection;
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
    }

    public function test_task_service_update_valid_task()
    {

        $task = Task::factory(['owner_id' => $this->user->id])->create();

        $request = [
            'id' => $task->id,
            'owner_id' => $this->user->id,
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
        $task = Task::factory(['owner_id' => $this->user->id, 'for' => 'staff',])->create();

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
            'owner_email' => 'test@example.com',
            'url' => 'https://test.com',
        ];
        $this->service->create($request);

    }


    public function test_to_getTasksByUserId_returns_tasks_for_given_user_id()
    {

        Task::factory(['owner_id' => $this->user->id, 'for' => 'staff'])->count(3)->create();

        $tasks = $this->service->getTasksByUserId($this->user->id);
        // Assertions
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $tasks);
        $this->assertEquals(3, $tasks->count());


    }

    public function test_getTasksByUserIdAndStatus_returns_tasks_for_given_user_id_and_status()
    {
        $task = Task::factory(['owner_id' => $this->user->id, 'status' => 'completed'])->create();
        $task2 = Task::factory(['owner_id' => $this->user->id, 'status' => 'hidden'])->create();
        $task3 = Task::factory(['owner_id' => $this->user->id, 'status' => 'completed'])->create();

        $tasks = $this->service->getTasksByUserIdAndStatus($this->user->id, 'completed');

        $this->assertEquals(2, $tasks->count());
        $this->assertTrue($tasks->contains($task));
        $this->assertTrue($tasks->contains($task3));
        $this->assertFalse($tasks->contains($task2));
    }

    // public function testShowReturnsTaskAndPaginatesCorrectly()
    // {
    //     // Arrange
    //     $created_tasks = Task::factory(['owner_id' => $this->user->id, 'status' => 'completed'])->count(30)->create();
    //     $paginate = 20;
    //     $tasks = $this->service->getTasksByOwner($this->user->id, $paginate);
    //     // $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $tasks);
    //     $this->assertInstanceOf(TaskCollection::class, $tasks);
    //     $this->assertEquals($paginate, $tasks->count());
    // }

    public function test_to_get_tasks_by_owner()
    {
        // Arrange
        $owner1 = User::factory()->create();
        $owner2 = User::factory()->create();

        $visibleTask1 = Task::factory()->create(['owner_id' => $owner1->id, 'status' => 'visible', 'for' => 'staff']);
        $visibleTask2 = Task::factory()->create(['owner_id' => $owner1->id, 'status' => 'visible', 'for' => 'staff']);
        $staledTask = Task::factory()->create(['owner_id' => $owner1->id, 'status' => 'staled', 'for' => 'staff']);
        $hiddenTask = Task::factory()->create(['owner_id' => $owner1->id, 'status' => 'hidden', 'for' => 'staff']);
        $otherOwnerTask = Task::factory()->create(['owner_id' => $owner2->id, 'status' => 'visible', 'for' => 'staff']);

        $perPage = 10;

        // Act
        $tasks = $this->service->getTasksByOwner($owner1->id, $perPage);

        // Assert
        $this->assertCount(3, $tasks);
        $this->assertTrue($tasks->contains($visibleTask1));
        $this->assertTrue($tasks->contains($visibleTask2));
        $this->assertTrue($tasks->contains($staledTask));
        $this->assertFalse($tasks->contains($hiddenTask));
        $this->assertFalse($tasks->contains($otherOwnerTask));
        $this->assertEquals($perPage, $tasks->perPage());
    }





}