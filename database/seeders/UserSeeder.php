<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'uuid' => Str::uuid(),
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email'=> 'thesamuelfort@gmail.com',
            'mobile' => '0248297302',
            'password' => Hash::make('123'),
            'email_verified_at' => now(),
        ]);
    }
}
