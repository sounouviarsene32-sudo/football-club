<?php

namespace App\Modules$module\Services;

use App\Modules$module\Repositories${MODEL}RepositoryInterface;

class EventsService implements EventsServiceInterface
{
    public function __construct(public EventsRepositoryInterface $repository)
    {
        //
    }
}
