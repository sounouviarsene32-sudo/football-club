<?php

namespace App\Modules$module\Repositories;

use App\Modules$module\Models$MODEL;
use Illuminate\Support\Collection;

interface EventsRepositoryInterface
{
    public function all(): Collection;
    public function find(int|string $id): ?Events;
    public function create(array $data): Events;
    public function update(int|string $id, array $data): bool;
    public function delete(int|string $id): bool;
}
