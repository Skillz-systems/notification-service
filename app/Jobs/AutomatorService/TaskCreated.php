<?php

namespace App\Jobs\AutomatorService;

use App\Services\NotificationTaskService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TaskCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;
    private int $id;


    public function __construct(array $data)
    {
        $this->data = $data;
        $this->id = $data['id'];
    }


    public function handle(): void
    {
        $service = new NotificationTaskService();
        $task = $service->show($this->id);

        if (!$task) {
            $service->create($this->data);
        } else {
            $service->update($this->data, $this->id);
        }
    }

    public function getData(): array
    {
        return $this->data;
    }
}