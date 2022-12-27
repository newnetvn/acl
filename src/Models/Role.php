<?php

namespace Newnet\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Core\Support\Traits\CacheableTrait;

/**
 * Newnet\Acl\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property bool $is_admin
 * @property string|null $permissions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Newnet\Acl\Models\Admin[] $users
 * @property-read int|null $users_count
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Acl\Models\Role newModelQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Acl\Models\Role newQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Acl\Models\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Role whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Role wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Acl\Models\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    use CacheableTrait;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_admin',
        'permissions',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(Admin::class);
    }
}
