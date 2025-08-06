<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws \Throwable
     */
    public function run(): void
    {

        $users = include database_path('import/j_users.php');

        DB::transaction(function () use ($users) {
            foreach ($users as $user) {

                $mobile = trim($user['j_phone'] ?? '');
                $email = trim($user['j_email'] ?? '');

                if (empty($mobile) || User::where('mobile', $mobile)->exists()) {
                    continue;
                }
                if (empty($email) || User::where('email', $email)->exists()) {
                    continue;
                }

                User::create([
                    'old_id'      => $user['j_id'],
                    'firstname'         => $user['j_firstname'],
                    'lastname'          => $user['j_lastname'],
                    'email'             => $email,
                    'mobile'            => $mobile,
                    'password'          => Hash::make('123456'),
                    'email_verified_at' => now(),
                ]);
            }
        });

    }
}
