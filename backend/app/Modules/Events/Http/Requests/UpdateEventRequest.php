<?php

namespace App\Modules\Events\Http\Requests;

use App\Modules\Events\Http\Requests\ApiRequest;

class UpdateEventRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'date' => ['sometimes', 'date'],
            'location' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ];
    }
}
