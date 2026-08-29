<?php

namespace App\Modules\Players\Http\Requests;

use App\Modules\Players\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class UpdatePlayerRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'position' => ['sometimes', 'string', 'max:100'],
            'birth_date' => ['sometimes', 'date'],
            'nationality' => ['sometimes', 'string', 'max:100'],
            'team_id' => ['sometimes', 'integer', 'exists:teams,id'],
        ];
    }
}
