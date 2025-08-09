<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Console\Command;

class ImportServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:s';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import services from old database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        $services = include database_path('import/j_services.php');

        $users = User::query()->get();
        $categories = ServiceCategory::query()->get();

        foreach ($services as $service) {
            $user_id = null;
            $category_id = null;

            foreach ($users as $user) {
                if ($user->old_id == $service['j_user_id']) {
                    $user_id = $user->id;
                    break;
                }
            }
            if (is_null($user_id)) {
                continue;
            }

            foreach ($categories as $category) {
                similar_text(
                    strtolower($service['j_category']),
                    strtolower($category->name),
                    $percent
                );
                if ($percent > 70) {
                    $category_id = $category->id;
                    break;
                }
            }

            if (!$category_id){
                $exsCategory = ServiceCategory::query()
                    ->where('name', $service['j_category'])
                    ->first(['id']);
                if ($exsCategory) {
                    $category_id = $exsCategory->id;
                }
                else{
                    $category_id = ServiceCategory::query()->create([
                        'name' => $service['j_category'],
                    ])->id;
                }
            }

            Service::query()->create([
                'user_id' => $user_id,
                'created_by_id' => $user_id,
                'category_id' => $category_id,
                'description' => $service['j_desc'],
                'first_mobile' => $service['j_phone'],
                'whatsapp_mobile' => $service['j_whatsapp'],
                'email' => $service['j_email'],
                'address' => $service['j_address'],
                'town' => $service['j_city'],
                'title' => $service['j_title'] . " - " . $service['j_category'],
                'business_name' => $service['j_title'] . " - " . $service['j_category'],
            ]);
        }

        $this->info('services imported');

    }
}
