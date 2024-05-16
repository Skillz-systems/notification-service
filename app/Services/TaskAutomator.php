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
        $emailData = $this->prepareEmailData($request);

        EmailJob::dispatch($emailData);

    }


    private function prepareEmailData(array $request): array
    {
        $owner = $this->getOwner($request['entity_type'], $request['user_id']);

        $emailData = [
            'type' => $request['entity_type'],
            'title' => $request['title'],
            'email' => $owner->email,
            'route' => $request['route'],
        ];
        return $emailData;
    }

    private function getOwner(string $entityType, int $ownerId): object
    {
        switch ($entityType) {
            case 'customer':
                return $this->userService->show($ownerId);
            case 'staff':
                return $this->userService->show($ownerId);
            case 'supplier':
                // change to supplier //FIXME:
                return $this->userService->show($ownerId);
            default:
                throw new \InvalidArgumentException("Invalid owner type: $entityType");
        }
    }
}