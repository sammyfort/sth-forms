<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use App\Traits\HasPromotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

class Promotion extends Model
{
    //
    use BootModelTrait, HasPromotion;

    public function promotable(): MorphTo
    {
        return $this->morphTo();
    }
}
