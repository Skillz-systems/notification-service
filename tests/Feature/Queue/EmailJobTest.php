<?php

namespace Tests\Feature\Queue;

use App\Jobs\CommunicationsService\EmailJob;
use Tests\TestCase;
use App\Models\NotificationTask;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailJobTest extends TestCase
{
    use RefreshDatabase;
    public function test_to_send_job_to_communications_service(): void
    {

        Queue::fake();
        $email = NotificationTask::factory(['user_id' => $this->user()->id])->create();
        EmailJob::dispatch($email->toArray());
        Queue::assertPushed(EmailJob::class, function ($job) use ($email) {
            return $job->getData() == $email->toArray();
        });
    }
}
