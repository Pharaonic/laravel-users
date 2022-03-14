<?php

namespace Pharaonic\Laravel\Users\Models\Roles;

use Illuminate\Database\Eloquent\Model;

class RoleTranslation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['locale', 'role_id', 'title'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
