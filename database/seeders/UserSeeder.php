<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Product;
use App\Models\ProductCategory;
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
                'referral_code' => Str::random(10),
                'firstname' => 'Admin',
                'lastname' => 'User',
                'email' => 'thesamuelfort@gmail.com',
                'mobile' => '0248297302',
                'password' => "123",
                'email_verified_at' => now(),
            ],

            [
                'uuid' => Str::uuid(),
                'referral_code' => Str::random(10),
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
            'referral_code' => Str::random(10),
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'admin@app.com',
            'mobile' => '0257906340',
            'password' => "123",
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $signboardCategories = SignboardCategory::query()->pluck('id');
        $jobCategories = JobCategory::query()->pluck('id');
        $productCategories = ProductCategory::query()->pluck('id');
        $regions = Region::query()->pluck('id');

        foreach ($users as $userData) {
            $user = User::query()->create($userData);

//            Business::factory(3)
//                ->for($user)
//                ->create([
//                    'created_by_id' => $user->id
//                ])
//                ->each(function ($business) use ($user, $signboardCategories, $regions) {
//                    Signboard::factory(2)
//                        ->for($business)
//                        ->create([
//                            'region_id' => $regions->random(),
//                            'created_by_id' => $user->id
//                        ])
//                        ->each(function (Signboard $signboard) use ($signboardCategories) {
//                            $signboard->categories()
//                                ->attach($signboardCategories->take(rand(3, 10))->toArray());
//                            $this->addMedia($signboard, 'featured');
//                            foreach (range(0, 2) as $i){
//                                $this->addMedia($signboard, 'gallery');
//                            }
//                        });
//                });
//
//            Service::factory(3)
//                ->for($user)
//                ->create([
//                    'created_by_id' => $user->id
//                ])
//                ->each(function ($service){
//                    $this->addMedia($service, 'featured');
//                    foreach (range(0, 2) as $i){
//                        $this->addMedia($service, 'gallery');
//                    }
//                });
//
//            Job::factory(3)
//                ->for($user)
//                ->create([
//                    'region_id' => $regions->random(),
//                    'created_by_id' => $user->id
//                ])
//                ->each(function (Job $job) use($jobCategories){
//                    $job->categories()->attach($jobCategories->take(rand(4, 10))->toArray());
//                    $this->addMedia($job, 'company_logo');
//                });
//
//            Product::factory(3)
//                ->for($user)
//                ->create([
//                    'region_id' => $regions->random(),
//                    'created_by_id' => $user->id
//                ])
//                ->each(function (Product $product) use ($productCategories){
//                    $product->categories()->attach($productCategories->take(rand(4, 10))->toArray());
//                    $this->addMedia($product, 'featured');
//
//                    foreach (range(0, 2) as $i){
//                        $this->addMedia($product, 'gallery');
//                    }
//                });
        }
    }

    private function addMedia($mediable, $collection): void
    {
        try {
            $mediable->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection($collection);
        } catch (\Exception $e){
            $mediable->addMediaFromUrl('https://fastly.picsum.photos/id/368/200/300.jpg?hmac=qqvgzPEXwcvVBrpVDtVeofz3jGWFgOVpRiiQU_ddP8Y')
                ->toMediaCollection($collection);
        }
    }
}
