<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate([
            'name' => 'Root User',
            'email' => 'root@gym.local',
            'password' => Hash::make('ChangeMe123!'),
            'role_id' => 1, // make sure role_id 1 exists
        ]);
    }
}
