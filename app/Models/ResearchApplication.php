<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_code', 'code');
    }

    protected function casts(): array
    {
        return [
            'documents' => 'array',
            'study_design' => 'array',
            'staff_categories' => 'array',
        ];
    }
}
