<?php

namespace Newnet\Acl\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Newnet\Acl\Traits\HasPermission;
use Newnet\Core\Support\Traits\CacheableTrait;
use Newnet\Media\Traits\HasMediaTrait;

/**
 * Newnet\Acl\Models\Admin
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property bool $is_admin
 * @property string|null $permissions
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $avatar
 * @property-read \Illuminate\Database\Eloquent\Collection|\Newnet\Media\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Newnet\Acl\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Acl\Models\Admin newModelQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Acl\Models\Admin newQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Acl\Models\Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Admin whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Admin whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Admin wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasPermission;
    use HasMediaTrait;
    use CacheableTrait;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin'          => 'boolean',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getAvatarAttribute()
    {
        if ($this->hasMedia('avatar')) {
            return $this->getFirstMedia('avatar');
        }

        return config('acl.default_avatar') ?: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mOccAMAAf4BaqRcrJYAAAAASUVORK5CYII=';
    }
}
