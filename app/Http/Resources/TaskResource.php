<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'subject' => $this->subject,
            'system_id' => $this->system_id,
            'mode_id' => $this->mode_id,
            'definition' => $this->definition,
            'status_id' => $this->status_id,
            'percentage' => $this->percentage,
            'added_by' => $this->added_by,
            'created_at' =>	$this->created_at,
            'updated_at' =>	$this->updated_at
        ];
    }
}
