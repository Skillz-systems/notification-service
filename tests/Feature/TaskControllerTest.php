<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }
    public function test_to_show_tasks_by_user_id()
    {

        $this->actingAsAuthenticatedTestUser();

        Task::factory(['user_id' => $this->user->id, 'task_status' => 0])->create();

        $response = $this->getJson('/api/tasks/' . $this->user->id);

        $response->assertStatus(200);
    }
    public function test_unauthenticated_cannot_show_tasks_by_user_id()
    {

        $this->actingAsUnAuthenticatedTestUser();

        Task::factory(['user_id' => $this->user->id, 'task_status' => 0])->create();

        $response = $this->getJson('/api/tasks/' . $this->user->id);

        $response->assertStatus(401);
    }
    public function test_it_can_show_tasks_for_a_user()
    {
        $this->actingAsAuthenticatedTestUser();
        Task::factory(['user_id' => $this->user->id, 'task_status' => Task::PENDING])->count(5)->create();
        Task::factory(['user_id' => $this->user->id, 'task_status' => Task::DONE])->count(5)->create();
        $response = $this->getJson('/api/tasks/' . $this->user->id);
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'title', 'route', 'start_time', 'end_time', 'task_status']]]);
        $this->assertCount(5, $response->json('data'));
    }

}