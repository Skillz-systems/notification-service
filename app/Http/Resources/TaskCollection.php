<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
        // return [
        //     'data' => $this->collection,
        //     'meta' => [
        //         'total' => $this->total(),
        //         'count' => $this->count(),
        //         'per_page' => $this->perPage(),
        //         'current_page' => $this->currentPage(),
        //         'total_pages' => $this->lastPage(),
        //     ],
        // ];
    }



}