<?php

namespace Tests\Unit\Services;

use App\Services\TaskUrlGenerator;
use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskUrlGeneratorTest extends TestCase
{
    use RefreshDatabase;

    private TaskUrlGenerator $taskUrlGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskUrlGenerator = new TaskUrlGenerator();
    }

    public function test_it_generates_correct_url_for_user(): void
    {
        $user = User::factory()->create();
        $expectedUrl = 'https://example.com/tasks/users/' . $user->id;

        $url = $this->taskUrlGenerator->generateUrl('staff', $user->id);

        $this->assertEquals($expectedUrl, $url);
    }

    public function test_it_generates_correct_url_for_customer(): void
    {
        $customer = Customer::factory()->create();
        $expectedUrl = 'https://example.com/tasks/customers/' . $customer->id;

        $url = $this->taskUrlGenerator->generateUrl('customer', $customer->id);

        $this->assertEquals($expectedUrl, $url);
    }

    /** @test */
    // public function test_it_generates_correct_url_for_supplier(): void
    // {
    //     $supplier = Supplier::factory()->create();
    //     $expectedUrl = 'https://example.com/tasks/suppliers/' . $supplier->id;

    //     $url = $this->taskUrlGenerator->generateUrl('supplier', $supplier->id);

    //     $this->assertEquals($expectedUrl, $url);
    // }

    /** @test */
    public function Test_it_throws_exception_for_invalid_owner_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid owner type: invalid_type');

        $this->taskUrlGenerator->generateUrl('invalid_type', 1);
    }
}