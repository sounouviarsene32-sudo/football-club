<?php

namespace App\Modules$module\Repositories;

use App\Modules$module\Models$MODEL;
use Illuminate\Support\Collection;

class CompetitionsRepository implements CompetitionsRepositoryInterface
{
    public function all(): Collection
    {
        return Competitions::all();
    }

    public function find(int|string $id): ?Competitions
    {
        return Competitions::find($id);
    }

    public function create(array $data): Competitions
    {
        return Competitions::create($data);
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
