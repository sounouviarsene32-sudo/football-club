<?php

namespace App\Modules\News\Http\Requests;

use App\Modules\News\Http\Requests\ApiRequest;

class UpdateNewsRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'content' => ['sometimes', 'string'],
            'published_at' => ['sometimes', 'date'],
            'author_id' => ['sometimes', 'integer', 'exists:users,id'],
        ];
    }
}
