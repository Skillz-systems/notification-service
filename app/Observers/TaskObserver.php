<?php

namespace App\Observers;

use App\Models\Task;
use App\Services\TaskAutomator;

class TaskObserver
{

    protected $taskAutomator;

    // Dependency injection of the TaskAutomator service
    public function __construct(TaskAutomator $taskAutomator)
    {
        $this->taskAutomator = $taskAutomator;
    }


    //only run the taskAutomator if the task is 0(false) i.e pending

    public function created(Task $task): void
    {
        if ($task->task_status === Task::PENDING) {
            $this->taskAutomator->send($task->toArray());
        }
    }

    //only run the taskAutomator if the task is 0(false) i.e pending

    public function updated(Task $task): void
    {
        if ($task->task_status === Task::PENDING) {
            $this->taskAutomator->send($task->toArray());
        }
    }
}