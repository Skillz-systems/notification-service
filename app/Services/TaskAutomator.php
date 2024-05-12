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

        // $status = $request['status'];
        // $for = $request['for'];
        // $email_to = $request['owner_email'];
        // $owner_id = $request['owner_id'];
        // $url = $request['url'];
        // $content = $request['content'];
        // $title = $request['title'];

        // if ($status === 'visible' && $for === 'customer') {
        //     $customer = $this->customerService->show($owner_id);

        //     $newArray = [
        //         'title' => $title,
        //         'receiver_email' => $email_to ?? $customer->email,
        //         'body' => $content,
        //         'url' => $url,
        //         // 'receiver_name' => $user->name,
        //     ];
        //     EmailJob::dispatch($newArray);

        // }
        // if ($status === 'visible' && $for === 'staff') {
        //     $user = $this->userService->show($owner_id);
        //     $newArray = [
        //         'title' => $title,
        //         'receiver_email' => $email_to,
        //         'body' => $content,
        //         'url' => $url,
        //         'receiver_name' => $user->name,
        //     ];
        //     EmailJob::dispatch($newArray);
        // }


        $emailData = $this->prepareEmailData($request);

        EmailJob::dispatch($emailData);

    }


    private function prepareEmailData(array $request): array
    {
        $owner = $this->getOwner($request['for'], $request['owner_id']);

        $emailData = [
            'title' => $request['title'],
            'receiver_email' => $request['owner_email'] ?? $owner->email,
            'body' => $request['content'],
            'url' => $request['url'],
        ];

        if ($request['for'] === 'staff' || $request['for'] === 'supplier') {
            $emailData['receiver_name'] = $owner->name;
        }

        return $emailData;
    }

    private function getOwner(string $ownerType, int $ownerId): object
    {
        switch ($ownerType) {
            case 'customer':
                return $this->customerService->show($ownerId);
            case 'staff':
                return $this->userService->show($ownerId);
            case 'supplier':
                // change to supplier //FIXME:
                return $this->userService->show($ownerId);
            default:
                throw new \InvalidArgumentException("Invalid owner type: $ownerType");
        }
    }
}