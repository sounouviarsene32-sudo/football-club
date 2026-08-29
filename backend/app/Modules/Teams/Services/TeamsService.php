<?php

namespace App\Modules$module\Services;

use App\Modules$module\Repositories${MODEL}RepositoryInterface;

class TeamsService implements TeamsServiceInterface
{
    public function __construct(public TeamsRepositoryInterface $repository)
    {
        //
    }
}
