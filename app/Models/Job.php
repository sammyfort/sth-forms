<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use App\Traits\HasMediaUploads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

class Job extends Model implements  HasMedia
{
    //
    use BootModelTrait, HasMediaUploads, InteractsWithMedia;

    protected $table = '_jobs';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function user(): BelongsTo
    {
        return  $this->belongsTo(User::class);
    }
}
