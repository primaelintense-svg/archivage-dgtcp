<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nom' => 'Zoutougou',
            'prenom' => 'Primaël',
            'email' => 'agent@dgtcp.bj',
            'password' => Hash::make('password'),
            'role' => 'agent_comptable',
        ]);

        User::create([
            'nom' => 'Adjovi',
            'prenom' => 'Chantal',
            'email' => 'archiviste@dgtcp.bj',
            'password' => Hash::make('password'),
            'role' => 'archiviste',
        ]);

        User::create([
            'nom' => 'Houngbo',
            'prenom' => 'Roger',
            'email' => 'admin@dgtcp.bj',
            'password' => Hash::make('password'),
            'role' => 'administrateur',
        ]);

        User::create([
            'nom' => 'Visiteur',
            'prenom' => 'Test',
            'email' => 'visiteur@dgtcp.bj',
            'password' => Hash::make('password'),
            'role' => 'visiteur',
        ]);
    }
}
