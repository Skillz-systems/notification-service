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
        if ($task->task_status === false) {

            $this->taskAutomator->handle($task->toArray());

        }
    }

    //only run the taskAutomator if the task is 0(false) i.e pending

    public function updated(Task $task): void
    {
        if ($task->task_status === false) {
            $this->taskAutomator->handle($task->toArray());
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    // public function deleted(Task $task): void
    // {
    //     //
    // }

    // /**
    //  * Handle the Task "restored" event.
    //  */
    // public function restored(Task $task): void
    // {
    //     //
    // }

    // /**
    //  * Handle the Task "force deleted" event.
    //  */
    // public function forceDeleted(Task $task): void
    // {
    //     //
    // }
}