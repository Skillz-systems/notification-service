<?php

namespace App\Services;

use App\Models\NotificationTask;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * TaskService is a class that provides functionality for managing tasks.
 */
class NotificationTaskService
{
    /**
     * Get a task by its ID.
     *
     * @param int $id
     * @return NotificationTask|null
     */
    public function show(int $id): ?NotificationTask
    {
        return NotificationTask::findOrFail($id);
    }

    /**
     * Create a new task.
     *
     * @param array $request
     * @return NotificationTask|null
     */
    public function create(array $request): ?NotificationTask
    {
        $validatedData = $this->validateCreateData($request);
        $task = NotificationTask::create($validatedData);
        return $task;
    }

    /**
     * Update an existing task.
     *
     * @param array $request
     * @param int $id
     * @return bool|null
     */
    public function update(array $request, int $id): ?bool
    {
        $validatedData = $this->validateUpdateData($request);
        $task = NotificationTask::findOrFail($id);
        return $task->update($validatedData);
    }

    /**
     * Delete a task.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $task = $this->show($id);
        return $task->delete();
    }

    /**
     * Get tasks by user ID.
     *
     * @param int $userId
     * @return Collection|null
     */
    public function getTasksByUserId(int $userId, ): ?Collection
    {
        return NotificationTask::where('user_id', $userId)->get();
    }

    /**
     * Get tasks by user ID and status.
     *
     * @param int $userId
     * @param mixed $status
     * @return Collection
     */
    public function getTasksByUserIdAndStatus($userId, $status)
    {
        return NotificationTask::where('user_id', $userId)
            ->where('task_status', $status)
            ->get();
    }
    /**
     * Validate the data for updating a task.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
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

    /**
     * Validate the data for creating a new task.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
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