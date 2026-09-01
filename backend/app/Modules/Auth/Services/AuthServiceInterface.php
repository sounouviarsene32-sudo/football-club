<?php

namespace App\Modules\Auth\Services;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * Enregistrer un nouvel utilisateur
     *
     * @param array $data
     * @return array{user: User, token: string}
     */
    public function register(array $data): array;

    /**
     * Connexion utilisateur
     *
     * @param array $credentials
     * @return array{user: User, token: string}|null
     */
    public function login(array $credentials): ?array;

    /**
     * Déconnexion utilisateur
     *
     * @param User $user
     * @return void
     */
    public function logout(User $user): void;

    /**
     * Rafraîchir le token
     *
     * @param User $user
     * @return array{token: string}
     */
    public function refreshToken(User $user): array;
}
