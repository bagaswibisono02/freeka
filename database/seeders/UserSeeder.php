<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();



        // Buat 1000 user random
        for ($i = 0; $i < 1000; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'), // default password semua user
                'contact' => $faker->phoneNumber,
                'hak_akses' => 'user',
            ]);
        }
    }
}
