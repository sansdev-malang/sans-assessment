<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SPMBDbSeeder extends Seeder
{
    /**
     * Seed the database with Administrator account only.
     */
    public function run(): void
    {
        // Akun Administrator Tunggal SPMB (Mencakup SD & SMP)
        User::updateOrCreate(
            ['email' => 'superadmin@sans.dev'],
            [
                'name' => 'Administrator SPMB',
                'password' => Hash::make('sans1234'),
            ]
        );
    }
}
