<?php

namespace App\Modules$module\Services;

use App\Modules$module\Repositories${MODEL}RepositoryInterface;

class StaffService implements StaffServiceInterface
{
    public function __construct(public StaffRepositoryInterface $repository)
    {
        //
    }
}
