<?php

namespace App\Services;

use App\Repositories\RepositoryInterface;

interface ServiceInterface
{
    public function __construct(RepositoryInterface $repository);
}
