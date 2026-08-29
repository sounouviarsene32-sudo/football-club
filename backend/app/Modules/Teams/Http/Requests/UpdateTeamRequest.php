<?php

namespace App\Modules\Teams\Http\Requests;

use App\Modules\Teams\Http\Requests\ApiRequest;

class UpdateTeamRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'city' => ['sometimes', 'string', 'max:255'],
            'country' => ['sometimes', 'string', 'max:255'],
            'stadium' => ['nullable', 'string', 'max:255'],
            'founded' => ['nullable', 'integer', 'min:1800'],
        ];
    }
}
