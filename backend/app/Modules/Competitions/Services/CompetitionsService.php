<?php

namespace App\Modules$module\Services;

use App\Modules$module\Repositories${MODEL}RepositoryInterface;

class CompetitionsService implements CompetitionsServiceInterface
{
    public function __construct(public CompetitionsRepositoryInterface $repository)
    {
        //
    }
}
