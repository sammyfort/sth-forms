<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Job;
use App\Models\Region;
use App\Models\Service;
use App\Models\Signboard;
use App\Models\SignboardCategory;
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
        $users = [
            [
                'uuid' => Str::uuid(),
                'firstname' => 'Admin',
                'lastname' => 'User',
                'email' => 'thesamuelfort@gmail.com',
                'mobile' => '0248297302',
                'password' => "123",
                'email_verified_at' => now(),
            ],

            [
                'uuid' => Str::uuid(),
                'firstname' => 'Emmanuel',
                'lastname' => 'Arhinful',
                'email' => 'fii@gmail.com',
                'mobile' => '0507455860',
                'password' => "123",
                'email_verified_at' => now(),
            ]
        ];

        $admin = User::query()->create([
            'uuid' => Str::uuid(),
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'admin@app.com',
            'mobile' => '0257906340',
            'password' => "123",
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $signboardCategory = SignboardCategory::query()->pluck('id');
        $regions = Region::query()->pluck('id');

        foreach ($users as $userData) {
            $user = User::query()->create($userData);
            Business::factory(2)
                ->for($user)
                ->create([
                    'created_by_id' => $user->id
                ])
                ->each(function ($business) use ($user, $signboardCategory, $regions) {
                    Signboard::factory(3)
                        ->for($business)
                        ->create([
                            'region_id' => $regions->random(),
                            'created_by_id' => $user->id
                        ])
                        ->each(function (Signboard $signboard) use ($signboardCategory) {
                            $signboard->categories()
                                ->attach($signboardCategory->take(rand(3, 10))->toArray());
                            $signboard->addMediaFromUrl('https://picsum.photos/200/300')
                                ->toMediaCollection('featured');
                        });
                });

            Service::factory(5)
                ->for($user)
                ->create()
                ->each(function ($service){
                    $service->addMediaFromUrl('https://picsum.photos/200/300')
                        ->toMediaCollection('featured');
                });

            Job::factory(20)
                ->for($user)
                ->create()
                ->each(function ($job){
                    $job->addMediaFromUrl('https://picsum.photos/200/300')
                        ->toMediaCollection('company_logo');
                });
        }
    }
}
