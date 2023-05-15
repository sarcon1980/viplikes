<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        $userAdmin = User::query()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
        ]);

        $userAdmin->profile()->create([
            'last_name'=> '',
            'first_name' => 'Админ'
        ] );

        if (config('app.env') !== 'production') {

            for ($i = 1; $i < 10; $i++) {
                $user = User::query()->create([
                    'name' => 'Test ' . $i,
                    'email' => 'test' . $i . '@test.com',
                    'email_verified_at' => rand(0, 1) ? now() : null,
                    'password' => Hash::make('123456'),
                    'remember_token' => Str::random(10),
                ]);

                $user->profile()->create([
                    'last_name' => Str::random(5),
                    'first_name' => Str::random(7)
                ]);
            }
        }
    }

}
