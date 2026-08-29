<?php

namespace App\Modules$module\Repositories;

use App\Modules$module\Models$MODEL;
use Illuminate\Support\Collection;

class StaffRepository implements StaffRepositoryInterface
{
    public function all(): Collection
    {
        return Staff::all();
    }

    public function find(int|string $id): ?Staff
    {
        return Staff::find($id);
    }

    public function create(array $data): Staff
    {
        return Staff::create($data);
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
