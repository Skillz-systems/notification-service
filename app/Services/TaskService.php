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

    private function validateUpdateData(array $data): array
    {
        $validator = Validator::make($data, [
            'id' => 'required|integer',
            'processflow_history_id' => 'nullable|integer',
            'formbuilder_data_id' => 'nullable|integer',
            'entity_id' => 'nullable|integer',
            'entity_type' => 'nullable|string',
            'user_id' => 'nullable|integer',
            'processflow_id' => 'nullable|integer',
            'processflow_step_id' => 'nullable|integer',
            'title' => 'required|string',
            'route' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'task_status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function validateCreateData(array $data): array
    {
        $validator = Validator::make($data, [
            'id' => 'required|integer',
            'processflow_history_id' => 'nullable|integer',
            'formbuilder_data_id' => 'nullable|integer',
            'entity_id' => 'nullable|integer',
            'entity_type' => 'nullable|string',
            'user_id' => 'nullable|integer',
            'processflow_id' => 'nullable|integer',
            'processflow_step_id' => 'nullable|integer',
            'title' => 'required|string',
            'route' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'task_status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}