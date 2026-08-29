<?php

namespace App\Modules\Events\Http\Requests;

use App\Modules\Events\Http\Requests\ApiRequest;

class StoreEventRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ];
    }
}
