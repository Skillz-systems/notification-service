<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

// use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    public function test_to_get_all_users(): void
    {
        User::factory()->count(3)->create();

        $users = $this->userService->get();

        $this->assertCount(3, $users);
    }

    public function test_to_show_a_user(): void
    {
        $user = User::factory()->create();

        $foundUser = $this->userService->show($user->id);

        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_create_a_new_user(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ];

        $user = $this->userService->create($userData);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_to_update_an_existing_user(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $updatedData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ];

        $updatedUser = $this->userService->update($updatedData, $user->id);

        $this->assertEquals($updatedData['name'], $updatedUser->name);
        $this->assertEquals($updatedData['email'], $updatedUser->email);
    }

    public function test_to_delete_a_user_succeeds()
    {
        $user = User::factory()->create();

        $result = $this->userService->destroy($user->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_to_validation_fails_on_creating_a_new_user()
    {
        $this->expectException(ValidationException::class);

        $invalidData = [
            'name' => '',
            'email' => 'invalid-email',
        ];

        $this->userService->create($invalidData);
    }

    public function test_to_validation_fails_on_updating_an_existing_user()
    {
        // $this->expectException(ModelNotFoundException::class);
        $this->expectException(ValidationException::class);

        $user = User::factory()->create([
            'id' => 40,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $invalidData = [
            'name' => '',
            'email' => 'invalid-email',
        ];

        $this->userService->update($invalidData, $user->id);
    }

}