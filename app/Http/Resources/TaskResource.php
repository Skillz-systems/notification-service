<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'processflow_id' => $this->processflow_id,
            'formbuilder_id' => $this->formbuilder_id,
            'due_at' => $this->due_at,
            'title' => $this->title,
            'for' => $this->for,
            'status' => $this->status,
            'content' => $this->content,
            'owner_email' => $this->owner_email,
            'url' => $this->url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}