<?php

namespace App\Modules$module\Repositories;

use App\Modules$module\Models$MODEL;
use Illuminate\Support\Collection;

class TeamsRepository implements TeamsRepositoryInterface
{
    public function all(): Collection
    {
        return Teams::all();
    }

    public function find(int|string $id): ?Teams
    {
        return Teams::find($id);
    }

    public function create(array $data): Teams
    {
        return Teams::create($data);
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
