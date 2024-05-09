<?php

namespace App\Services;

use App\Models\Task;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class TaskService
{
    public function show(int $id): ?Task
    {
        return Task::findOrFail($id);
    }

    public function create(array $request): ?Task
    {

        $validatedData = $this->validateCreateData($request);
        $task = Task::create($validatedData);
        return $task;

    }

    public function update(array $request, int $id): ?bool
    {


        $validatedData = $this->validateUpdateData($request);
        $task = Task::findOrFail($id);
        return $task->update($validatedData);

    }

    public function destroy(int $id): bool
    {
        $task = $this->show($id);
        return $task->delete();
    }

    public function getTasksByUserId(int $userId)
    {
        return Task::where('user_id', $userId)->get();
    }

    public function getTasksByUserIdAndStatus($userId, $status)
    {
        return Task::where('user_id', $userId)
            ->where('status', $status)
            ->get();
    }

    private function validateUpdateData(array $data): array
    {
        $validator = Validator::make($data, [
            'id' => 'required',
            'user_id' => 'required',
            'title' => 'sometimes',
            'for' => 'sometimes',
            'status' => 'sometimes',
            'content' => 'sometimes',
            'user_email' => 'sometimes',
            'url' => 'sometimes',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function validateCreateData(array $data): array
    {
        $validator = Validator::make($data, [
            'id' => 'required',
            'user_id' => 'required',
            'title' => 'required|string|max:255',
            'for' => 'required|in:staff,customer,supplier,other',
            'status' => 'required|in:visible,hidden,completed,stalled',
            'content' => 'nullable|string',
            'user_email' => 'nullable|email',
            'url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}