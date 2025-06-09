<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! env('PLEB_PASSWORD') || ! env('TRAINER_PASSWORD')) {
            throw new \Exception('Please set PLEB_PASSWORD and TRAINER_PASSWORD in your .env file.');
        }

        $password = Hash::make(env('PLEB_PASSWORD'));
        $password2 = Hash::make(env('TRAINER_PASSWORD'));

        $data = [
            [
                'email' => 'calina',
                'name' => 'Călina',
                'password' => $password,
            ],
            [
                'email' => 'cristi',
                'name' => 'Cristi',
                'password' => $password2,
            ],
        ];

        foreach ($data as $item) {
            if (\App\Models\User::where('email', $item['email'])->exists()) {
                continue; // Skip if user already exists
            }

            // Create or update the user with the given email and password
            \App\Models\User::updateOrCreate($item);
        }
    }
}
