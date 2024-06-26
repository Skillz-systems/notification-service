<?php

namespace Tests\Unit;

use App\Models\NotificationTask;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SendTasksReminderNotifierTest extends TestCase
{

    use RefreshDatabase;

    public function test_send_task_reminder_command()
    {
        $taskPending = NotificationTask::factory([
            'user_id' => $this->user()->id,
            'task_status' => 0,
            'start_time' => Carbon::now()->subHours(10),
            'end_time' => Carbon::now()->addHours(2),
        ])->create();

        $taskCompleted = NotificationTask::factory([
            'user_id' => $this->user()->id,
            'task_status' => 1,
            'start_time' => Carbon::now()->subHours(12),
            'end_time' => Carbon::now()->addHours(4),
        ])->create();

        $taskOutsideTimeRange = NotificationTask::factory([
            'user_id' => $this->user()->id,
            'task_status' => 0,
            'start_time' => Carbon::now()->subHours(6),
            'end_time' => Carbon::now()->subHours(2),
        ])->create();

        $this->artisan('app:send-tasks-reminder-notifier')
            ->expectsOutput("Sending reminder for task: {$taskPending->title}")
            ->assertSuccessful();

        $this->assertDatabaseHas('notification_tasks', [
            'id' => $taskPending->id,
            'task_status' => 0,
        ]);

        $this->assertDatabaseHas('notification_tasks', [
            'id' => $taskCompleted->id,
            'task_status' => 1,
        ]);

        $this->assertDatabaseHas('notification_tasks', [
            'id' => $taskOutsideTimeRange->id,
            'task_status' => 0,
        ]);
    }
}