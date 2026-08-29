<?php

namespace App\Modules\Players\Services;

use App\Modules\Players\Repositories\PlayerRepositoryInterface;

class PlayerService implements PlayerServiceInterface
{
    public function __construct(public PlayerRepositoryInterface $repository)
    {
        //
    }
}
