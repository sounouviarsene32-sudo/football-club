<?php

namespace App\Actions;

use App\Services\ServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class Action
{
    abstract public function handle(Request $request): JsonResponse;
}
