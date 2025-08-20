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
 * @property string $full_name
 * @property string $phone
 * @property string $email
 * @property string $code
*/

class Voucher extends Model
{
    //
    use BootModelTrait;
}
