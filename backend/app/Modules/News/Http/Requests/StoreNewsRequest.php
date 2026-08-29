<?php

namespace App\Modules\News\Http\Requests;

use App\Modules\News\Http\Requests\ApiRequest;

class StoreNewsRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'published_at' => ['required', 'date'],
            'author_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
