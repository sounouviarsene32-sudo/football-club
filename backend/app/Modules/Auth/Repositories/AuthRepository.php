<?php

namespace App\Modules\Auth\Repositories;

use App\Models\User;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Créer un nouvel utilisateur
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Trouver un utilisateur par email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Trouver un utilisateur par ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }
}
