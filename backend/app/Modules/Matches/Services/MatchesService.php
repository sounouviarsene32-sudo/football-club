<?php

namespace App\Modules$module\Services;

use App\Modules$module\Repositories${MODEL}RepositoryInterface;

class MatchesService implements MatchesServiceInterface
{
    public function __construct(public MatchesRepositoryInterface $repository)
    {
        //
    }
}
