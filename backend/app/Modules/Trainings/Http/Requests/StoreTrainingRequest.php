<?php

namespace App\Modules\Trainings\Http\Requests;

use App\Modules\Trainings\Http\Requests\ApiRequest;

class StoreTrainingRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'team_id' => ['required', 'integer', 'exists:teams,id'],
            'date' => ['required', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
