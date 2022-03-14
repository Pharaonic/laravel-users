<?php

namespace Pharaonic\Laravel\Users\Models\Roles;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $role_id
 * @property Role $role
 * 
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
class Roleable extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['role_id'];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the parent model
     */
    public function roleable()
    {
        return $this->morphTo();
    }
}
