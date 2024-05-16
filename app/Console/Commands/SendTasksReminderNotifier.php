<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Services\TaskAutomator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Container\BindingResolutionException;

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

        $taskAutomator = app()->make(TaskAutomator::class);

        $pendingTasks = Task::where('task_status', 0)
            ->whereDate('start_time', '<=', now()->subHours(8))
            ->whereDate('end_time', '>=', now());


        $pendingTasks->chunk(100, function ($tasks) use ($taskAutomator) {
            foreach ($tasks as $task) {
                try {
                    $taskAutomator->handle($task->toArray());
                    $this->info("Sending reminder for task: {$task->title}");
                } catch (\Exception $e) {
                    Log::error('Failed to handle task: ' . $task->title . ', Error: ' . $e->getMessage());
                    $this->error("Failed to handle task: {$task->title}");
                }
            }
        });

        return 0;
    }
}