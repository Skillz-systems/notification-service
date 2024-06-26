<?php

namespace Tests\Unit\Observers;

use App\Models\NotificationTask;
use App\Observers\TaskObserver;
use App\Services\NotificationTaskAutomator;
use Mockery;
use Tests\TestCase;

class TaskObserverTest extends TestCase
{
    protected $taskAutomator;
    protected $taskObserver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskAutomator = Mockery::mock(NotificationTaskAutomator::class);
        $this->taskObserver = new TaskObserver($this->taskAutomator);
    }

    public function test_observer_task_automator_run_when_task_is_created_with_pending_task_status(): void
    {
        $task = new NotificationTask(['task_status' => NotificationTask::PENDING]);

        $this->taskAutomator->shouldReceive('send')
            ->once()
            ->with($task->toArray());

        $this->taskObserver->created($task);
    }

    public function test_observer_task_automator_does_not_run_when_task_is_created_or_updated_with_completed_task_status(): void
    {
        $task = new NotificationTask(['task_status' => NotificationTask::DONE]);

        $this->taskAutomator->shouldNotReceive('send');

        $this->taskObserver->created($task);
    }

}
