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

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function send(array $request): void
    {
        $emailData = $this->prepareEmailData($request);

        EmailJob::dispatch($emailData);

    }
    private function prepareEmailData(array $request): array
    {

        $user = $this->userService->show($request['user_id']);
        $emailData = [
            'type' => $request['entity_type'],
            'title' => $request['title'],
            'email' => $user->email,
            'route' => $request['route'],
        ];
        return $emailData;
    }
}