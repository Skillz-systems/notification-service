<?php

namespace App\Services;

use App\Models\Task;


use App\Services\UserService;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Validator;
use App\Jobs\CommunicationsService\EmailJob;



class TaskAutomator
{

    private $userService;
    private $customerService;

    public function __construct(UserService $userService, CustomerService $customerService)
    {
        $this->userService = $userService;
        $this->customerService = $customerService;
    }
    public function handle(array $request): void
    {

        $status = $request['status'];
        $for = $request['for'];
        $email_to = $request['owner_email'];
        $owner_id = $request['owner_id'];
        $url = $request['url'];
        $content = $request['content'];
        $title = $request['title'];

        if ($status === 'visible' && $for === 'customer') {
            $customer = $this->customerService->show($owner_id);

            $newArray = [
                'title' => $title,
                'receiver_email' => $email_to ?? $customer->email,
                'body' => $content,
                'url' => $url,
                // 'receiver_name' => $user->name,
            ];
            EmailJob::dispatch($newArray);

        }
        if ($status === 'visible' && $for === 'staff') {
            $user = $this->userService->show($owner_id);
            $newArray = [
                'title' => $title,
                'receiver_email' => $email_to,
                'body' => $content,
                'url' => $url,
                'receiver_name' => $user->name,
            ];
            EmailJob::dispatch($newArray);
        }

    }
}