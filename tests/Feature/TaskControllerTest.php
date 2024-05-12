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
    public function test_to_show_tasks_by_owner()
    {

        $this->actingAsAuthenticatedTestUser();

        $owner = User::factory()->create();
        Task::factory()->count(5)->create(['owner_id' => $this->user->id, 'for' => 'staff']);
        $response = $this->getJson('/api/tasks/' . $this->user->id);

        $response->assertStatus(200);
        // $response->assertJsonCount(5, 'data');
        // $response->assertJsonStructure([
        //     'data' => [
        //         '*' => [
        //             'id',
        //             'title',
        //             'description',
        //             'due_at',
        //             'status',
        //             'owner_id',
        //             'created_at',
        //             'updated_at',
        //         ]
        //     ]
        // ]);
    }

    // public function testFilterTasks()
    // {
    //     $this->actingAsAuthenticatedTestUser();
    //     // $owner = User::factory()->create();
    //     $tasks = Task::factory()->count(10)->create([
    //         'owner_id' => $this->user->id,
    //         'title' => 'Important',
    //         'due_at' => '2023-05-15',
    //     ]);

    //     $filters = [
    //         'title' => 'Important',
    //         'due_at' => '2023-05-15',
    //         'for' => 'staff',
    //         'sort_column' => 'due_at',
    //         'sort_direction' => 'desc',
    //     ];

    //     $response = $this->getJson(route('tasks.filter', ['id' => $this->user->id, 'filters' => $filters]));

    //     $response->assertStatus(200);
    //     // $response->assertJsonCount(10, 'data');
    //     // $response->assertJsonStructure([
    //     //     'data' => [
    //     //         '*' => [
    //     //             'id',
    //     //             'title',
    //     //             'description',
    //     //             'due_at',
    //     //             'status',
    //     //             'owner_id',
    //     //             'created_at',
    //     //             'updated_at',
    //     //         ]
    //     //     ]
    //     // ]);
    // }
}