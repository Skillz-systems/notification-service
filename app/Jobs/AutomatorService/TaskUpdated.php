<?php

namespace App\Jobs\AutomatorService;

use App\Services\TaskService;
use Illuminate\Bus\Queueable;
use App\Services\TaskUpdateService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TaskUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;
    private int $id;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->id = $data['id'];
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new TaskService();
        $service->update($this->data, $this->id);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getId(): int
    {
        return $this->id;
    }

}