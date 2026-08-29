<?php

namespace App\Modules\Teams\Http\Requests;

use App\Modules\Teams\Http\Requests\ApiRequest;

class StoreTeamRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'stadium' => ['nullable', 'string', 'max:255'],
            'founded' => ['nullable', 'integer', 'min:1800'],
        ];
    }
}
