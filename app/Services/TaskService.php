<?php

namespace App\Services;

use App\Models\Task;


use App\Models\User;
use App\Services\TaskUrlGenerator;
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

    public function getTasksByUserId(int $userId): ?Collection
    {
        return Task::where('owner_id', $userId)->get();

        // $user = User::findOrFail($userId);
        // Retrieve all tasks associated with the user
        // return $user->tasks;
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
            'for' => 'required|in:staff,customer,supplier,other',
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




// namespace App\Services;

// use App\Models\Task;
// use App\Models\User;
// use App\Models\Customer;
// use App\Models\Supplier;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Validation\ValidationException;

// class TaskService
// {
//     public function show(int $id): ?Task
//     {
//         return Task::findOrFail($id);
//     }

//     public function create(array $request): ?Task
//     {
//         $validatedData = $this->validateCreateData($request);
//         $task = Task::create($validatedData);

//         // Associate the task with its owner
//         $ownerType = $validatedData['owner_type'];
//         $ownerId = $validatedData['owner_id'];
//         $this->associateTaskWithOwner($task, $ownerType, $ownerId);

//         return $task;
//     }

//     public function update(array $request, int $id): ?bool
//     {
//         $validatedData = $this->validateUpdateData($request);
//         $task = Task::findOrFail($id);

//         // Update the task with the validated data
//         $task->update($validatedData);

//         // Associate the task with its owner
//         $ownerType = $validatedData['owner_type'] ?? $task->owner_type;
//         $ownerId = $validatedData['owner_id'] ?? $task->owner_id;
//         $this->associateTaskWithOwner($task, $ownerType, $ownerId);

//         return true;
//     }

//     public function destroy(int $id): bool
//     {
//         $task = $this->show($id);
//         return $task->delete();
//     }

//     private function validateUpdateData(array $data): array
//     {
//         $validator = Validator::make($data, [
//             'title' => 'sometimes|string|max:255',
//             'for' => 'sometimes|in:staff,customer,supplier,other',
//             'status' => 'sometimes|in:visible,hidden,completed,stalled',
//             'content' => 'sometimes|string',
//             'user_email' => 'sometimes|email',
//             'url' => 'sometimes|url',
//             'owner_id' => 'sometimes|required_with:owner_type',
//             'owner_type' => 'sometimes|required_with:owner_id|in:' . implode(',', [User::class, Customer::class]),
//         ]);

//         if ($validator->fails()) {
//             throw new ValidationException($validator);
//         }

//         return $validator->validated();
//     }

//     private function validateCreateData(array $data): array
//     {
//         $validator = Validator::make($data, [
//             'title' => 'required|string|max:255',
//             'for' => 'required|in:staff,customer,supplier,other',
//             'status' => 'required|in:visible,hidden,completed,stalled',
//             'content' => 'nullable|string',
//             'user_email' => 'nullable|email',
//             'url' => 'nullable|url',
//             'owner_id' => 'required',
//             'owner_type' => 'required|in:' . implode(',', [User::class, Customer::class]),
//         ]);

//         if ($validator->fails()) {
//             throw new ValidationException($validator);
//         }

//         return $validator->validated();
//     }

//     private function associateTaskWithOwner(Task $task, ?string $ownerType, ?int $ownerId)
//     {
//         if (!empty($ownerType) && !empty($ownerId)) {
//             $ownerModel = app($ownerType);
//             $owner = $ownerModel->findOrFail($ownerId);
//             $task->owner()->associate($owner);
//             $task->save();
//         }
//     }
// }