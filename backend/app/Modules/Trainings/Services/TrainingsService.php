<?php

namespace App\Modules$module\Services;

use App\Modules$module\Repositories${MODEL}RepositoryInterface;

class TrainingsService implements TrainingsServiceInterface
{
    public function __construct(public TrainingsRepositoryInterface $repository)
    {
        //
    }
}
