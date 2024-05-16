<?php

namespace Tests\Unit\Observers;

use App\Models\Task;
use App\Observers\TaskObserver;
use App\Services\TaskAutomator;
use Mockery;
use Tests\TestCase;

class TaskObserverTest extends TestCase
{
    protected $taskAutomator;
    protected $taskObserver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskAutomator = Mockery::mock(TaskAutomator::class);
        $this->taskObserver = new TaskObserver($this->taskAutomator);
    }

    public function test_observer_task_automator_run_when_task_is_created_with_pending_task_status(): void
    {
        $task = new Task(['task_status' => 0]);

        $this->taskAutomator->shouldReceive('handle')
            ->once()
            ->with($task->toArray());

        $this->taskObserver->created($task);
    }

    public function test_observer_task_automator_does_not_run_when_task_is_created_or_updated_with_completed_task_status(): void
    {
        $task = new Task(['task_status' => 1]);

        $this->taskAutomator->shouldNotReceive('handle');

        $this->taskObserver->created($task);
    }

}