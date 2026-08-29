<?php

namespace App\Modules$module\Repositories;

use App\Modules$module\Models$MODEL;
use Illuminate\Support\Collection;

interface TrainingsRepositoryInterface
{
    public function all(): Collection;
    public function find(int|string $id): ?Trainings;
    public function create(array $data): Trainings;
    public function update(int|string $id, array $data): bool;
    public function delete(int|string $id): bool;
}
