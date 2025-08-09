<?php

namespace App\Console\Commands;

use App\Models\ServiceCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportServiceCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import service Categories from old database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $importCategories = include database_path('import/j_categories.php');
        $insert = [];

        foreach ($importCategories as $category) {
            $data = [
                'old_id' => $category['j_id'],
                'name' => $category['name'],
                'description' => $category['j_desc'],
                'uuid' => Str::uuid(),
                'slug' => str::slug($category['name']),
            ];
            $insert[] = $data;
        }

        $insertFinal = array_values(array_reduce($insert, function ($carry, $item) {
            if (!isset($carry[$item['slug']])) {
                $carry[$item['slug']] = $item;
            }
            return $carry;
        }, []));

        ServiceCategory::query()->fillAndInsert($insertFinal);

        $this->info('services categories imported');
    }
}
