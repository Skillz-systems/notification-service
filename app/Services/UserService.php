<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class UserService
 *
 * Service class responsible for user-related operations.
 *
 * @package App\Services
 */
class UserService
{

    protected static ?string $password;
    /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return User::all();
    }

    /**
     * Show a user.
     *
     * @param int $id
     * @return \App\Models\User|null
     */
    public function show(int $id): ?User
    {
        return User::findOrFail($id);
    }

    /**
     * Create a new user.
     *
     * @param  $request
     * @return \App\Models\User
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(array $request): User
    {
        $validatedData = $this->validateUserData($request);
        $user = User::create($validatedData);

        return $user;
    }

    /**
     * Update an existing user.
     *
     * @param  $request
     * @param int $id
     * @return \App\Models\User
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(array $request, int $id): User
    {
        $user = $this->show($id);
        $validatedData = $this->validateUserData($request);

        $user->fill($validatedData);
        $user->save();

        return $user;
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function destroy(int $id): bool
    {
        $user = $this->show($id);
        return $user->delete();
    }

    /**
     * Validate user data.
     *
     * @param array $data
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateUserData(array $data): array
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . ($data['id'] ?? null),
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}