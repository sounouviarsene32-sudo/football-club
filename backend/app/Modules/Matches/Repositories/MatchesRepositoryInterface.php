<?php

namespace App\Modules$module\Repositories;

use App\Modules$module\Models$MODEL;
use Illuminate\Support\Collection;

interface MatchesRepositoryInterface
{
    public function all(): Collection;
    public function find(int|string $id): ?Matches;
    public function create(array $data): Matches;
    public function update(int|string $id, array $data): bool;
    public function delete(int|string $id): bool;
}
