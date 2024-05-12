<?php

namespace App\Services;

use App\Models\Task;


use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use App\Jobs\CommunicationsService\EmailJob;



class TaskAutomator
{

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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

        $user = $this->userService->show($owner_id);

        if ($status === 'visible' && $for === 'customer') {

            $newArray = [
                'title' => $title,
                'receiver_email' => $email_to,
                'body' => $content,
                'url' => $url,
                // 'receiver_name' => $user->name,
            ];
            EmailJob::dispatch($newArray);

        }
        if ($status === 'visible' && $for === 'staff') {

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