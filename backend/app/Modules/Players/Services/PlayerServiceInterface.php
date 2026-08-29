<?php

namespace App\Modules\Players\Services;

use App\Modules\Players\Repositories\PlayerRepositoryInterface;

interface PlayerServiceInterface
{
    public function __construct(PlayerRepositoryInterface $repository);
}
