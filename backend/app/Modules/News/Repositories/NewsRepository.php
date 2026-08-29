<?php

namespace App\Modules$module\Repositories;

use App\Modules$module\Models$MODEL;
use Illuminate\Support\Collection;

class NewsRepository implements NewsRepositoryInterface
{
    public function all(): Collection
    {
        return News::all();
    }

    public function find(int|string $id): ?News
    {
        return News::find($id);
    }

    public function create(array $data): News
    {
        return News::create($data);
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
