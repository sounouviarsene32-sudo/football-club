<?php

namespace App\Modules$module\Repositories;

use App\Modules$module\Models$MODEL;
use Illuminate\Support\Collection;

class EventsRepository implements EventsRepositoryInterface
{
    public function all(): Collection
    {
        return Events::all();
    }

    public function find(int|string $id): ?Events
    {
        return Events::find($id);
    }

    public function create(array $data): Events
    {
        return Events::create($data);
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
