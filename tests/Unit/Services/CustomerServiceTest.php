<?php

namespace Tests\Unit\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerServiceTest extends TestCase
{
    use RefreshDatabase;

    private CustomerService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = $this->app->make(CustomerService::class);
    }


    public function test_to_create_customer_with_valid_data()
    {
        $data = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $customer = $this->service->create($data);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($data['id'], $customer->id);
        $this->assertEquals($data['name'], $customer->name);
        $this->assertEquals($data['email'], $customer->email);
    }

    public function test_cannot_create_customer_with_invalid_data()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'id' => 1,
            'name' => '',
            'email' => 'invalid-email',
        ];

        $this->service->create($data);
    }

    public function test_to_update_customer_with_valid_data()
    {
        $customer = Customer::factory()->create([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $data = [
            'id' => 1,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ];

        $updatedCustomer = $this->service->update($data, $customer->id);

        $this->assertInstanceOf(Customer::class, $updatedCustomer);
        $this->assertEquals($customer->id, $updatedCustomer->id);
        $this->assertEquals($data['name'], $updatedCustomer->name);
        $this->assertEquals($data['email'], $updatedCustomer->email);
    }

    public function test_update_customer_with_invalid_data()
    {
        $customer = Customer::factory()->create([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->expectException(ValidationException::class);

        $data = [
            'name' => '',
            'email' => 'invalid-email',
        ];

        $this->service->update($data, $customer->id);
    }

    public function test_update_customer_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $data = [
            'id' => 999,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ];

        $updatedCustomer = $this->service->update($data, 999);

        $this->assertNull($updatedCustomer);
    }

    public function test_show_customer_found()
    {
        $customer = Customer::factory()->create([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $foundCustomer = $this->service->show($customer->id);

        $this->assertInstanceOf(Customer::class, $foundCustomer);
        $this->assertEquals($customer->id, $foundCustomer->id);
        $this->assertEquals($customer->name, $foundCustomer->name);
        $this->assertEquals($customer->email, $foundCustomer->email);
    }

    public function test_show_customer_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->service->show(999);

    }
}
