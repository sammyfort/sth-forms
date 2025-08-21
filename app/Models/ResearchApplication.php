<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

class ResearchApplication extends Model
{
    //
    use BootModelTrait;

    protected function casts(): array
    {
        return [
            'documents' => 'array',
            'study_design' => 'array',
            'staff_categories' => 'array',
        ];
    }
}
