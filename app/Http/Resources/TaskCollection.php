<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="TaskCollection",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="string"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="route", type="string"),
 *     @OA\Property(property="start_time", type="date"),
 *     @OA\Property(property="end_time", type="date"),
 *     @OA\Property(property="task_status", type="boolean")
 * )
 */
class TaskCollection extends ResourceCollection
{




    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {

        return [
            'data' => $this->collection->transform(function ($task) {
                return [
                    'id' => $task->id,
                    'user_id' => $task->user_id,
                    'title' => $task->title,
                    'route' => $task->route,
                    'start_time' => $task->start_time,
                    'end_time' => $task->end_time,
                    'task_status' => (boolean) $task->task_status,
                ];
            }),
        ];

    }



}