<?php

namespace App\Jobs;

use App\Services\CustomerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CustomerCreated implements ShouldQueue
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
        $service = new CustomerService();
        $customer = $service->show($this->id);

        if (!$customer) {
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