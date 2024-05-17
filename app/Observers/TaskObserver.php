<?php

namespace App\Observers;

use App\Models\Task;
use App\Services\TaskAutomator;

/**
 * TaskObserver is an observer class for handling task events.
 */
class TaskObserver
{
    /**
     * @var TaskAutomator
     */
    protected $taskAutomator;

    /**
     * Constructor for TaskObserver.
     *
     * @param TaskAutomator $taskAutomator
     */
    public function __construct(TaskAutomator $taskAutomator)
    {
        $this->taskAutomator = $taskAutomator;
    }

    /**
     * Handle the Task "created" event.
     *
     * @param Task $task
     * @return void
     */
    public function created(Task $task): void
    {
        if ($task->task_status === Task::PENDING) {
            $this->taskAutomator->send($task->toArray());
        }
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param Task $task
     * @return void
     */
    public function updated(Task $task): void
    {
        if ($task->task_status === Task::PENDING) {
            $this->taskAutomator->send($task->toArray());
        }
    }
}