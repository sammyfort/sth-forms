<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BootModelTrait;

/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

class Business extends Model
{
    //
    use BootModelTrait;
}
