<?php

namespace App\Modules\Competitions\Http\Requests;

use App\Modules\Competitions\Http\Requests\ApiRequest;

class StoreCompetitionRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'season' => ['required', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'type' => ['required', 'string', 'max:50'],
        ];
    }
}
