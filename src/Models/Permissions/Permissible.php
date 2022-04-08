<?php

namespace Pharaonic\Laravel\Users\Models\Permissions;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $permission_id
 * @property Permission $permission
 *
 * @author Moamen Eltouny (Raggi) <support@raggitech.com>
 */
class Permissible extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['permission_id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * Get the parent model
     */
    public function permissible()
    {
        return $this->morphTo();
    }
}
