<?php

namespace App\Jobs\UserService;

use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UserCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;


    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function handle(): void
    {

        $service = new UserService();
        $service->create($this->data);


    }

    public function getData(): array
    {
        return $this->data;
    }
}