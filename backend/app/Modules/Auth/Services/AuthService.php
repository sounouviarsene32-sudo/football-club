<?php

namespace App\Modules\Auth\Services;

use App\Models\User;
use App\Modules\Auth\Repositories\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly AuthRepositoryInterface $authRepository
    ) {}

    /**
     * Enregistrer un nouvel utilisateur
     *
     * @param array $data
     * @return array{user: User, token: string}
     */
    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        
        $user = $this->authRepository->create($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Connexion utilisateur
     *
     * @param array $credentials
     * @return array{user: User, token: string}|null
     */
    public function login(array $credentials): ?array
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();
        
        // Révoquer les anciens tokens
        $user->tokens()->delete();
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Déconnexion utilisateur
     *
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        // Révoquer le token actuel
        $user->currentAccessToken()->delete();
    }

    /**
     * Rafraîchir le token
     *
     * @param User $user
     * @return array{token: string}
     */
    public function refreshToken(User $user): array
    {
        // Révoquer le token actuel
        $user->currentAccessToken()->delete();
        
        // Créer un nouveau token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
        ];
    }
}
