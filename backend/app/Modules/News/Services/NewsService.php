<?php

namespace App\Modules$module\Services;

use App\Modules$module\Repositories${MODEL}RepositoryInterface;

class NewsService implements NewsServiceInterface
{
    public function __construct(public NewsRepositoryInterface $repository)
    {
        //
    }
}
