<?php

namespace Tests\Feature\Queue;

use Tests\TestCase;
use App\Models\Customer;
use App\Jobs\CustomerCreated;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerServiceJobTest extends TestCase
{

    use RefreshDatabase, WithFaker;
    private CustomerService $service;

    protected function setUp(): void
    {
        parent::setUp();
        // $this->service = new UserService();
        $this->service = $this->app->make(CustomerService::class);
    }


    public function test_it_dispatches_customer_creation_job_functionality(): void
    {
        Queue::fake();

        $request = [
            "name" => "john doe",
            "id" => 1,
            "email" => "test@nnpc.com",
        ];

        CustomerCreated::dispatch($request);

        Queue::assertPushed(CustomerCreated::class, function ($job) use ($request) {
            return $job->getData() == $request;
        });
    }



    public function test_it_dispatches__updated_job_functionality(): void
    {
        Queue::fake();
        $customer = Customer::factory([
            "name" => "jerry doe",
            "id" => 1,
            "email" => "jerry@nnpc.com",
        ])->create();


        $request = [
            "name" => "jerry doe updated",
            "id" => $customer->id,
            "email" => "test@nnpc.com",
        ];
        CustomerCreated::dispatch($request);

        Queue::assertPushed(CustomerCreated::class, function ($job) use ($request) {
            return $job->getData() == $request;
        });

    }


}




// NEW

// class CustomerCreatedTest extends TestCase
// {
//     use RefreshDatabase, WithFaker;

//     public function testHandleCreatesCustomerWhenNotExists()
//     {
//         $customerData = [
//             'id' => 1,
//             'name' => $this->faker->name,
//             'email' => $this->faker->email,
//         ];

//         $customerServiceMock = $this->createMock(CustomerService::class);
//         $customerServiceMock->expects($this->once())
//             ->method('show')
//             ->with(1)
//             ->willReturn(null);

//         $customerServiceMock->expects($this->once())
//             ->method('create')
//             ->with($customerData);

//         $job = new CustomerCreated($customerData);
//         $job->handle($customerServiceMock);
//     }

//     public function testHandleUpdatesCustomerWhenExists()
//     {
//         $customerData = [
//             'id' => 1,
//             'name' => $this->faker->name,
//             'email' => $this->faker->email,
//         ];

//         $existingCustomer = [
//             'id' => 1,
//             'name' => $this->faker->name,
//             'email' => $this->faker->email,
//         ];

//         $customerServiceMock = $this->createMock(CustomerService::class);
//         $customerServiceMock->expects($this->once())
//             ->method('show')
//             ->with(1)
//             ->willReturn($existingCustomer);

//         $customerServiceMock->expects($this->once())
//             ->method('update')
//             ->with($customerData, 1);

//         $job = new CustomerCreated($customerData);
//         $job->handle($customerServiceMock);
//     }
// }