<?php

namespace App\Jobs\UserService;

use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UserUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;


    private int $id;


    public function __construct(array $data)
    {
        $this->data = $data;
        $this->id = $data['id'];
    }


    public function getData(): array
    {
        return $this->data;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function handle(): void
    {

        $service = new UserService();
        $service->update($this->data, $this->id);
    }
}