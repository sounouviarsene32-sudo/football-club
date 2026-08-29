<?php

namespace App\Modules\Players\Http\Requests;

use App\Modules\Players\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class StorePlayerRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date'],
            'nationality' => ['required', 'string', 'max:100'],
            'team_id' => ['required', 'integer', 'exists:teams,id'],
        ];
    }
}
