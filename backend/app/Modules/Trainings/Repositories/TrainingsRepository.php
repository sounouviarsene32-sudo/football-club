<?php

namespace App\Modules$module\Repositories;

use App\Modules$module\Models$MODEL;
use Illuminate\Support\Collection;

class TrainingsRepository implements TrainingsRepositoryInterface
{
    public function all(): Collection
    {
        return Trainings::all();
    }

    public function find(int|string $id): ?Trainings
    {
        return Trainings::find($id);
    }

    public function create(array $data): Trainings
    {
        return Trainings::create($data);
    }

    public function update(int|string $id, array $data): bool
    {
        $item = $this->find($id);
        return $item ? $item->update($data) : false;
    }

    public function delete(int|string $id): bool
    {
        $item = $this->find($id);
        return $item ? $item->delete() : false;
    }
}
