<?php

namespace App\Modules\Trainings\Http\Requests;

use App\Modules\Trainings\Http\Requests\ApiRequest;

class UpdateTrainingRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'team_id' => ['sometimes', 'integer', 'exists:teams,id'],
            'date' => ['sometimes', 'date'],
            'location' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
