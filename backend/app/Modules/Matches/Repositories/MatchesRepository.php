<?php

namespace App\Modules$module\Repositories;

use App\Modules$module\Models$MODEL;
use Illuminate\Support\Collection;

class MatchesRepository implements MatchesRepositoryInterface
{
    public function all(): Collection
    {
        return Matches::all();
    }

    public function find(int|string $id): ?Matches
    {
        return Matches::find($id);
    }

    public function create(array $data): Matches
    {
        return Matches::create($data);
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
