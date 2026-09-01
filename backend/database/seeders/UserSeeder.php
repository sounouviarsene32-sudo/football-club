<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Utilisateur admin de test
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@football-club.com',
            'password' => Hash::make('Admin123!'),
            'email_verified_at' => now(),
        ]);

        // Utilisateur standard de test
        User::create([
            'name' => 'Test User',
            'email' => 'test@football-club.com',
            'password' => Hash::make('Test123!'),
            'email_verified_at' => now(),
        ]);

        // Créer 10 utilisateurs aléatoires
        User::factory(10)->create();
    }
}
