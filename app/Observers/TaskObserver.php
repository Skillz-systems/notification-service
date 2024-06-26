<?php

namespace App\Observers;

use App\Models\NotificationTask;
use App\Services\NotificationTaskAutomator;

/**
 * TaskObserver is an observer class for handling task events.
 */
class TaskObserver
{
    /**
     * @var NotificationTaskAutomator
     */
    protected $taskAutomator;

    /**
     * Constructor for TaskObserver.
     *
     * @param NotificationTaskAutomator $taskAutomator
     */
    public function __construct(NotificationTaskAutomator $taskAutomator)
    {
        $this->taskAutomator = $taskAutomator;
    }

    /**
     * Handle the Task "created" event.
     *
     * @param NotificationTask $task
     * @return void
     */
    public function created(NotificationTask $task): void
    {
        if ($task->task_status === NotificationTask::PENDING) {
            $this->taskAutomator->send($task->toArray());
        }
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param NotificationTask $task
     * @return void
     */
    public function updated(NotificationTask $task): void
    {
        if ($task->task_status === NotificationTask::PENDING) {
            $this->taskAutomator->send($task->toArray());
        }
    }
}