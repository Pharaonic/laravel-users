<?php

namespace Pharaonic\Laravel\Users\Models\Roles;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Translatable\Translatable;
use Pharaonic\Laravel\Users\Models\Roleable;
use Pharaonic\Laravel\Users\Traits\HasPermissions;

/**
 * @property integer $id
 * @property string $code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @author Moamen Eltouny (Raggi) <support@raggitech.com>
 */
class Role extends Model
{
    use Translatable;
    use HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code'];

    /**
     * Translatable attributes names.
     *
     * @var array
     */
    protected $translatableAttributes = ['title'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($role) {
            $role->roleables()->delete();
        });
    }

    /**
     * Getting roles by code
     *
     * @param string $code
     * @return static|null
     */
    public static function findByCode(string ...$code)
    {
        $result = static::with('translations')->whereIn('code', $code);
        return count($code) > 1 ? $result->get() : $result->limit(1)->first();
    }

    /**
     * Create a new role
     *
     * @param string $code
     * @param array|string $title
     * @param array $permissions
     * @param string|null $locale
     * @return Role
     */
    public static function set(string $code, $title, array $permissions = [], string $locale = null)
    {
        $role = new self;
        $data = ['code' => $code];

        $localKey = $role->translationsKey ?? 'locale';

        if (is_array($title))
            $data[$localKey] = $title;
        else
            $data[$localKey][$locale ?? app()->getLocale()]['title'] = $title;

        $role->fill($data)->save();

        if (!empty($permissions))
            $role->syncPermissions($permissions);

        return $role;
    }

    /**
     * Get all attached roleables to the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function roleables()
    {
        return $this->hasMany(Roleable::class);
    }
}
