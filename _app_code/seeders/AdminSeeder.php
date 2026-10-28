<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Admin utama
        User::updateOrCreate(
            ['email' => 'admin@perpusbangkalan.go.id'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@perpusbangkalan.go.id',
                'password' => Hash::make('Admin@Perpus2024!'),
                'role'     => 'admin',
                'is_active' => true,
            ]
        );

        // Petugas contoh
        User::updateOrCreate(
            ['email' => 'petugas@perpusbangkalan.go.id'],
            [
                'name'     => 'Petugas Perpustakaan',
                'email'    => 'petugas@perpusbangkalan.go.id',
                'password' => Hash::make('Petugas@2024!'),
                'role'     => 'petugas',
                'is_active' => true,
            ]
        );
    }
}
