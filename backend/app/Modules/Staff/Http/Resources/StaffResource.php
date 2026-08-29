<?php

namespace App\Modules\Staff\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'role' => $this->role,
            'team_id' => $this->team_id,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
