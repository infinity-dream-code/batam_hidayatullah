<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah user sudah ada
        $user = User::where('email', 'admin@batamhidayatullah.com')->first();
        
        if (!$user) {
            User::create([
                'kode' => 'ADM001',
                'name' => 'Administrator',
                'email' => 'admin@batamhidayatullah.com',
                'password' => Hash::make('password123'),
                'jabatan_id' => 1, // Set default 1 untuk admin
            ]);
        }
    }
}
