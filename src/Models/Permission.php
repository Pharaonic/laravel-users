<?php

namespace Pharaonic\Laravel\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pharaonic\Laravel\Translatable\Translatable;

/**
 * @property integer $id
 * @property string $code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
class Permission extends Model
{
    use Translatable, SoftDeletes;

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
        static::deleting(function ($permission) {
            $permission->permissible()->delete();
        });
    }

    /**
     * Getting permissions by code
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
     * Create a new permission
     *
     * @param string $code
     * @param array|string $title
     * @param string|null $locale
     * @return Permission
     */
    public static function create(string $code, $title, string $locale = null)
    {
        $permission = static::findByCode($code);

        if (!$permission) {
            $permission = new static();
            $permission->code = $code;
            $permission->save();
        }

        if (is_array($title)) {
            foreach ($title as $locale => $t)
                $permission->translateOrNew($locale)->title = $t;
        } else {
            $permission->translateOrNew($locale ?? app()->getLocale())->title = $title;
        }

        $permission->save();

        return $permission;
    }

    /**
     * Get all attached Permissible to the permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function permissible()
    {
        return $this->hasMany(Permissible::class);
    }
}
