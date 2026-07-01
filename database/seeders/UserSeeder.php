<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Tamus Tahir',
                'email' => 'tamus@gmail.com',
                'role' => 'Superadmin',
            ],
            [
                'name' => 'HR Staff',
                'email' => 'hr@example.com',
                'role' => 'hr',
            ],
            [
                'name' => 'Finance Staff',
                'email' => 'finance@example.com',
                'role' => 'finance',
            ],
            [
                'name' => 'Employee',
                'email' => 'employee@example.com',
                'role' => 'employee',
            ],
        ];

        foreach ($users as $user) {
            if (User::where('email', $user['email'])->exists()) {
                continue;
            }

            User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ]);
        }
    }
}
