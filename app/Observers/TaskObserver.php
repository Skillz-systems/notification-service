<?php

namespace App\Observers;

use App\Models\Task;
use App\Services\TaskAutomator;

class TaskObserver
{

    protected $taskAutomator;

    public function __construct(TaskAutomator $taskAutomator)
    {
        $this->taskAutomator = $taskAutomator;
    }


    public function created(Task $task): void
    {
        if ($task->status === 'visible') {

            $this->taskAutomator->handle($task->toArray());

        }
    }

    public function updated(Task $task): void
    {
        if ($task->status === 'visible') {
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