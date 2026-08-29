<?php

namespace App\Modules\Staff\Http\Requests;

use App\Modules\Staff\Http\Requests\ApiRequest;

class StoreStaffRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:100'],
            'team_id' => ['required', 'integer', 'exists:teams,id'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ];
    }
}
