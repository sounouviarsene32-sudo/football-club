<?php

namespace App\Modules\Staff\Http\Requests;

use App\Modules\Staff\Http\Requests\ApiRequest;

class UpdateStaffRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'role' => ['sometimes', 'string', 'max:100'],
            'team_id' => ['sometimes', 'integer', 'exists:teams,id'],
            'email' => ['sometimes', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ];
    }
}
