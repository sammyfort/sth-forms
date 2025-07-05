<?php

namespace App\Models;

use App\Enums\ContactUsMessageStatus;
use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

class ContactUsMessage extends Model
{
    //
    use BootModelTrait;

    protected $casts = [
        'status' => ContactUsMessageStatus::class,
    ];
}
