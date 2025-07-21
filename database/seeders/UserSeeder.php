<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin users who can manage words
        $users = [
            [
                'name' => 'doda',
                'email' => 'doda@example.com',
                'password' => Hash::make('password'),
                'can_manage_words' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'jondo',
                'email' => 'jondo@example.com',
                'password' => Hash::make('password'),
                'can_manage_words' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'gio',
                'email' => 'gio@example.com',
                'password' => Hash::make('password'),
                'can_manage_words' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'keti',
                'email' => 'keti@example.com',
                'password' => Hash::make('password'),
                'can_manage_words' => true,
                'email_verified_at' => now(),
            ],
              [
                'name' => 'lado',
                'email' => 'lado@example.com',
                'password' => Hash::make('password'),
                'can_manage_words' => true,
                'email_verified_at' => now(),
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('Users seeded successfully!');
    }
}