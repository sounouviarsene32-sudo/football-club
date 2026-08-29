<?php

namespace App\Modules\Players\Repositories;

use App\Modules\Players\Models\Player;
use Illuminate\Support\Collection;

interface PlayerRepositoryInterface
{
    public function all(): Collection;
    public function find(int|string $id): ?Player;
    public function create(array $data): Player;
    public function update(int|string $id, array $data): bool;
    public function delete(int|string $id): bool;
}
