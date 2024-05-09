<?php

namespace App\Jobs\AutomatorService;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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