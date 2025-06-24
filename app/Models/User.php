<?php

namespace App\Models;

 use App\Notifications\PasswordResetNotification;
 use App\Traits\BootModelTrait;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
 use Illuminate\Database\Eloquent\Casts\Attribute;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 use Illuminate\Support\Carbon;
 use Spatie\MediaLibrary\HasMedia;
 use Spatie\MediaLibrary\InteractsWithMedia;

 /**
 * @property string $id
 * @property string $uuid
 * @property string $firstname
 * @property string $lastname
 * @property string $fullname
 * @property string $email
 * @property string $facebook
 * @property string $x
 * @property string $instagram
 * @property string $linkedin
 * @property string $email_verified_at
 * @property string $password
 * @property bool $is_active
 * @property string $last_login
 * @property int $created_by_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $initials
 * @property string $google_id
 */

 class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasFactory, Notifiable, \Illuminate\Auth\MustVerifyEmail, BootModelTrait, InteractsWithMedia;

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected $appends = [
        'initials', 'fullname', 'avatar',
    ];

     public function registerMediaCollections(): void
     {
         $this->addMediaCollection('avatar')
             ->singleFile();
     }

    public function fullname(): Attribute
    {
        return Attribute::make(fn () => "$this->lastname $this->firstname");
    }

    public function initials(): Attribute
    {
        return Attribute::make(fn () => $this->lastname[0] . $this->firstname[0]);
    }

    public function lastLogin(): Attribute
    {
        return Attribute::make(fn ($value) => Carbon::parse($value)->format(dateFormat(true)));
    }

    public function avatar(): Attribute
    {
        return Attribute::make(fn () => $this->getFirstMedia('avatar'));
    }
}
