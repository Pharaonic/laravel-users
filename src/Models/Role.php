<?php

namespace Pharaonic\Laravel\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Translatable\Translatable;
use Pharaonic\Laravel\Users\Traits\HasPermissions;

/**
 * @property integer $id
 * @property string $code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
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
     * @param string|null $locale
     * @return Permission
     */
    public static function create(string $code, $title, string $locale = null)
    {
        $role = static::findByCode($code);

        if (!$role) {
            $role = new static();
            $role->code = $code;
            $role->save();
        }

        if (is_array($title)) {
            foreach ($title as $locale => $t)
                $role->translateOrNew($locale)->title = $t;
        } else {
            $role->translateOrNew($locale ?? app()->getLocale())->title = $title;
        }

        $role->save();

        return $role;
    }
}
