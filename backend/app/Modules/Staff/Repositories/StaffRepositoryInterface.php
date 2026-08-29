<?php

namespace App\Modules$module\Repositories;

use App\Modules$module\Models$MODEL;
use Illuminate\Support\Collection;

interface StaffRepositoryInterface
{
    public function all(): Collection;
    public function find(int|string $id): ?Staff;
    public function create(array $data): Staff;
    public function update(int|string $id, array $data): bool;
    public function delete(int|string $id): bool;
}
