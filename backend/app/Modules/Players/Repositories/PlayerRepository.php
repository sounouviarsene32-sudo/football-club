<?php

namespace App\Modules\Players\Repositories;

use App\Modules\Players\Models\Player;
use Illuminate\Support\Collection;

class PlayerRepository implements PlayerRepositoryInterface
{
    public function all(): Collection
    {
        return Player::all();
    }

    public function find(int|string $id): ?Player
    {
        return Player::find($id);
    }

    public function create(array $data): Player
    {
        return Player::create($data);
    }

    public function update(int|string $id, array $data): bool
    {
        $player = $this->find($id);
        return $player ? $player->update($data) : false;
    }

    public function delete(int|string $id): bool
    {
        $player = $this->find($id);
        return $player ? $player->delete() : false;
    }
}
