<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Console\Command;

class SendTaskReminders extends Command
{

    // protected $signature = 'app:send-task-reminders';


    // protected $description = 'Command description';


    // public function handle()
    // {

    // }

    protected $signature = 'app:send-task-reminders';
    protected $description = 'Send reminders for tasks between 8am and 4pm daily';

    public function handle()
    {
        // Get current time
        $currentTime = Carbon::now();

        // Fetch tasks with status 'visible' or 'staled' for today between 8am and 4pm
        $tasks = Task::where('status', 'visible')
            ->orWhere('status', 'staled')
            ->whereBetween('created_at', [
                Carbon::today()->startOfDay()->addHours(8),
                Carbon::today()->startOfDay()->addHours(16)
            ])->get();

        // Send reminders for each task
        foreach ($tasks as $task) {
            // Send reminder logic here
            // Example: send email, SMS, notification, etc.
            // For demonstration purposes, just display task title
            $this->info('Reminder for task: ' . $task->title);
        }

        $this->info('Reminders sent successfully.');
    }
}