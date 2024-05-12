<?php

namespace App\Providers;

use App\Jobs\AutomatorService\TaskCreated;
use App\Jobs\CustomerCreated;
use App\Jobs\UserService\UserCreated;
use App\Jobs\UserService\UserDeleted;
use App\Jobs\UserService\UserUpdated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        \App::bindMethod(UserCreated::class . '@handle', fn($job) => $job->handle());
        \App::bindMethod(UserUpdated::class . '@handle', fn($job) => $job->handle());
        \App::bindMethod(UserDeleted::class . '@handle', fn($job) => $job->handle());
        \App::bindMethod(TaskCreated::class . '@handle', fn($job) => $job->handle());
        \App::bindMethod(CustomerCreated::class . '@handle', fn($job) => $job->handle());
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}