<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
        'id' => $this->id,
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'email' => $this->email,
        'phone' => $this->phone,
        'address' => $this->address,
        'country' => $this->country,
        'role' => [
            'id' => $this->role->id,
            'name' => $this->role->name,
        ],
        'tasks' => $this->tasks(@$this->tasks)
       ];
    }

    private function tasks($tasks)
    {
        $data = [];
        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->id,
                'name' => $task->name,
                'status' => $task->status,
            ];
        }
        return $data;
    }
}
