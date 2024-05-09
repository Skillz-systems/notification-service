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

    public function testCreatedWithVisibleStatus()
    {
        $task = new Task(['status' => 'visible']);

        $this->taskAutomator->shouldReceive('handle')
            ->once()
            ->with($task->toArray());

        $this->taskObserver->created($task);
    }

    public function testCreatedWithNonVisibleStatus()
    {
        $task = new Task(['status' => 'hidden']);

        $this->taskAutomator->shouldNotReceive('handle');

        $this->taskObserver->created($task);
    }

    public function testUpdatedWithVisibleStatus()
    {
        $task = new Task(['status' => 'visible']);

        $this->taskAutomator->shouldReceive('handle')
            ->once()
            ->with($task->toArray());

        $this->taskObserver->updated($task);
    }

    public function testUpdatedWithNonVisibleStatus()
    {
        $task = new Task(['status' => 'hidden']);

        $this->taskAutomator->shouldNotReceive('handle');

        $this->taskObserver->updated($task);
    }

    // public function testDeleted()
    // {
    //     $task = new Task();

    //     $this->taskObserver->deleted($task);
    //     // No assertions needed as the method is empty
    // }

    // public function testRestored()
    // {
    //     $task = new Task();

    //     $this->taskObserver->restored($task);
    //     // No assertions needed as the method is empty
    // }

    // public function testForceDeleted()
    // {
    //     $task = new Task();

    //     $this->taskObserver->forceDeleted($task);
    //     // No assertions needed as the method is empty
    // }
}