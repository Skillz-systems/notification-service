<?php

namespace App\Services;

use App\Models\Task;


use App\Models\User;
use App\Services\TaskUrlGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;


class TaskService
{


    public function show(int $id): ?Task
    {
        return Task::findOrFail($id);
    }

    public function create(array $request): ?Task
    {

        $taskUrlGenerator = app()->make(TaskUrlGenerator::class);

        $validatedData = $this->validateCreateData($request);
        $ownerType = $request['for'];
        $ownerId = $request['owner_id'];
        $url = $taskUrlGenerator->generateUrl($ownerType, $ownerId);
        $validatedData['url'] = $url;

        $task = Task::create($validatedData);
        return $task;

    }

    public function update(array $request, int $id): ?bool
    {
        $taskUrlGenerator = app()->make(TaskUrlGenerator::class);
        $ownerType = $request['for'];
        $ownerId = $request['owner_id'];
        $url = $taskUrlGenerator->generateUrl($ownerType, $ownerId);
        $validatedData = $this->validateUpdateData($request);
        $validatedData['url'] = $url;

        $task = Task::findOrFail($id);
        return $task->update($validatedData);

    }

    public function destroy(int $id): bool
    {
        $task = $this->show($id);
        return $task->delete();
    }

    public function getTasksByOwner($ownerId, $perPage = 15)
    {
        return Task::where('owner_id', $ownerId)
            ->whereIn('status', ['visible', 'staled'])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getTasksWithFilters($ownerId, $filters = [], $paginate = 15)
    {
        $query = DB::table('tasks')
            ->where('owner_id', $ownerId)
            ->whereIn('status', ['visible', 'staled']);

        // Apply filters
        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (isset($filters['due_at'])) {
            $query->whereDate('due_at', $filters['due_at']);
        }

        if (isset($filters['for'])) {
            $query->where('for', $filters['for']);
        }

        // Apply sorting
        $sortColumn = isset($filters['sort_column']) ? $filters['sort_column'] : 'created_at';
        $sortDirection = isset($filters['sort_direction']) && strtolower($filters['sort_direction']) === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortColumn, $sortDirection);

        // Apply pagination
        if ($paginate > 0) {
            return $query->paginate($paginate);
        } else {
            return $query->get();
        }
    }

    public function getTasksByUserId(int $userId): ?Collection
    {


        return Task::where('owner_id', $userId)->get();
    }

    public function getTasksByUserIdAndStatus($userId, $status)
    {
        return Task::where('owner_id', $userId)
            ->where('status', $status)
            ->get();
    }

    private function validateUpdateData(array $data): array
    {
        $validator = Validator::make($data, [
            'id' => 'required',
            'owner_id' => 'required',
            'title' => 'sometimes',
            'for' => 'sometimes',
            'status' => 'sometimes',
            'content' => 'sometimes',
            'owner_email' => 'sometimes',
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
            'owner_id' => 'required',
            'title' => 'required|string|max:255',
            'for' => 'required|in:staff,customer',
            'status' => 'required|in:visible,hidden,completed,stalled',
            'content' => 'nullable|string',
            'owner_email' => 'nullable|email',
            'url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}