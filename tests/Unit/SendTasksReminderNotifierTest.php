<?php

namespace Tests\Unit;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SendTasksReminderNotifierTest extends TestCase
{

    use RefreshDatabase;

    public function test_send_task_reminder_command()
    {
        $taskPending = Task::factory([
            'user_id' => $this->user()->id,
            'task_status' => 0,
            'start_time' => Carbon::now()->subHours(10),
            'end_time' => Carbon::now()->addHours(2),
        ])->create();

        $taskCompleted = Task::factory([
            'user_id' => $this->user()->id,
            'task_status' => 1,
            'start_time' => Carbon::now()->subHours(12),
            'end_time' => Carbon::now()->addHours(4),
        ])->create();

        $taskOutsideTimeRange = Task::factory([
            'user_id' => $this->user()->id,
            'task_status' => 0,
            'start_time' => Carbon::now()->subHours(6),
            'end_time' => Carbon::now()->subHours(2),
        ])->create();

        $this->artisan('app:send-tasks-reminder-notifier')
            ->expectsOutput("Sending reminder for task: {$taskPending->title}")
            ->assertSuccessful();

        $this->assertDatabaseHas('tasks', [
            'id' => $taskPending->id,
            'task_status' => 0,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $taskCompleted->id,
            'task_status' => 1,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $taskOutsideTimeRange->id,
            'task_status' => 0,
        ]);
    }
}