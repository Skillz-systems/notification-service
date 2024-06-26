<?php

namespace App\Console\Commands;

use App\Models\NotificationTask;
use App\Services\NotificationTaskAutomator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Container\BindingResolutionException;



/**
 * SendTasksReminderNotifier is a Laravel console command that sends reminders for pending tasks.
 * It retrieves tasks where the task status is 'PENDING', the start time is at least 8 hours before
 * the current time, and the end time is greater than or equal to the current time. It then sends
 * these tasks to the TaskAutomator service for further processing or notification.
 */
class SendTasksReminderNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-tasks-reminder-notifier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for pending tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {
            // Resolve the TaskAutomator service from the container
            $taskAutomator = app()->make(NotificationTaskAutomator::class);
        } catch (BindingResolutionException $e) {
            // Log the error and display a message if the TaskAutomator service cannot be resolved
            Log::error('Failed to resolve TaskAutomator: ' . $e->getMessage());
            $this->error('Failed to resolve TaskAutomator. Check the logs for more details.');
            return 1;
        }




        // Retrieve pending tasks where the start time is at least 8 hours before the current time
        // and the end time is greater than or equal to the current time

        $pendingTasks = NotificationTask::where('task_status', NotificationTask::PENDING)
            ->whereDate('start_time', '<=', now()->subHours(8))
            ->whereDate('end_time', '>=', now());


        // Check if there are any pending tasks
        if ($pendingTasks->count() === 0) {
            $this->info('No pending tasks found.');
            return 0;
        }

        // Process the pending tasks in chunks of 100 records
        $pendingTasks->chunk(100, function ($tasks) use ($taskAutomator) {
            foreach ($tasks as $task) {
                try {
                    // Send the task to the TaskAutomator service for processing
                    $taskAutomator->send($task->toArray());
                    $this->info("Sending reminder for task: {$task->title}");
                } catch (\Exception $e) {
                    // Log and display an error message if an exception occurs while processing the task
                    Log::error('Failed to handle task: ' . $task->id . $task->title . ', Error: ' . $e->getMessage());
                    $this->error("Failed to handle task: {$task->title}");
                }
            }
        });

        return 0;
    }
}