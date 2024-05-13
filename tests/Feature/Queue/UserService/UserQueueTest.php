<?php

namespace Tests\Feature\Queue\UserService;

use App\Jobs\UserService\UserUpdated;
use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use App\Jobs\UserService\UserCreated;
use App\Jobs\UserService\UserDeleted;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserQueueTest extends TestCase
{





    use RefreshDatabase;

    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        // $this->service = new UserService();
        $this->service = $this->app->make(UserService::class);
    }


    public function test_it_dispatches_user_creation_job_functionality(): void
    {
        Queue::fake();

        $request = [
            "name" => "john doe",
            "id" => 1,
            "email" => "test@nnpc.com",
        ];

        UserCreated::dispatch($request);

        Queue::assertPushed(UserCreated::class, function ($job) use ($request) {
            return $job->getData() == $request;
        });
    }


    public function test_it_dispatches_user_deletion_job_functionality(): void
    {

        Queue::fake();

        $user = User::factory()->create();

        UserDeleted::dispatch($user->id);

        Queue::assertPushed(UserDeleted::class, function ($job) use ($user) {
            return $job->getId() == $user->id;
        });
    }
    public function test_it_dispatches_user_updated_job_functionality(): void
    {
        Queue::fake();
        $user = User::factory()->create();


        $request = [
            "name" => "john doe updated",
            "id" => $user->id,
            "email" => "test@nnpc.com",
        ];
        UserUpdated::dispatch($request);

        Queue::assertPushed(UserUpdated::class, function ($job) use ($request) {
            return $job->getData() == $request;
        });

    }
}